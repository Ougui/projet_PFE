<?php

namespace App\Controller;
use App\Repository\PresenceRepository;
use App\Repository\BulletinRepository;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
            ['Bulletin'=>$repository->findBy(['employe'=> $id ])]);
    }
    #[Route('/dg/historiquePresence/{id}', name: 'dg_historique_presence')]
    public function historiquePresence(PresenceRepository $repository, int $id): Response
    {
        $id= $this->getUser()->getId();
        return $this->render('directeur_general/historiquePresence.html.twig',
            ['Presence'=>$repository->findBy(['employe'=> $id ])]);
    }
}
