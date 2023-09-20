<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'books')]
class Book
{
    #[ORM\Id()]
    #[ORM\Column(name: 'id', type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(name: 'title', type: 'string', nullable: false)]
    private string $title;

    public function __construct()
    {
        $this->id = (string) Uuid::v7();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title ?: '';
    }
}
