<?php

namespace App\Controller;

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
}
