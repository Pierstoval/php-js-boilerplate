<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Doctrine\Persistence\ManagerRegistry;
use Jane\Component\AutoMapper\AutoMapperInterface;

class EntityProvider implements ProviderInterface
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly ProviderInterface $itemProvider,
        private readonly ProviderInterface $collectionProvider,
        private readonly AutoMapperInterface $autoMapper,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $resourceClass = $operation->getClass();
        $entityClass = $operation->getExtraProperties()['entityClass'];

        if ($operation instanceof CollectionOperationInterface) {
            $data = $this->collectionProvider->provide(
                $operation->withClass($entityClass),
                $uriVariables,
                $context,
            );

            $processed = [];

            foreach ($data as $item) {
                $processed[] = $this->autoMapper->map($item, $resourceClass);
            }

            return $processed;
        }

        if ($operation instanceof Get) {
            $data = $this->managerRegistry->getManagerForClass($entityClass)->find($entityClass, $uriVariables['id']);

            return $this->autoMapper->map($data, $resourceClass);
        }

        $data = $this->itemProvider->provide(
            $operation->withClass($entityClass),
            $uriVariables,
            $context,
        );

        return $this->autoMapper->map($data, $resourceClass);
    }
}
