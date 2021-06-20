<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Filiale;
use App\Entity\Poste;
use App\Entity\Rh;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/createUser', name: 'createUser')]
    public function CreateUser(UserPasswordEncoderInterface $encoder): Response
    {
        $post= new Poste();
        $post->setMontantHeureSupp(0);
        $post->setNbHeureJour(0);
        $post->setNbJourSemaine(0);
        $post->setSalaireParHeure(0);
        $post->setNom('Em');
        $this->getDoctrine()->getManager()->persist($post);
        $this->getDoctrine()->getManager()->flush();

        $filiale= new Filiale();
        $filiale->setAdresse('');
        $filiale->setNomFiliale('Catring');
        $filiale->setType('S');
        $this->getDoctrine()->getManager()->persist($filiale);
        $this->getDoctrine()->getManager()->flush();

        $user= new Rh();
        $user->setRoles(['ROLE_RH']);
        $user->setEmail('a@a.a');
        $MotdePasseCrypte= $encoder->encodePassword($user, 'password');
        $user->setPassword($MotdePasseCrypte);
        $user->setFiliale($filiale);
        $user->setPoste($post);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return new Response('');
    }

}
