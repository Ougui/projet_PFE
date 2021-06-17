<?php

namespace App\Controller\Admin;

use App\Entity\Employe;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

class EmployeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Employe::class;
    }



    public function configureFields(string $pageName): iterable
    {
       return [
            Field::new('id'),
            Field::new('nom'),
            Field::new('prenom'),
            Field::new('dateNaissance'),
            Field::new('lieuNaissance'),
            ChoiceField::new('sexe')->setChoices([  'mâle' => 'M',
                    'femelle' => 'F']
            ),
            Field::new('adresse'),
            Field::new('ccp'),
            ChoiceField::new('SituationFamiliale')->setChoices([  'Marié(e)' => 'M',
                    'Divorcé(e)' => 'D', 'Célibataire' => 'C','Veuf(ve)' => 'V']
            ),
            Field::new('nombreEnfant'),
            Field::new('Salaire_de_base'),
            ChoiceField::new('type')->setChoices([  'RH' => 'rh','Comptable' => 'comptable',
                'Directeur' => 'directeur','Directeur Général' => 'directeurGeneral']),
            AssociationField::new('filiale'),
            AssociationField::new('poste'),
        ];
    }

}
