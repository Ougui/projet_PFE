<?php

namespace App\Controller;
use App\Entity\Bulletin;
use App\Entity\Presence;
use App\Entity\Probleme;
use App\Repository\PosteRepository;
use App\Repository\PresenceRepository;
use App\Repository\BulletinRepository;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use function Symfony\Component\String\s;
use Sasedev\MpdfBundle\Factory\MpdfFactory;




class ComptableController extends AbstractController
{
    #[Route('/comptable/{date}', name: 'comptable')]
    public function espacePersoDG(EmployeRepository $repository,string $date): Response
    {
        $id= $this->getUser()->getId();
        return $this->render('comptable/index.html.twig', [
            'Employe' => $repository->find($id),
            'date'=>$date
        ]);
    }


    #[Route('/comptable/listerEmploye/1', name: 'comptable_lister_employe')]
    public function listerEmploye(EmployeRepository $repository): Response
    {
        return $this->render('comptable/listEmploye.html.twig',['Employes'=>$repository->findAll(),
            'rochdi'=>(new \DateTime('now'))->format('Y-m-d')]);
    }


    #[Route('/comptable/viewEmploye/{id}', name: 'comptable_profile_employe')]
    public function viewEmploye(EmployeRepository $employeRepository,int $id): Response
    {
        $employe=$employeRepository->find($id);
        return $this->render('comptable/formView.html.twig',['Employe'=>$employeRepository->find($id),
            'date'=>(new \DateTime('now'))->format('Y-m-d')]);
    }


    #[Route('/comptable/viewBulletin/{id}', name: 'comptable_view_bulletin')]
    public function viewBulletin(BulletinRepository $repository,EmployeRepository $employeRepository, int $id): Response
    {
        $id = $this->getUser()->getId();
        return $this->render('comptable/viewBulletin.html.twig',
            ['Bulletin' => $repository->findBy(['employe' => $id]),
                'date'=>(new \DateTime('now'))->format('Y-m-d')]);
    }


    #[Route('/comptable/bulletinEmploye/{id}', name: 'comptable_bulletin_employe')]
    public function bulletinEmploye(BulletinRepository $repository,EmployeRepository $employeRepository, int $id): Response
    {
        $id=$employeRepository->find($id);
        return $this->render('comptable/bulletinEmploye.html.twig',
            ['Bulletin'=>$repository->findBy(['employe'=> $id ]),'Employe'=>$employeRepository->find($id),
                'date'=>(new \DateTime('now'))->format('Y-m-d')]);
    }


    #[Route('/comptable/historiquePresence/{id}', name: 'comptable_historique_presence')]
    public function historiquePresence(PresenceRepository $repository, int $id): Response
    {
        $id= $this->getUser()->getId();
        return $this->render('comptable/historiquePresence.html.twig',
            ['Presence'=>$repository->findBy(['employe'=> $id ]),
                'date'=>(new \DateTime('now'))->format('Y-m-d')]);
    }

