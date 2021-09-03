<?php

namespace App\Controller\front_end;

use App\Entity\Societe;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SocieteController extends AbstractController
{
    /**
     * @Route("societe", name="afficher_tout_societe")
     */
    public function afficherToutSociete(Request $request, PaginatorInterface $paginator): Response
    {
        $societes = $this->getDoctrine()->getRepository(Societe::class)->findAll();

        return $this->render('front_end/societe/afficher_tout.html.twig', [
            'totalSocietes' => count($societes),
            'societes' => $paginator->paginate(
                $societes,
                $request->query->getInt('page', 1), 6
            ),
        ]);
    }

    /**
     * @Route("societe/{idSociete}/afficher", name="afficher_societe")
     */
    public function afficherSociete($idSociete): Response
    {
        return $this->render('front_end/societe/afficher.html.twig', [
            'societe' => $this->getDoctrine()->getRepository(Societe::class)->find($idSociete),
        ]);
    }

    /**
     * @Route("societe/recherche")
     */
    public function rechercheSociete(Request $request): Response
    {
        $recherche = $request->get('valeur-recherche');
        $societes = $this->getDoctrine()->getRepository(Societe::class)->findSocieteByName($recherche);

        if ($societes) {
            $jsonContent = null;
            foreach ($societes as $key => $societe) {
                $jsonContent[$key]['id'] = $societe->getId();
                $jsonContent[$key]['idPhoto'] = $societe->getIdPhoto();
                $jsonContent[$key]['nom'] = $societe->getNom();
                $jsonContent[$key]['adresse'] = $societe->getAdresse();
            }
            return new Response(json_encode($jsonContent));
        }
        return new Response(null);
    }
}
