<?php

namespace App\Controller\Admin;

use App\Entity\DirecteurGeneral;
use App\Entity\Rh;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

class DirecteurGeneralCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DirecteurGeneral::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('nom'),
            Field::new('prenom'),
            EmailField::new('email'),
            Field::new('password'),
            Field::new('dateNaissance'),
            Field::new('lieuNaissance'),
            Field::new('sexe'),
            Field::new('adresse'),
            Field::new('numeroTelephone'),
            Field::new('ccp'),
            Field::new('SituationFamiliale'),
            Field::new('nombreEnfant'),
            Field::new('Salaire_de_base'),
            AssociationField::new('filiale'),
            AssociationField::new('poste'),
            ArrayField::new('roles')
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        $sendInvoice = Action::new('sendInvoice', 'Mise a jour password', 'fa fa-envelope')
            ->linkToUrl(function (DirecteurGeneral $directeurGeneral) {
                return '/afficherEmploye/'.$directeurGeneral->getId();
            });
        return $actions
            ->add(Crud::PAGE_INDEX, $sendInvoice->addCssClass('btn btn-outline-info mb-2'));
    }

}
