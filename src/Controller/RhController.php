<?php

namespace App\Controller;

use App\Entity\Filiale;
use App\Entity\Poste;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RhController extends AbstractController
{
    #[Route('/rh', name: 'rh')]
    public function index(): Response
    {
        return $this->render('rh/index.html.twig', [
            'controller_name' => 'RhController',
        ]);
    }
    #[Route('/rh/addEmploye', name: 'rh_ajouter_employe')]
    public function addEmploye(): Response
    {
        $form=$this->createFormBuilder()
            ->add('nom')
            ->add('prenom')
            ->add('email',EmailType::class)
            ->add('password',PasswordType::class)
            ->add('date_de_naissance',DateType::class)
            ->add('lieu_de_naissance')
            ->add('sexe', ChoiceType::class, [
                'choices'  => ['Mâle' => 'M', 'Femelle' => 'F']])
            ->add('address')
            ->add('numero',NumberType::class)
            ->add('ccp')
            ->add('situation_familiale', ChoiceType::class, [
                'choices'  => ['Veuf(ve)' => 'V', 'Célibataire' => 'C', 'Marié(e)' => 'M', 'Divorcé(e)' => 'D']])

            ->add('nombre_des_enfants',IntegerType::class)
            ->add('salaire_de_base',IntegerType::class)
            ->add('roles',ChoiceType::class,['choices'  => ['Employé' => 'ROLE_EMPLOYE']])
            ->add('filiale',EntityType::class,[
                'class'=>Filiale::class,
                'query_builder'=>function(EntityRepository $entityRepository){
                    return $entityRepository->createQueryBuilder('f')
                        ->select('f');
                }
            ])
            ->add('poste',EntityType::class,[
                'class'=>Poste::class,
                'query_builder'=>function(EntityRepository $entityRepository){
                    return $entityRepository->createQueryBuilder('f')
                        ->select('f');
                }
            ])
            ->getForm()
        ;
        return $this->render('employe/formAdd.html.twig',['formila'=>$form->createView()]);
    }
}
