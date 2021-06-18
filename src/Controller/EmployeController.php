<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Repository\ClientRepository;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'employe')]
    public function index(): Response
    {
        return $this->render('employe/employe.html.twig', [
            'controller_name' => 'EmployeController',
        ]);
    }

     #[Route('/afficherEmploye/{id}',name:'app_afficherEmploye')]

    public function display(EmployeRepository $rochdi, int $id, UserPasswordEncoderInterface $encoder ): Response
    {
      $UnEmploye = $rochdi->find($id);
      if (strlen ($UnEmploye->getPassword()) < 20)
      {
          $MotdePasseCrypte= $encoder->encodePassword($UnEmploye, $UnEmploye->getPassword());
          $UnEmploye->setPassword($MotdePasseCrypte);
          $this->getDoctrine()->getManager()->persist($UnEmploye);
          $this->getDoctrine()->getManager()->flush();
      }
        return new Response('') ;
    }

}

