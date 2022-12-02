<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\DTO\AdminBookDTO;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BookCrudController extends AbstractDtoCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    public static function getDtoFqcn(): string
    {
        return AdminBookDTO::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
        ];
    }
}
