<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RhController extends AbstractController
{
    #[Route('/rh', name: 'rh')]
    public function index(): Response
    {
        return $this->render('rh/rh.html.twig', [
            'controller_name' => 'RhController',
        ]);
    }
}