    #[Route('/comptable/calculPaie/{datecalcu}', name: 'calculPaie')]
    public function calculPaie(EmployeRepository $employeRep,PresenceRepository $presenceRep,BulletinRepository $bulletinRep,string $datecalcu): Response
    {  $bul=$bulletinRep->findDateB($datecalcu);
        if ($bul ==null) {
            $em = $employeRep->findAll();
            $n = count($em);
            for ($i = 0; $i < $n; $i++) {
                $id = $em[$i]->getId();
                $dateRecrutement = $em[$i]->getDateRecrutement();
                $salaire = $em[$i]->getPoste()->getSalaireDeBase();
                $heure_jour = $em[$i]->getPoste()->getNbHeureJour();
                $jour_semaine = $em[$i]->getPoste()->getNbJourSemaine();
                $heure_mois = $heure_jour * $jour_semaine * 4;
                $salaire_heure = $salaire / $heure_mois;
                $bulletin = new Bulletin();
                $bulletin->setEmploye($em[$i]);
                $pr = $presenceRep->findDate($datecalcu);
                $p = count($pr);
                $jour = 0;
                $travail = 0;
                for ($j = 0; $j < $p; $j++) {
                    $x = $pr[$j];
                    if (($x['employe_id']) == $id) {
                        $tr = strtotime($x['heure_out']) - strtotime($x['heure_in']);
                        $travail = $travail + intdiv($tr, 3600);
                        $jour = $jour + 1;
                    }
                }
                $bulletin->setDate(new \DateTime($datecalcu));
                $diff = strtotime($bulletin->getDate()->format('Y-m-d')) - strtotime($dateRecrutement->format('Y-m-d'));
                $diff = $diff / 86400;
                $anciennete = intdiv($diff, 365);
                $iep = ($anciennete * $salaire) / 100;
                $bulletin->setIEP($iep);
                $allocF = ($em[$i]->getNombreEnfant()) * 600;
                $bulletin->setAllocationFamiliale($allocF);
                $panier = $jour * 400;
                $cotisations= (9*$salaire)/100;
                $bulletin->setPanier($panier);
                $bulletin->setCotisations($cotisations);
                $t = $iep + $allocF + $panier - $cotisations + ($salaire_heure * $travail);
                if ($t>1440000/12)
                {
                    $impots = (35*$t)/100;
                }
                else if ($t>360001/12)
                {
                    $impots = (30*$t)/100;
                }
                else if ($t>120001/12)
                {
                    $impots = (20*$t)/100;
                }
                else
                {
                    $impots = 0;
                }
                $bulletin->setImpots($impots);
                $total = $t - $impots;
                $bulletin->setTotal($total);
                if ($travail < $heure_mois) {
                    $bulletin->setTotalHeureAbs($heure_mois - $travail);
                    $bulletin->setTotalHeureSupp(0);
                } else {
                    $bulletin->setTotalHeureSupp($travail - $heure_mois);
                    $bulletin->setTotalHeureAbs(0);
                }
                $this->getDoctrine()->getManager()->persist($bulletin);
                $this->getDoctrine()->getManager()->flush();
            }
            return $this->render('comptable/calculPaie.html.twig', ['rochdi' => $datecalcu]);
        }
        else return $this->render('comptable/calculEffectue.html.twig', ['rochdi' => $datecalcu]);
    }
    #[Route('/comptable/modifierMdp/{id}', name: 'comptable_modifier_mdp')]
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
            return $this->render('comptable/mdpChange.html.twig',
                ['date'=>(new \DateTime('now'))->format('Y-m-d')]);
        }
        return $this->render('comptable/modifierMdp.html.twig',['formila'=>$form->createView(),
            'date'=>(new \DateTime('now'))->format('Y-m-d')]);
    }

    #[Route('/pointage/{id}/{heureIn}/{heureOut}/{em}', name: 'pointage')]
    public function pointage(Request $request,UserPasswordEncoderInterface $encoder,int $id,int $em,\DateTime $heureIn,
                             \DateTime $heureOut,
        EmployeRepository $employeRepository, PresenceRepository $presenceRepository): Response
    {
        $employe=$employeRepository->find($em);
        $presence= new Presence();
        $presence->setEmploye($employe);
        $presence->setHeureIn($heureIn);
        $presence->setHeureOut($heureOut);
        $presence->setDate(new \DateTime('now'));
        $this->getDoctrine()->getManager()->persist($presence);
        $this->getDoctrine()->getManager()->flush();
        return new Response('');
    }

   #[Route('/recupEmployes', name: 'recupEmployes')]
    public function recupEmployes(Request $request, EmployeRepository $employeRepository): Response
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $employe=$employeRepository->findAll();
        $employes = [];
        foreach ($employe as $em)
        {
            array_push($employes,$em->getId());
        }

        $jsonContent = $serializer->serialize($employes, 'json');
        return $this->json($jsonContent);
    }

    #[Route('/comptable/paieOublie/1', name: 'comptable_paie_oublie')]
    public function paieOublie(Request $request,EmployeRepository $employeRepository): Response
    {
        $form=$this->createFormBuilder()
            ->add('Date',DateType::class, ['input'  => 'datetime_immutable','widget' => 'single_text'])
            ->add('Confirmer',SubmitType::class)
            ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $data = $form->getData();
            $dat=$data['Date']->format('Y-m-d');
            return $this->render('comptable/confirmerOublie.html.twig',['date'=>$dat]);
        }
        return $this->render('comptable/paieOublie.html.twig',['formila'=>$form->createView(),
            'date'=>(new \DateTime('now'))->format('Y-m-d')]);

    }

   #[Route('/comptable/fichePaie/{id_bulletin}', name: 'comptable_fiche_paie')]
   public function fichePaie(int $id_bulletin, BulletinRepository $bulletinRepository): Response
   {
       $bulletin = $bulletinRepository->find($id_bulletin);
       $employe = $bulletin->getEmploye();
       $date_recrutement = $employe->getDateRecrutement()->format('Y-m-d');
       $poste = $employe->getPoste();
       $salaireParHeure = ($poste->getSalaireDeBase()/($poste->getNbJourSemaine()*4*$poste->getNbHeureJour()));
       $montantHeureSupp = $bulletin->getTotalHeureSupp()*$salaireParHeure;
       $montantHeureAbs = $bulletin->getTotalHeureAbs()*$salaireParHeure;
       return $this->render('comptable/fiche_de_paie.html.twig',
           ['Poste'=>$poste,'Employe'=>$employe,'Bulletin'=>$bulletin,'dateRecrutement'=>$date_recrutement,
               'salaireParHeure'=>$salaireParHeure,'montantHeureSupp'=>$montantHeureSupp,
               'montantHeureAbs'=>$montantHeureAbs]);
   }

    #[Route('/comptable/probleme/{id}', name: 'comptable_probleme')]
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
            return $this->render('comptable/probleme_envoye.html.twig',['date'=>(new \DateTime('now'))->format('Y-m-d')]);
        }
        return $this->render('comptable/probleme.html.twig',['formila'=>$form->createView(),'date'=>(new \DateTime('now'))->format('Y-m-d')]);
    }
    #[Route('/comptable/confirmer/1', name: 'confirmer')]
    public function confirmer(Request $request,EmployeRepository $employeRepository): Response
    {
        return $this->render('comptable/confirmer.html.twig',[
            'date'=>(new \DateTime('now'))->format('Y-m-d')]);
    }
}
