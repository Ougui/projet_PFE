<?php

namespace App\Controller;
use App\Repository\PresenceRepository;
use App\Repository\BulletinRepository;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
            ['Bulletin'=>$repository->findBy(['employe'=> $id ])]);
    }


    #[Route('/directeur/historiquePresence/{id}', name: 'directeur_historique_presence')]
    public function historiquePresence(PresenceRepository $repository, int $id): Response
    {
        $id= $this->getUser()->getId();
        return $this->render('directeur/historiquePresence.html.twig',
            ['Presence'=>$repository->findBy(['employe'=> $id ])]);
    }
}
