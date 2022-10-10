<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
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

        if ($operation instanceof CollectionOperationInterface) {
            $data = $this->collectionProvider->provide(
                $operation->withClass($operation->getExtraProperties()['entityClass']),
                $uriVariables,
                $context,
            );

            $processed = [];

            foreach ($data as $item) {
                $processed[] = $this->autoMapper->map($item, $resourceClass);
            }

            return $processed;
        }

        $data = $this->itemProvider->provide(
            $operation->withClass($operation->getExtraProperties()['entityClass']),
            $uriVariables,
            $context,
        );

        return $this->autoMapper->map($data, $resourceClass);
    }
}