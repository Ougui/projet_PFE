<?php

namespace App\Controller\Admin;
use App\Entity\Bulletin;
use App\Entity\Comptable;
use App\Entity\Directeur;
use App\Entity\DirecteurGeneral;
use App\Entity\Employe;
use App\Entity\Filiale;
use App\Entity\Poste;
use App\Entity\Presence;
use App\Entity\Rh;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('PFE');
    }

    public function configureMenuItems(): iterable
    {
        //yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        //yield MenuItem::linkToCrud('Bulletin', 'fas fa-list', Bulletin::class);
        yield MenuItem::linkToCrud('Comptable', 'fas fa-list', Comptable::class);
        yield MenuItem::linkToCrud('DirecteurGeneral', 'fas fa-list', DirecteurGeneral::class);
        yield MenuItem::linkToCrud('Employe', 'fas fa-list', Employe::class);
        yield MenuItem::linkToCrud('Filiale', 'fas fa-list', Filiale::class);
        yield MenuItem::linkToCrud('Poste', 'fas fa-list', Poste::class);
        //yield MenuItem::linkToCrud('Presence', 'fas fa-list', Presence::class);
        yield MenuItem::linkToCrud('Rh', 'fas fa-list', Rh::class);
        yield MenuItem::linkToCrud('Directeur', 'fas fa-list', Directeur::class);
        yield MenuItem::linkToCrud('User', 'fas fa-list', User::class);
        

    }
}
