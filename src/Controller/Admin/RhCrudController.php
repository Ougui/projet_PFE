<?php

namespace App\Controller\Admin;

use App\Entity\Rh;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
class RhCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rh::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('id'),
            Field::new('nom'),
            Field::new('prenom'),
            Field::new('dateNaissance'),
            Field::new('lieuNaissance'),
            Field::new('sexe'),
            Field::new('adresse'),
            Field::new('ccp'),
            Field::new('SituationFamiliale'),
            Field::new('nombreEnfant'),
            Field::new('Salaire_de_base'),
            AssociationField::new('type'),
            AssociationField::new('filiale'),
            AssociationField::new('poste'),
        ];
    }
    
}
