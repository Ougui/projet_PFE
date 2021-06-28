<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Presence;
use App\Repository\PresenceRepository;
use App\Repository\BulletinRepository;
use App\Repository\ComptableRepository;
use App\Repository\DirecteurGeneralRepository;
use App\Repository\DirecteurRepository;
use App\Repository\EmployeRepository;
use App\Repository\RhRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use DateInterval;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'employe')]
    public function espacePerso(EmployeRepository $repository): Response
    {
        $id= $this->getUser()->getId();
        return $this->render('employe/employe.html.twig', [
            'Employe' => $repository->find($id) ]);
    }


     #[Route('/afficherEmploye/{id}',name:'anis')]

    public function display(RhRepository $rhRepository,
                            EmployeRepository $employeRepository,
                            ComptableRepository $comptableRepository,
                            DirecteurRepository $directeurRepository,
                            DirecteurGeneralRepository $directeurGeneralRepository,
                            int $id,
                            UserPasswordEncoderInterface $encoder
     ): Response
    {
      $rh= $rhRepository->find($id);
      $employe=$employeRepository->find($id);
      $comptable=$comptableRepository->find($id);
      $directeurGeneral=$directeurGeneralRepository->find($id);
      $directeur=$directeurRepository->find($id);
      if ($rh ){
          $UnEmploye=$rh;
      }
      if($employe){
          $UnEmploye=$employe;
      }
      if($comptable){
          $UnEmploye=$comptable;
      }
      if($directeurGeneral){
          $UnEmploye=$directeurGeneral;
      }
      if($directeur){
          $UnEmploye=$directeur;
      }
     if($UnEmploye != null)
     {
      if (strlen ($UnEmploye->getPassword()) < 20)
      {
          $MotdePasseCrypte= $encoder->encodePassword($UnEmploye, $UnEmploye->getPassword());
          $UnEmploye->setPassword($MotdePasseCrypte);
          $this->getDoctrine()->getManager()->persist($UnEmploye);
          $this->getDoctrine()->getManager()->flush();
      }
        return $this->redirectToRoute('app_login') ;
     }
     return new Response('employé existe pas');
    }


    #[Route('/employe/viewBulletin/{id}', name: 'employe_view_bulletin')]
    public function viewBulletin(BulletinRepository $repository,EmployeRepository $employeRepository, int $id): Response
    {
        $id = $this->getUser()->getId();
        return $this->render('employe/viewBulletin.html.twig',
            ['Bulletin' => $repository->findBy(['employe' => $id])]);
    }


    #[Route('/employe/historiquePresence/{id}', name: 'employe_historique_presence')]
    public function historiquePresence(PresenceRepository $repository, int $id): Response
    {
        $id= $this->getUser()->getId();
        return $this->render('employe/historiquePresence.html.twig',
            ['Presence'=>$repository->findBy(['employe'=> $id ])]);
    }
    #[Route('/employe/modifierMdp/{id}', name: 'employe_modifier_mdp')]
    public function modifierMdp(Request $request,UserPasswordEncoderInterface $encoder,int $id
        ,EmployeRepository $employeRepository): Response
    {
        $id= $this->getUser()->getId();
        $employe=$employeRepository->find($id);
        $form=$this->createFormBuilder()
            ->add('password',PasswordType::class)
            ->add('Confirmer',SubmitType::class)
            ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $data = $form->getData();
            $MotdePasseCrypte= $encoder->encodePassword($employe, $data['password']);
            $employe->setPassword($MotdePasseCrypte);
            $this->getDoctrine()->getManager()->persist($employe);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('employe/modifierMdp.html.twig',['formila'=>$form->createView()]);
    }
    #[Route('/presences', name: 'employe_presences')]
    public function presences(EmployeRepository $employeRepository): Response
    {
        $employes=$employeRepository->findAll();
        $dateInit=new \DateTimeImmutable('-90 day');
        foreach ($employes as $employe)
        {

            $now=$dateInit;
            $num=0;
            for ($i=0;$i<($employe->getPoste()->getNbJourSemaine())*12;$i++)
            {
                if ($now->format('l')=='Friday')
                {
                    $now=$now->add(new DateInterval('PT86400S'));
                }
                $now=$now->add(new DateInterval('PT86400S'));
                $randPresance=mt_rand(0,100)/ 100;

                if($randPresance>0.05)
                {
                    $num++;
                    $presence=new Presence();
                    $presence->setEmploye($employe);
                    $presence->setDate(new \DateTimeImmutable($now->format('Y-m-d')));
                    //$randPresanceHeurIn
                    $randPresanceHeurIn=mt_rand(8,10);
                    $randPresanceMinut=mt_rand(0,59);
                    $now=$now->setTime($randPresanceHeurIn,$randPresanceMinut,0);
                    $presence->setHeureIn(new \DateTime($now->format('Y-m-d H:i')));
                    //$randPresanceHeurIn+$randPresanceHeur
                    $randPresanceHeurOut=mt_rand(17,19);
                    $randPresanceMinut=mt_rand(0,59);
                    $now=$now->setTime($randPresanceHeurOut,$randPresanceMinut,0);
                    $presence->setHeureOut(new \DateTime($now->format('Y-m-d H:i')));
                    $this->getDoctrine()->getManager()->persist($presence);
                    $this->getDoctrine()->getManager()->flush();

                }


            }
            echo $employe->getId().'presane '.$num.'='.(($employe->getPoste()->getNbJourSemaine())*12).'<br>';
        }
        return new Response('aniss');
    }
}

