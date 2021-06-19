<?php

namespace App\Controller\Admin;

use App\Entity\Employe;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
class EmployeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Employe::class;
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
        $sendInvoice = Action::new('sendInvoice', 'Confirmer', 'fa fa-envelope')
            ->linkToUrl(function (Employe $employe) {
               return '/afficherEmploye/'.$employe->getId();
            });
        return $actions
            ->add(Crud::PAGE_INDEX, $sendInvoice->addCssClass('btn btn-outline-info mb-2'));
    }

}
