<?php

namespace App\Controller;


use App\Entity\Comptable;
use App\Entity\Directeur;
use App\Entity\DirecteurGeneral;
use App\Entity\Employe;
use App\Entity\Filiale;
use App\Entity\Poste;
use App\Entity\Rh;
use App\Entity\User;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function home(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->redirectToRoute('app_login');
    }
    #[Route('/', name: 'site')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->redirectToRoute('app_login');
    }

    #[Route('/createUser', name: 'createUser')]
    public function CreateUser(UserPasswordEncoderInterface $encoder): Response
    {
        $post= new Poste();
        $post->setNbHeureJour(0);
        $post->setNbJourSemaine(0);
        $post->setSalaireDeBase(0);
        $post->setNom('Em');
        $this->getDoctrine()->getManager()->persist($post);
        $this->getDoctrine()->getManager()->flush();

        $filiale= new Filiale();
        $filiale->setAdresse('');
        $filiale->setNomFiliale('Catring');
        $filiale->setType('S');
        $this->getDoctrine()->getManager()->persist($filiale);
        $this->getDoctrine()->getManager()->flush();

        $user= new Employe();
        $user->setRoles(['ROLE_RH']);
        $user->setEmail('r@r.r');
        $MotdePasseCrypte= $encoder->encodePassword($user, 'password');
        $user->setPassword($MotdePasseCrypte);
        $user->setFiliale($filiale);
        $user->setPoste($post);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return new Response('');
    }

}
