<?php

namespace App\Controller\Admin;

use App\Entity\Rh;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RhCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rh::class;
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
