<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Competence;
use App\Entity\Education;
use App\Entity\ExperienceDeTravail;
use App\Entity\Utilisateur;
use App\Form\CandidatType;
use App\Repository\ExperienceDeTravailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CandidatController extends AbstractController
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
     * @Route("/candidat", name="afficherToutCandidat")
     */
    public function afficherToutCandidat()
    {
        return $this->render('backEnd/candidat/afficherToutCandidat.html.twig', [
            'candidats' => $this->getDoctrine()->getRepository(Candidat::class)->findAll(),
        ]);
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
            $filename= md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $candidat->setIdPhoto($uploads_directory."/".$filename);

            $utilisateurManager = $this->getDoctrine()->getManager();
            $utilisateurManager->persist($utilisateur);
            $utilisateurManager->flush();

            $candidatManager = $this->getDoctrine()->getManager();
            $candidatManager->persist($candidat);
            $candidatManager->flush();

            return $this->redirectToRoute('afficherUtilisateur');
        }

        return $this->render('frontEnd/utilisateur/candidat/manipulerCandidat.html.twig', [
            'form' => $form->createView(),
            'manipulation' => "Ajouter",
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
            $filename= md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $candidat->setIdPhoto($uploads_directory."/".$filename);

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
}
