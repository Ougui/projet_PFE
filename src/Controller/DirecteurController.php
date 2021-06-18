<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DirecteurController extends AbstractController
{
    #[Route('/directeur', name: 'directeur')]
    public function index(): Response
    {
        dd('je suis directeur');
        return $this->render('directeur/directeur.html.twig', [
            'controller_name' => 'DirecteurController',
        ]);
    }
}
