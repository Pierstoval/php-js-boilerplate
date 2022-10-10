<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class BookFixtures extends ArrayFixture implements ORMFixtureInterface
{
    protected function getEntityClass(): string
    {
        return Book::class;
    }

    protected function getObjects(): iterable
    {
        yield ['id' => '96ba14a6-f7d3-42fb-b874-548d4d0f55c5', 'title' => 'Test book'];
    }
}
