<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Repository\ComptableRepository;
use App\Repository\DirecteurGeneralRepository;
use App\Repository\DirecteurRepository;
use App\Repository\EmployeRepository;
use App\Repository\RhRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'employe')]
    public function espacePerso(EmployeRepository $repository): Response
    {
        $id= $this->getUser()->getId();
        return $this->render('employe/employe.html.twig', [
            'Employe' => $repository->find($id) ]);
    }

     #[Route('/afficherEmploye/{id}',name:'anis')]

    public function display(RhRepository $rhRepository,
                            EmployeRepository $employeRepository,
                            ComptableRepository $comptableRepository,
                            DirecteurRepository $directeurRepository,
                            DirecteurGeneralRepository $directeurGeneralRepository,
                            int $id,
                            UserPasswordEncoderInterface $encoder
     ): Response
    {
      $rh= $rhRepository->find($id);
      $employe=$employeRepository->find($id);
      $comptable=$comptableRepository->find($id);
      $directeurGeneral=$directeurGeneralRepository->find($id);
      $directeur=$directeurRepository->find($id);
      if ($rh ){
          $UnEmploye=$rh;
      }
      if($employe){
          $UnEmploye=$employe;
      }
      if($comptable){
          $UnEmploye=$comptable;
      }
      if($directeurGeneral){
          $UnEmploye=$directeurGeneral;
      }
      if($directeur){
          $UnEmploye=$directeur;
      }
     if($UnEmploye != null)
     {
      if (strlen ($UnEmploye->getPassword()) < 20)
      {
          $MotdePasseCrypte= $encoder->encodePassword($UnEmploye, $UnEmploye->getPassword());
          $UnEmploye->setPassword($MotdePasseCrypte);
          $this->getDoctrine()->getManager()->persist($UnEmploye);
          $this->getDoctrine()->getManager()->flush();
      }
        return $this->redirectToRoute('app_login') ;
     }
     return new Response('employé existe pas');
    }

}

