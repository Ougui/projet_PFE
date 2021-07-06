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
use Monolog\DateTimeImmutable;

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

        $post->setNbHeureJour(8);
        $post->setNbJourSemaine(5);
        $post->setSalaireDeBase(70000);
        $post->setNom('Informaticien');
        $this->getDoctrine()->getManager()->persist($post);
        $this->getDoctrine()->getManager()->flush();

        $filiale= new Filiale();
        $filiale->setAdresse('Alger, rue didouche mourad');
        $filiale->setNomFiliale('Direction Generale');
        $filiale->setType('D');
        $this->getDoctrine()->getManager()->persist($filiale);
        $this->getDoctrine()->getManager()->flush();

        $user= new Employe();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail('bundles@gmail.com');
        $MotdePasseCrypte= $encoder->encodePassword($user, 'mdp');
        $user->setPassword($MotdePasseCrypte);
        $user->setDateNaissance(new DateTimeImmutable('now'));
        $user->setDateRecrutement(new DateTimeImmutable('now'));
        $user->setFiliale($filiale);
        $user->setPoste($post);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return new Response('');
    }

}
