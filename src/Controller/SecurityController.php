<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $arrayRoles=$this->getUser()->getRoles();
            $employe=false;
            $rh=false;
            $comptable=false;
            $directeur=false;
            $directeurGenral=false;
            $admin=false;
            foreach ($arrayRoles as $role ) {
                if ($role == 'ROLE_EMPLOYE')
                {$employe=true;}
                if ($role == 'ROLE_COMPTABLE')
                {$comptable=true;}
                if ($role == 'ROLE_DIRECTEUR')
                {$directeur=true;}
                if ($role == 'ROLE_DIRECTEUR_GENERAL')
                {$directeurGenral=true;}
                if ($role == 'ROLE_RH')
                {$rh=true;}
                if ($role == 'ROLE_ADMIN'){
                  $admin=true;
                }
            }
            if($comptable) {
                return $this->redirectToRoute('comptable');
            }
            if($directeur) {
                return $this->redirectToRoute('directeur');
            }
            if($directeur and $directeurGenral) {
                return $this->redirectToRoute('directeur_general');
            }
            if($rh) {
                return $this->redirectToRoute('rh');
            }
            if($employe) {
                return $this->redirectToRoute('employe');
            }
            if($admin) {
                return $this->redirectToRoute('admin');
            }
           return $this->redirectToRoute('target_path');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
