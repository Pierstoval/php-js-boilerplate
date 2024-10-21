<?php

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options as DoctrineORMOptions;
use ApiPlatform\Metadata as Api;
use App\Entity\Book as BookEntity;

#[Api\ApiResource(
    operations: [
        new Api\Get(
            stateOptions: new DoctrineORMOptions(entityClass: BookEntity::class)
        ),
        new Api\GetCollection(
            stateOptions: new DoctrineORMOptions(entityClass: BookEntity::class)
        ),
    ],
)]
class Book
{
    public string $id;
    public string $title;
}
