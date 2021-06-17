<?php

namespace App\Controller\Admin;

use App\Entity\Directeur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DirecteurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Directeur::class;
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
