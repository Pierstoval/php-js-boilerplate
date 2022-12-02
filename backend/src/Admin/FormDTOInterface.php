<?php

declare(strict_types=1);

namespace App\Admin;

interface FormDTOInterface
{
    public static function createFromEntity(object $entity): static;

    public static function getEntityMutatorMethodName(): string;
}
