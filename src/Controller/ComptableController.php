<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComptableController extends AbstractController
{
    #[Route('/comptable', name: 'comptable')]
    public function index(): Response
    {
        dd('je suis comptable');
        return $this->render('comptable/comptable.html.twig', [
            'controller_name' => 'ComptableController',
        ]);
    }
}
