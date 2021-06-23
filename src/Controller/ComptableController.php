<?php

namespace App\Controller;
use App\Repository\PosteRepository;
use App\Repository\PresenceRepository;
use App\Repository\BulletinRepository;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComptableController extends AbstractController
{
    #[Route('/comptable', name: 'comptable')]
    public function espacePersoDG(EmployeRepository $repository): Response
    {
        $id= $this->getUser()->getId();
        return $this->render('comptable/index.html.twig', [
            'Employe' => $repository->find($id),
        ]);
    }


    #[Route('/comptable/listerEmploye', name: 'comptable_lister_employe')]

    public function listerEmploye(EmployeRepository $repository): Response
    {   // $filiale= $this->getUser()->getFiliale();

        return $this->render('comptable/listEmploye.html.twig',['Employes'=>$repository->findAll()]);
    }


    #[Route('/comptable/viewEmploye/{id}', name: 'comptable_profile_employe')]
    public function viewEmploye(EmployeRepository $employeRepository,int $id): Response
    {
        $employe=$employeRepository->find($id);
        return $this->render('comptable/formView.html.twig',['Employe'=>$employeRepository->find($id)]);
    }


    #[Route('/comptable/viewBulletin/{id}', name: 'comptable_view_bulletin')]
    public function viewBulletin(BulletinRepository $repository,EmployeRepository $employeRepository, int $id): Response
    {
        $id = $this->getUser()->getId();
        return $this->render('comptable/viewBulletin.html.twig',
            ['Bulletin' => $repository->findBy(['employe' => $id])]);
    }


    #[Route('/comptable/bulletinEmploye/{id}', name: 'comptable_bulletin_employe')]
    public function bulletinEmploye(BulletinRepository $repository,EmployeRepository $employeRepository, int $id): Response
    {
        $id=$employeRepository->find($id);
        return $this->render('comptable/bulletinEmploye.html.twig',
            ['Bulletin'=>$repository->findBy(['employe'=> $id ])]);
    }


    #[Route('/comptable/historiquePresence/{id}', name: 'comptable_historique_presence')]
    public function historiquePresence(PresenceRepository $repository, int $id): Response
    {
        $id= $this->getUser()->getId();
        return $this->render('comptable/historiquePresence.html.twig',
            ['Presence'=>$repository->findBy(['employe'=> $id ])]);
    }
    #[Route('/comptable/calculPaie', name: 'calculPaie')]
    public function calculPaie(BulletinRepository $bulletinRep,PosteRepository $posteRep,EmployeRepository $employeRep): Response
    {
        $em = $employeRep->findAll();
        $n = count($em);
        for($i=0;$i<$n;$i++)
        {
           $id = $em[$i]->getId();
           $dateRecrutement = $em[$i]->getDateRecrutement();
           $salaire = $em[$i]->getSalaireDeBase();
           $montant_h_s = $em[$i]->getPoste()->getMontantHeureSupp();
           $salaire_heure = $em[$i]->getPoste()->getSalaireParHeure();
           $bulletin = $bulletinRep->findBy(['employe'=> $id ]);
           $ths = $bulletin[$i]->getTotalHeureSupp();
           $tha = $bulletin[$i]->getTotalHeureAbs();
           $bulletin[$i]->setDate(new \DateTime('now'));
           $diff = strtotime($bulletin[$i]->getDate()->format('Y-m-d')) -strtotime($dateRecrutement->format('Y-m-d'));
           $diff = $diff / 86400;
           $anciennete = intdiv ($diff,365);
           $iep=($anciennete/100) * $salaire;
           $bulletin[$i]->setIEP($iep);
           $allocF = ($em[$i]->getNombreEnfant())*600;
           $bulletin[$i]->setAllocationFamiliale($allocF);
           $t=$salaire+$iep+$allocF+($montant_h_s*$ths)-($salaire_heure*$tha);
           $bulletin[$i]->setTotal($t);
           $this->getDoctrine()->getManager()->persist($bulletin[$i]);
           $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('comptable/calculPaie.html.twig');
    }
}
