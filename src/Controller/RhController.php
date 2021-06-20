<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Filiale;
use App\Entity\Poste;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Date;

class RhController extends AbstractController
{
    #[Route('/rh', name: 'rh')]
    public function espacePersoRh(EmployeRepository $repository): Response
    {
        $id= $this->getUser()->getId();
        return $this->render('rh/index.html.twig', [
            'Employe' => $repository->find($id),
        ]);
    }
    #[Route('/rh/addEmploye', name: 'rh_ajouter_employe')]
    public function addEmploye(Request $request,UserPasswordEncoderInterface $encoder): Response
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
            ->add('submit',SubmitType::class)
            ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())
        {
            $data=$form->getData();
            $employe=new Employe();
            $employe->setNom($data['nom']);
            $employe->setPrenom($data['prenom']);
            $employe->setEmail($data['email']);
            $MotdePasseCrypte= $encoder->encodePassword($employe, $data['password']);
            $employe->setPassword($MotdePasseCrypte);
            $employe->setDateNaissance(new \DateTimeImmutable($data['date_de_naissance']->format('Y-m-d')));
            $employe->setLieuNaissance($data['lieu_de_naissance']);
            $employe->setNombreEnfant($data['nombre_des_enfants']);
            $employe->setNumeroTelephone($data['numero']);
            $employe->setFiliale($data['filiale']);
            $employe->setPoste($data['poste']);
            $employe->setSalaireDeBase($data['salaire_de_base']);
            $employe->setCcp($data['ccp']);
            $employe->setSexe($data['sexe']);
            $employe->setRoles([$data['roles']]);
            $this->getDoctrine()->getManager()->persist($employe);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('employe/formAdd.html.twig',['formila'=>$form->createView()]);
    }
    #[Route('/rh/updateEmploye/{id}', name: 'rh_modifier_employe')]
    public function updateEmploye(Request $request,UserPasswordEncoderInterface $encoder,int $id,
                                  EmployeRepository $employeRepository): Response
    {
        $employe=$employeRepository->find($id);
        $form=$this->createFormBuilder()

            ->add('nom', TextType::class, [ 'disabled' => true,
                'data' => $employe->getNom()
            ])
            ->add('prenom', TextType::class, [  'disabled' => true,
                'data' => $employe->getPrenom()
            ])
            ->add('email',EmailType::class, [
                'data' => $employe->getEmail()
            ])
            ->add('password',PasswordType::class, [
                'data' => $employe->getPassword()
            ])
            ->add('date_de_naissance',TextType::class, [  'disabled' => true,
                'data' => $employe->getDateNaissance()->format('Y-m-d')
            ])
            ->add('lieu_de_naissance', TextType::class,[  'disabled' => true,
                'data' => $employe->getLieuNaissance()
            ])
            ->add('sexe', ChoiceType::class,[  'disabled' => true, 'data'=> $employe->getSexe(),
                'choices'  => ['Mâle' => 'M', 'Femelle' => 'F']])
            ->add('address',TextType::class,  [
                'data' => $employe->getAdresse()
            ])
            ->add('numero',NumberType::class, [
                'data' => $employe->getNumeroTelephone()
            ])
            ->add('ccp', TextType::class, [
                'data' => $employe->getCcp()
            ])
            ->add('situation_familiale', ChoiceType::class,[ 'data'=> $employe->getSituationFamiliale(),
                'choices'  => ['Veuf(ve)' => 'V', 'Célibataire' => 'C', 'Marié(e)' => 'M', 'Divorcé(e)' => 'D']])

            ->add('nombre_des_enfants',IntegerType::class, [
                'data' => $employe->getNombreEnfant()
            ])
            ->add('salaire_de_base',IntegerType::class, [
                'data' => $employe->getSalaireDeBase()
            ])
            ->add('roles',ChoiceType::class,[ 'disabled' => true, 'choices'  => ['Employé' => 'ROLE_EMPLOYE']])
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
            ->add('submit',SubmitType::class)

            ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $data = $form->getData();
            $employe->setEmail($data['email']);
            $MotdePasseCrypte = $encoder->encodePassword($employe, $data['password']);
            $employe->setPassword($MotdePasseCrypte);
            $employe->setNombreEnfant($data['nombre_des_enfants']);
            $employe->setNumeroTelephone($data['numero']);
            $employe->setFiliale($data['filiale']);
            $employe->setAdresse($data['address']);
            $employe->setPoste($data['poste']);
            $employe->setSalaireDeBase($data['salaire_de_base']);
            $employe->setCcp($data['ccp']);
            $this->getDoctrine()->getManager()->persist($employe);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('employe/formAdd.html.twig',['formila'=>$form->createView()]);
    }
    #[Route('/rh/deleteEmploye/{id}', name: 'rh_supprimer_employe')]
    public function deleteEmploye(EmployeRepository $employeRepository,int $id): Response
    {
        $employe=$employeRepository->find($id);
        $this->getDoctrine()->getManager()->remove($employe);
        $this->getDoctrine()->getManager()->flush();
        return new Response('employe avec id='.$id.' est supprimé');
    }

    #[Route('/rh/listerEmploye', name: 'rh_lister_employe')]

    public function listerEmploye(EmployeRepository $repository): Response
    {   $filiale= $this->getUser()->getFiliale();

        return $this->render('rh/listEmploye.html.twig',['Employes'=>$repository->findBy(['filiale'=> $filiale])]);
    }

    #[Route('/rh/viewEmploye/{id}', name: 'rh_profile_employe')]
    public function viewEmploye(EmployeRepository $employeRepository,int $id): Response
    {
        $employe=$employeRepository->find($id);

        return $this->render('rh/formView.html.twig',['Employe'=>$employeRepository->find($id)]);
    }

    #[Route('/rh/addPost', name: 'rh_ajouter_poste')]
    public function addPost(Request $request): Response
    {
        $form=$this->createFormBuilder()
            ->add('nom')
            ->add('Montant_par_Heure_Supp', IntegerType::class)
            ->add('Salaire_par_Heure',IntegerType::class)
            ->add('Nombre_Jour_par_Semaine',IntegerType::class)
            ->add('Nombre_Heure_par_jour',IntegerType::class)
            ->add('submit',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid())
        {
            $data = $form->getData();
            $poste=new Poste();
            $poste->setNom($data['nom']);
            $poste->setMontantHeureSupp($data['Montant_par_Heure_Supp']);
            $poste->setSalaireParHeure($data['Salaire_par_Heure']);
            $poste->setNbJourSemaine($data['Nombre_Jour_par_Semaine']);
            $poste->setNbHeureJour($data['Nombre_Heure_par_jour']);
            $this->getDoctrine()->getManager()->persist($poste);
            $this->getDoctrine()->getManager()->flush();

        }

        return $this->render('rh/formAddposte.html.twig',['formPost'=>$form->createView()]);
    }
}
