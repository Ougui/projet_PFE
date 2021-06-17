<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DirecteurGeneralController extends AbstractController
{
    #[Route('/directeur/general', name: 'directeur_general')]
    public function index(): Response
    {
        return $this->render('directeur_general/directeur_general.html.twig', [
            'controller_name' => 'DirecteurGeneralController',
        ]);
    }
}
