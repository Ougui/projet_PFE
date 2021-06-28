<?php

namespace App\Controller;
use App\Entity\Bulletin;
use App\Entity\Presence;
use App\Repository\PosteRepository;
use App\Repository\PresenceRepository;
use App\Repository\BulletinRepository;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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


    #[Route('/comptable/listerEmploye', name: 'comptable_lister_employe')]

    public function listerEmploye(EmployeRepository $repository): Response
    {   // $filiale= $this->getUser()->getFiliale();
        dd('anis');
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
    public function calculPaie(BulletinRepository $bulletinRep,PosteRepository $posteRep,
                               EmployeRepository $employeRep,PresenceRepository $presenceRep,string $datecalcu): Response
    {
        $em = $employeRep->findAll();
        $n = count($em);
        for($i=0;$i<$n;$i++) {
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
            $travail = 0;
            for ($j = 0; $j < $p; $j++) {
                $x = $pr[$j];
                if (($x['employe_id']) == $id) {
                    $tr = strtotime($x['heure_out']) - strtotime($x['heure_in']);
                    $travail = $travail + intdiv($tr, 3600);
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
            $t = $iep + $allocF + ($salaire_heure * $travail);
            $bulletin->setTotal($t);
            if ($travail < $heure_mois) {
                $bulletin->setTotalHeureAbs($heure_mois - $travail);
                $bulletin->setTotalHeureSupp(0);
            }
            else
            {
                $bulletin->setTotalHeureSupp($travail - $heure_mois);
                $bulletin->setTotalHeureAbs(0);
            }
            $this->getDoctrine()->getManager()->persist($bulletin);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('comptable/calculPaie.html.twig',['rochdi'=>$datecalcu]);
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
}
