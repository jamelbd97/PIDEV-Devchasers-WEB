<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/accueil", name="accueil")
     */
    public function index(): Response
    {
        return $this->render('/frontEnd/accueil.html.twig', [
            'session' => $this->session->get("utilisateur"),
        ]);
    }

    /**
     * @Route("/accueilBackEnd", name="accueilBackEnd")
     */
    public function indexBackEnd(): Response
    {
        return $this->render('/backEnd/accueil.html.twig', [
        ]);
    }

    /**
     * @Route("/connexion", name="connexion")
     */
    public function connexion(Request $request)
    {
        $utilisateurConnexion = new Utilisateur();

        $form = $this->createForm(UtilisateurType::class, $utilisateurConnexion)
            ->add('Connexion', SubmitType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $utilisateurs = $manager->getRepository(Utilisateur::class)->findAll();

            foreach ($utilisateurs as $utilisateur) {
                $email = $utilisateur->getEmail();
                if ($utilisateurConnexion->getEmail() == $email) {
                    $motDePasse =  $utilisateur->getMotDePasse();
                    if ($utilisateurConnexion->getMotDePasse() == $motDePasse)
                    {
                        $utilisateurConnexion = $manager->getRepository(Utilisateur::class)->connexion($email,$motDePasse);
                        $this->session->set("utilisateur", [
                            'idUtilisateur' => $utilisateurConnexion->getId() ,
                            'emailUtilisateur' => $utilisateurConnexion->getEmail() ,
                        ]);
                        return $this->redirectToRoute("accueil");
                    }
                }
            }
        }

        return $this->render('/frontEnd/connexion/connexion.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/deconnexion", name="deconnexion")
     */
    public function deconnexion()
    {
        $this->session->set("utilisateur",null);
        return $this->redirectToRoute("accueil");
    }
}