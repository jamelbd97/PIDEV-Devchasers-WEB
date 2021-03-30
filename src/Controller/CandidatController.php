<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Competence;
use App\Entity\Education;
use App\Entity\ExperienceDeTravail;
use App\Entity\Utilisateur;
use App\Form\CandidatType;
use App\Form\UtilisateurType;
use App\Repository\ExperienceDeTravailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class CandidatController extends Controller
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/afficherProfil", name="afficherProfil")
     */
    public function afficherProfil()
    {
        $idCandidat = $this->session->get("utilisateur")["idCandidat"];
        return $this->render('frontEnd/utilisateur/candidat/afficherProfil.html.twig', [
            'candidat' => $this->getDoctrine()->getRepository(Candidat::class)->find($idCandidat),
            'educations' => $this->getDoctrine()->getRepository(Education::class)->findOneBySomeField($idCandidat),
            'workexps' => $this->getDoctrine()->getRepository(ExperienceDeTravail::class)->findOneBySomeField($idCandidat),
            'competences' => $this->getDoctrine()->getRepository(Competence::class)->findOneBySomeField($idCandidat)
        ]);
    }

    /**
     * @Route("/afficherCandidatBackEnd/{idCandidat}", name="afficherCandidatBackEnd")
     */
    public function afficherCandidatBackEnd($idCandidat)
    {
        return $this->render('backend/candidat/afficherCandidat.html.twig', [
            'candidat' => $this->getDoctrine()->getRepository(Candidat::class)->find($idCandidat),
            'educations' => $this->getDoctrine()->getRepository(Education::class)->findOneBySomeField($idCandidat),
            'workexps' => $this->getDoctrine()->getRepository(ExperienceDeTravail::class)->findOneBySomeField($idCandidat),
            'competences' => $this->getDoctrine()->getRepository(Competence::class)->findOneBySomeField($idCandidat)
        ]);
    }

    /**
     * @Route("/candidat", name="afficherToutCandidat")
     */
    public function afficherToutCandidat(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cand = $em->getRepository(Candidat::class)->findAll();
        $paginator = $this->get('knp_paginator');
        $cand = $paginator->paginate(
            $cand,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 2)
        );
        return $this->render('backEnd/candidat/afficherToutCandidat.html.twig', [
            'candidats' =>  $cand,
        ]);
    }

    /**
     * @Route("/afficherCandidatsRecherche", name="afficherCandidatsRecherche")
     * @throws \Exception
     */
    public function afficherCandidatsRecherche(Request $request)
    {
        $recherche = $request->get('recherche');

        $candidats = $this->getDoctrine()->getRepository(Candidat::class)->findStartingWith($recherche);

        $i = 0;
        $jsonContent = null;
        if ($candidats != null) {
            foreach ($candidats as $candidat) {
                $jsonContent[$i]["id"] = $candidat->getId();
                $jsonContent[$i]["nom"] = $candidat->getNom();
                $jsonContent[$i]["prenom"] = $candidat->getPrenom();
                $jsonContent[$i]["dateNaiss"] = $candidat->getDateNaissance()->format('d-m-Y');
                $jsonContent[$i]["sexe"] = $candidat->getSexe();
                $jsonContent[$i]["tel"] = $candidat->getTel();
                $i++;
            }
            return new Response(json_encode($jsonContent));
        } else {
            return new Response(null);
        }
    }

    /**
     * @Route("/candidat/ajouter/email={email}/password={motDePasse}", name="ajouterCandidat")
     */
    public function ajouterCandidat(Request $request, $email, $motDePasse)
    {
        $utilisateur = new Utilisateur();
        $candidat = new Candidat();

        $form = $this->createForm(CandidatType::class, $candidat)
            ->add('submit', SubmitType::class)
            ->handleRequest($request);

        if ($form->isSubmitted()) {

            $utilisateur->setEmail($email)
                ->setMotDePasse($motDePasse)
                ->setTypeUtilisateur(1);

            $candidat = $form->getData()
                ->setUtilisateur($utilisateur);

            $file = $request->files->get('candidat')['idPhoto'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $candidat->setIdPhoto($filename);

            $utilisateurManager = $this->getDoctrine()->getManager();
            $utilisateurManager->persist($utilisateur);
            $utilisateurManager->flush();

            $candidatManager = $this->getDoctrine()->getManager();
            $candidatManager->persist($candidat);
            $candidatManager->flush();

            return $this->redirectToRoute('afficherToutPublication');
        }

        return $this->render('_inscription/inscrireCandidat.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/candidat={idCandidat}/modifier", name="modifierCandidat")
     */
    public function modifierCandidat(Request $request, $idCandidat)
    {
        $manager = $this->getDoctrine()->getManager();
        $candidat = $manager->getRepository(Candidat::class)->find($idCandidat);

        $form = $this->createForm(CandidatType::class, $candidat);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $file = $request->files->get('candidat')['idPhoto'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $candidat->setIdPhoto($filename);

            $candidat = $form->getData();
            $manager->persist($candidat);
            $manager->flush();

            return $this->redirectToRoute('afficherProfil');
        }

        return $this->render('frontEnd/utilisateur/candidat/modifierprofil.html.twig', [
            'candidat' => $candidat,
            'form' => $form->createView(),
            'manipulation' => "Modifier"
        ]);
    }

    /**
     * @Route("/candidat={idUtilisateur}/modifierEmail", name="modifierEmail")
     */
    public function modifierEmail(Request $request, $idUtilisateur)
    {
        $manager = $this->getDoctrine()->getManager();
        $utilisateur = $manager->getRepository(Utilisateur::class)->find($idUtilisateur);

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $candidat = $form->getData();
            $manager->persist($candidat);
            $manager->flush();

            return $this->redirectToRoute('afficherProfil');
        }

        return $this->render('frontEnd/utilisateur/candidat/modifierEmail.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
            'manipulation' => "Modifier"
        ]);
    }

    /**
     * @Route("/candidat={idUtilisateur}/modifierMotDePasse", name="modifierMotDePasse")
     */
    public function modifierMotDePasse(Request $request, $idUtilisateur)
    {
        $manager = $this->getDoctrine()->getManager();
        $utilisateur = $manager->getRepository(Utilisateur::class)->find($idUtilisateur);

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $candidat = $form->getData();
            $manager->persist($candidat);
            $manager->flush();

            return $this->redirectToRoute('afficherProfil');
        }

        return $this->render('frontEnd/utilisateur/candidat/modifierMotDePasse.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
            'manipulation' => "Modifier"
        ]);
    }
}