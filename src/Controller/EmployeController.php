<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'employe')]
    public function index(): Response
    {
        return $this->render('employe/employe.html.twig', [
            'controller_name' => 'EmployeController',
        ]);
    }
}
