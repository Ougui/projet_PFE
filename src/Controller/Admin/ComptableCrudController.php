<?php

namespace App\Controller\Admin;

use App\Entity\Comptable;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ComptableCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comptable::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
