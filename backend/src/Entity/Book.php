<?php

namespace App\Entity;

use App\Form\DTO\AdminBookDTO;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "books")]
class Book
{
    #[ORM\Id()]
    #[ORM\Column(name: "id", type: "string", length: 36)]
    private string $id;

    #[ORM\Column(name: "title", type: "string", nullable: false)]
    private string $title;

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function updateFromAdmin(AdminBookDTO $dto): void
    {
        $this->title = $dto->title;
    }
}
