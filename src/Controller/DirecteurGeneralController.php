<?php

namespace App\Controller;
use App\Entity\Probleme;
use App\Repository\PresenceRepository;
use App\Repository\BulletinRepository;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DirecteurGeneralController extends AbstractController
{
    #[Route('/dg', name: 'directeur_general')]
    public function espacePersoDG(EmployeRepository $repository): Response
    {
        $id= $this->getUser()->getId();
        return $this->render('directeur_general/index.html.twig', [
            'Employe' => $repository->find($id),
        ]);
    }


    #[Route('/dg/listerEmploye', name: 'dg_lister_employe')]

    public function listerEmploye(EmployeRepository $repository): Response
    {   // $filiale= $this->getUser()->getFiliale();

        return $this->render('directeur_general/listEmploye.html.twig',['Employes'=>$repository->findAll()]);
    }


    #[Route('/dg/viewEmploye/{id}', name: 'dg_profile_employe')]
    public function viewEmploye(EmployeRepository $employeRepository,int $id): Response
    {
        $employe=$employeRepository->find($id);
        return $this->render('directeur_general/formView.html.twig',['Employe'=>$employeRepository->find($id)]);
    }


    #[Route('/dg/viewBulletin/{id}', name: 'dg_view_bulletin')]
    public function viewBulletin(BulletinRepository $repository,EmployeRepository $employeRepository, int $id): Response
    {
        $id = $this->getUser()->getId();
        return $this->render('directeur_general/viewBulletin.html.twig',
            ['Bulletin' => $repository->findBy(['employe' => $id])]);
    }


    #[Route('/dg/bulletinEmploye/{id}', name: 'dg_bulletin_employe')]
    public function bulletinEmploye(BulletinRepository $repository,EmployeRepository $employeRepository, int $id): Response
    {
        $id=$employeRepository->find($id);
        return $this->render('directeur_general/bulletinEmploye.html.twig',
            ['Bulletin'=>$repository->findBy(['employe'=> $id ]),'Employe'=>$employeRepository->find($id)]);
    }


    #[Route('/dg/historiquePresence/{id}', name: 'dg_historique_presence')]
    public function historiquePresence(PresenceRepository $repository, int $id): Response
    {
        $id= $this->getUser()->getId();
        return $this->render('directeur_general/historiquePresence.html.twig',
            ['Presence'=>$repository->findBy(['employe'=> $id ])]);
    }
    #[Route('/dg/presenceEmploye/{id}', name: 'dg_presence_employe')]
    public function presenceEmploye(PresenceRepository $repository,EmployeRepository $employeRepository, int $id): Response
    {
        $id = $employeRepository->find($id);
        return $this->render('directeur_general/presenceEmploye.html.twig',
            ['Presence' => $repository->findBy(['employe' => $id]),'Employe'=>$employeRepository->find($id)]);
    }
    #[Route('/dg/modifierMdp/{id}', name: 'dg_modifier_mdp')]
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
            return $this->render('directeur_general/mdpChange.html.twig');
        }
        return $this->render('directeur_general/modifierMdp.html.twig',['formila'=>$form->createView()]);
    }

    #[Route('/dg/fichePaie/{id_bulletin}', name: 'dg_fiche_paie')]
    public function fichePaie(int $id_bulletin, BulletinRepository $bulletinRepository): Response
    {
        $bulletin = $bulletinRepository->find($id_bulletin);
        $employe = $bulletin->getEmploye();
        $date_recrutement = $employe->getDateRecrutement()->format('Y-m-d');
        $poste = $employe->getPoste();
        $salaireParHeure = ($poste->getSalaireDeBase()/($poste->getNbJourSemaine()*4*$poste->getNbHeureJour()));
        $montantHeureSupp = $bulletin->getTotalHeureSupp()*$salaireParHeure;
        $montantHeureAbs = $bulletin->getTotalHeureAbs()*$salaireParHeure;
        return $this->render('employe/fiche_de_paie.html.twig',
            ['Poste'=>$poste,'Employe'=>$employe,'Bulletin'=>$bulletin,'dateRecrutement'=>$date_recrutement,
                'salaireParHeure'=>$salaireParHeure,'montantHeureSupp'=>$montantHeureSupp,
                'montantHeureAbs'=>$montantHeureAbs]);
    }
    #[Route('/dg/probleme/{id}', name: 'dg_probleme')]
    public function probleme(Request $request,int $id
        ,EmployeRepository $employeRepository): Response
    {
        $id= $this->getUser()->getId();
        $employe=$employeRepository->find($id);
        $form=$this->createFormBuilder()
            ->add('Description',TextareaType::class)
            ->add('Signaler',SubmitType::class)
            ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $data = $form->getData();
            $probleme = new Probleme();
            $probleme->setEmploye($employe);
            $probleme->setDescription($data['Description']);
            $this->getDoctrine()->getManager()->persist($probleme);
            $this->getDoctrine()->getManager()->flush();
            return $this->render('directeur_general/probleme_envoye.html.twig');
        }
        return $this->render('directeur_general/probleme.html.twig',['formila'=>$form->createView()]);
    }
}
