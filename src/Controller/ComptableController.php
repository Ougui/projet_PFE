<?php

namespace App\Controller;
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
}
