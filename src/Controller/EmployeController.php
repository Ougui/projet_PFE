<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Repository\ClientRepository;
use App\Repository\EmployeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'employe')]
    public function index(): Response
    {
        dd('je sui un employe');
        return $this->render('employe/employe.html.twig', [
            'controller_name' => 'EmployeController',
        ]);
    }

     #[Route('/afficherEmploye/{id}',name:'anis')]

    public function display(UserRepository $userRepository, int $id, UserPasswordEncoderInterface $encoder ): Response
    {
      $UnEmploye = $userRepository->find($id);
      if (strlen ($UnEmploye->getPassword()) < 20)
      {
          $MotdePasseCrypte= $encoder->encodePassword($UnEmploye, $UnEmploye->getPassword());
          $UnEmploye->setPassword($MotdePasseCrypte);

          $this->getDoctrine()->getManager()->persist($UnEmploye);
          $this->getDoctrine()->getManager()->flush();
      }
        return $this->redirectToRoute('app_login') ;

    }

}

