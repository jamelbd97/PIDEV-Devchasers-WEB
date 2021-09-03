<?php

namespace App\Controller\back_end_societe;

use App\Entity\OffreDeTravail;
use App\Entity\User;
use App\Form\OffreDeTravailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("espace_societe/")
 */
class OffreDeTravailController extends AbstractController
{
    /**
     * @Route("offre_de_travail")
     */
    public function afficherToutOffreDeTravail(): Response
    {
        return $this->render('back_end_societe/societe/offre_de_travail/afficher_tout.html.twig', [
            'offreDeTravails' => $this->getDoctrine()->getRepository(OffreDeTravail::class)->findAll()
        ]);
    }

    /**
     * @Route("offre_de_travail/{idOffreDeTravail}/afficher")
     */
    public function afficherOffreDeTravail($idOffreDeTravail): Response
    {
        return $this->render('back_end_societe/societe/offre_de_travail/afficher.html.twig', [
            'offreDeTravails' => $this->getDoctrine()->getRepository(OffreDeTravail::class)->find($idOffreDeTravail)
        ]);
    }

    /**
     * @Route("offre_de_travail/recherche")
     * @throws ExceptionInterface
     */
    public function rechercheOffreDeTravail(Request $request, NormalizerInterface $normalizer): Response
    {
        $recherche = $request->get("valeur-recherche");
        $offreDeTravail = $this->getDoctrine()->getRepository(OffreDeTravail::class)->findOneByOffreDeTravailName($recherche);

        $jsonContent = $normalizer->normalize($offreDeTravail, 'json', ['groups' => 'post:read',]);
        $retour = json_encode($jsonContent);
        return new Response($retour);
    }

    /**
     * @Route("offre_de_travail/ajouter")
     */
    public function ajouterOffreDeTravail(Request $request)
    {
        return $this->manipulerOffreDeTravail($request, 'Ajouter', new OffreDeTravail());
    }

    /**
     * @Route("offre_de_travail/{idOffreDeTravail}/modifier")
     */
    public function modifierOffreDeTravail(Request $request, $idOffreDeTravail)
    {
        return $this->manipulerOffreDeTravail($request, 'Modifier',
            $this->getDoctrine()->getRepository(OffreDeTravail::class)->find($idOffreDeTravail));
    }

    public function manipulerOffreDeTravail(Request $request, $manipulation, $offreDeTravail)
    {
        $email = $request->getSession()->get(Security::LAST_USERNAME);
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user) {
            $form = $this->createForm(OffreDeTravailType::class, $offreDeTravail)
                ->add('submit', SubmitType::class)
                ->handleRequest($request);

            if ($form->isSubmitted()) {
                $offreDeTravail = $form->getData();
                $offreDeTravail->setSociete($user->getSociete());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($offreDeTravail);
                $entityManager->flush();

                return $this->render('/espace_societe/offre_de_travail');
            }

            return $this->render('back_end_societe/societe/offre_de_travail/manipuler.html.twig', [
                'offreDeTravail' => $offreDeTravail,
                'form' => $form->createView(),
                'manipulation' => $manipulation,
            ]);
        } else {
            return $this->redirect('/connexion');
        }
    }

    /**
     * @Route("offre_de_travail/{idOffreDeTravail}/supprimer")
     */
    public
    function supprimerOffreDeTravail($idOffreDeTravail): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $offreDeTravail = $this->getDoctrine()->getRepository(OffreDeTravail::class)->find($idOffreDeTravail);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($offreDeTravail);
        $entityManager->flush();

        return $this->render('/espace_societe/offre_de_travail');
    }
}
