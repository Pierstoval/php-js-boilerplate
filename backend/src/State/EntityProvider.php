<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Jane\Component\AutoMapper\AutoMapperInterface;

class EntityProvider implements ProviderInterface
{
    public function __construct(
        private readonly ProviderInterface $itemProvider,
        private readonly ProviderInterface $collectionProvider,
        private readonly AutoMapperInterface $autoMapper,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $resourceClass = $operation->getClass();
        $entityClass = $operation->getExtraProperties()['entityClass'];

        // That's where the h̶a̶c̶k̶ magick happens:
        // Since the ApiResource is not an Entity, native Doctrine item providers cannot be used,
        // so overriding it here allows triggering Api Platform's native providers.
        $operation = $operation->withClass($entityClass);

        if ($operation instanceof CollectionOperationInterface) {
            $data = $this->collectionProvider->provide(
                $operation,
                $uriVariables,
                $context,
            );

            $processed = [];

            foreach ($data as $item) {
                $processed[] = $this->autoMapper->map($item, $resourceClass);
            }

            return $processed;
        }

        if (property_exists($resourceClass, 'id') && $operation instanceof HttpOperation) {
            // Thanks to this comment:
            // https://github.com/Pierstoval/php-js-boilerplate/pull/2#issuecomment-1328688248
            // we have to override the "id" Link if it is in the ApiResource to avoid Api Platform calling
            // Doctrine on the ApiResource (which is not an Entity).
            $operation = $operation->withUriVariables(['id' => new Link(fromClass: $entityClass, identifiers: ['id'])]);
        }

        $data = $this->itemProvider->provide(
            $operation,
            $uriVariables,
            $context,
        );

        return $this->autoMapper->map($data, $resourceClass);
    }
}
