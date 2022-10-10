<?php

namespace App\ApiResource;

use ApiPlatform\Metadata as Api;
use App\Entity\Book as BookEntity;
use App\State\EntityProvider;

#[Api\ApiResource(
    operations: [
        new Api\Get(provider: EntityProvider::class, extraProperties: ['entityClass' => BookEntity::class]),
        new Api\GetCollection(provider: EntityProvider::class, extraProperties: ['entityClass' => BookEntity::class]),
    ],
)]
class Book
{
    public string $id;
    public string $title;
}
