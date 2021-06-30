<?php

namespace App\Controller;
use App\Repository\PresenceRepository;
use App\Repository\BulletinRepository;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DirecteurController extends AbstractController
{
    #[Route('/directeur', name: 'directeur')]
    public function espacePersoD(EmployeRepository $repository): Response
    {
        $id= $this->getUser()->getId();
        return $this->render('directeur/index.html.twig', [
            'Employe' => $repository->find($id),
        ]);
    }


    #[Route('/directeur/listerEmploye', name: 'directeur_lister_employe')]

    public function listerEmploye(EmployeRepository $repository): Response
    {   $filiale= $this->getUser()->getFiliale();

        return $this->render('directeur/listEmploye.html.twig',['Employes'=>$repository->findBy(['filiale'=> $filiale])]);
    }


    #[Route('/directeur/viewEmploye/{id}', name: 'directeur_profile_employe')]
    public function viewEmploye(EmployeRepository $employeRepository,int $id): Response
    {
        $employe=$employeRepository->find($id);
        return $this->render('directeur/formView.html.twig',['Employe'=>$employeRepository->find($id)]);
    }


    #[Route('/directeur/viewBulletin/{id}', name: 'directeur_view_bulletin')]
    public function viewBulletin(BulletinRepository $repository,EmployeRepository $employeRepository, int $id): Response
    {
        $id = $this->getUser()->getId();
        return $this->render('directeur/viewBulletin.html.twig',
            ['Bulletin' => $repository->findBy(['employe' => $id])]);
    }


    #[Route('/directeur/bulletinEmploye/{id}', name: 'directeur_bulletin_employe')]
    public function bulletinEmploye(BulletinRepository $repository,EmployeRepository $employeRepository, int $id): Response
    {
        $id=$employeRepository->find($id);
        return $this->render('directeur/bulletinEmploye.html.twig',
            ['Bulletin'=>$repository->findBy(['employe'=> $id ]),'Employe'=>$employeRepository->find($id)]);
    }


    #[Route('/directeur/historiquePresence/{id}', name: 'directeur_historique_presence')]
    public function historiquePresence(PresenceRepository $repository, int $id): Response
    {
        $id= $this->getUser()->getId();
        return $this->render('directeur/historiquePresence.html.twig',
            ['Presence'=>$repository->findBy(['employe'=> $id ])]);
    }
    #[Route('/directeur/presenceEmploye/{id}', name: 'directeur_presence_employe')]
    public function presenceEmploye(PresenceRepository $repository,EmployeRepository $employeRepository, int $id): Response
    {
        $id = $employeRepository->find($id);
        return $this->render('directeur/presenceEmploye.html.twig',
            ['Presence' => $repository->findBy(['employe' => $id]),'Employe'=>$employeRepository->find($id)]);
    }
    #[Route('/directeur/modifierMdp/{id}', name: 'directeur_modifier_mdp')]
    public function modifierMdp(Request $request,UserPasswordEncoderInterface $encoder,int $id
        ,EmployeRepository $employeRepository): Response
    {
        $id= $this->getUser()->getId();
        $employe=$employeRepository->find($id);
        $form=$this->createFormBuilder()
            ->add('password',PasswordType::class)
            ->add('Confirmer',SubmitType::class)

            ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $data = $form->getData();
            $MotdePasseCrypte= $encoder->encodePassword($employe, $data['password']);
            $employe->setPassword($MotdePasseCrypte);
            $this->getDoctrine()->getManager()->persist($employe);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('directeur/modifierMdp.html.twig',['formila'=>$form->createView()]);
    }
    #[Route('/directeur/fichePaie/{id_bulletin}', name: 'directeur_fiche_paie')]
    public function fichePaie(int $id_bulletin, BulletinRepository $bulletinRepository): Response
    {
        $bulletin = $bulletinRepository->find($id_bulletin);
        $employe = $bulletin->getEmploye();
        $date_recrutement = $employe->getDateRecrutement()->format('Y-m-d');
        $poste = $employe->getPoste();
        $salaireParHeure = ($poste->getSalaireDeBase()/($poste->getNbJourSemaine()*4*$poste->getNbHeureJour()));
        $montantHeureSupp = $bulletin->getTotalHeureSupp()*$salaireParHeure;
        $montantHeureAbs = $bulletin->getTotalHeureAbs()*$salaireParHeure;
        return $this->render('comptable/fiche_de_paie.html.twig',
            ['Poste'=>$poste,'Employe'=>$employe,'Bulletin'=>$bulletin,'dateRecrutement'=>$date_recrutement,
                'salaireParHeure'=>$salaireParHeure,'montantHeureSupp'=>$montantHeureSupp,
                'montantHeureAbs'=>$montantHeureAbs]);
    }
}
