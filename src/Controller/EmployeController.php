<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Presence;
use App\Entity\Probleme;
use App\Repository\PresenceRepository;
use App\Repository\BulletinRepository;
use App\Repository\ComptableRepository;
use App\Repository\DirecteurGeneralRepository;
use App\Repository\DirecteurRepository;
use App\Repository\EmployeRepository;
use App\Repository\RhRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            return $this->render('employe/mdpChange.html.twig');
        }
        return $this->render('employe/modifierMdp.html.twig',['formila'=>$form->createView()]);
    }
    #[Route('/presences', name: 'employe_presences')]
    public function presences(EmployeRepository $employeRepository): Response
    {
        $employes=$employeRepository->findAll();
        //$dateInit=new \DateTimeImmutable('-90 day');
        foreach ($employes as $employe)
        {
           // $now=new \DateTimeImmutable('-'.(($employe->getPoste()->getNbJourSemaine())*12).' day');
            $now=new \DateTimeImmutable('-90 day');
            $num=0;
            for ($i=0;$i<($employe->getPoste()->getNbJourSemaine())*12;$i++)
            {
                if ($now->format('l')=='Friday')
                {

                    $now=$now->add(new DateInterval('PT86400S'));
                }
                elseif ($now->format('l')=='Thursday')
                {
                    $now=$now->add(new DateInterval('PT86400S'));
                    $now=$now->add(new DateInterval('PT86400S'));
                }
                else
                {
                    $now=$now->add(new DateInterval('PT86400S'));
                }


                $randPresance=mt_rand(0,100)/ 100;

                if($randPresance>0.08)
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
            echo $employe->getId().'presence '.$num.'='.(($employe->getPoste()->getNbJourSemaine())*12).'<br>';
        }
        return new Response('succes');
    }
    #[Route('/employe/fichePaie/{id_bulletin}', name: 'employe_fiche_paie')]
    public function fichePaie(int $id_bulletin, BulletinRepository $bulletinRepository): Response
    {
        $bulletin = $bulletinRepository->find($id_bulletin);
        $employe = $bulletin->getEmploye();
        $date_recrutement = $employe->getDateRecrutement()->format('Y-m-d');
        $poste = $employe->getPoste();
        $salaireParHeure = ($poste->getSalaireDeBase()/($poste->getNbJourSemaine()*4*$poste->getNbHeureJour()));
        $montantHeureSupp = $bulletin->getTotalHeureSupp()*$salaireParHeure;
        $montantHeureAbs = $bulletin->getTotalHeureAbs()*$salaireParHeure;
        return $this->render('employe/fiche_de_paie.html.twig',
            ['Poste'=>$poste,'Employe'=>$employe,'Bulletin'=>$bulletin,'dateRecrutement'=>$date_recrutement,
                'salaireParHeure'=>$salaireParHeure,'montantHeureSupp'=>$montantHeureSupp,
                'montantHeureAbs'=>$montantHeureAbs]);
    }

    #[Route('/employe/probleme/{id}', name: 'employe_probleme')]
    public function probleme(Request $request,int $id
        ,EmployeRepository $employeRepository): Response
    {
        $id= $this->getUser()->getId();
        $employe=$employeRepository->find($id);
        $form=$this->createFormBuilder()
            ->add('Description',TextareaType::class)
            ->add('Signaler',SubmitType::class)
            ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $data = $form->getData();
            $probleme = new Probleme();
            $probleme->setEmploye($employe);
            $probleme->setDescription($data['Description']);
            $this->getDoctrine()->getManager()->persist($probleme);
            $this->getDoctrine()->getManager()->flush();
            return $this->render('employe/probleme_envoye.html.twig');
        }
        return $this->render('employe/probleme.html.twig',['formila'=>$form->createView()]);
    }
}

