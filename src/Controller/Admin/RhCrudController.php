<?php

namespace App\Controller\Admin;

use App\Entity\Rh;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
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
            ChoiceField::new('type')->setChoices([  'RH' => 'Rh']),
            AssociationField::new('filiale'),
            AssociationField::new('poste'),
        ];
    }

}
