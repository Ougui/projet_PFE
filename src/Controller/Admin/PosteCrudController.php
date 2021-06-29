<?php

namespace App\Controller\Admin;

use App\Entity\Poste;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PosteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Poste::class;
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
