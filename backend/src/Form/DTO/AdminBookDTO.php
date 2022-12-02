<?php

declare(strict_types=1);

namespace App\Form\DTO;

use App\Admin\FormDTOInterface;
use App\Entity\Book;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminBookDTO implements FormDTOInterface
{
    #[NotBlank]
    public ?string $title = null;

    public static function getEntityMutatorMethodName(): string
    {
        return 'updateFromAdmin';
    }

    public static function createFromEntity(object $entity): static
    {
        \assert($entity instanceof Book);

        $self = new self();

        $self->title = $entity->getTitle();

        return $self;
    }
}