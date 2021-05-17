<?php

namespace App\Controller\front_end;

use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CommentaireController extends AbstractController
{
    /**
     * @Route("commentaire/ajouter", name="ajouterCommentaire")
     */
    public function ajouterCommentaire(Request $request): Response
    {
        $idUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' =>
            $request->getSession()->get(Security::LAST_USERNAME)])->getId();

        $commentaire = new Commentaire();
        $publication = $this->getDoctrine()->getRepository(Publication::class)->find($request->get('idPublication'));
        $commentaire
            ->setDescription($request->get('description'))
            ->setPublication($publication)
            ->setUser($this->getDoctrine()->getRepository(User::class)->find($idUser));

        $commentaireRepository = $this->getDoctrine()->getManager();
        $commentaireRepository->persist($commentaire);
        $commentaireRepository->flush();


        $jsonContent['id'] = $commentaire->getId();
        if ($commentaire->getUser()->getCandidat()) {
            $jsonContent['nom'] =
                $commentaire->getUser()->getCandidat()->getPrenom() . " " .
                $commentaire->getUser()->getCandidat()->getNom() . " :";
            $jsonContent['idPhoto'] = $commentaire->getUser()->getCandidat()->getIdPhoto();
        } elseif ($commentaire->getUser()->getSociete()) {
            $jsonContent['nom'] = $commentaire->getUser()->getSociete()->getNom() . " :";
            $jsonContent['idPhoto'] = $commentaire->getUser()->getSociete()->getIdPhoto();
        } else {
            $jsonContent['nom'] = "Admin :";
            $jsonContent['idPhoto'] = "/back-end/images/admin-icon.png";
        }
        $jsonContent['description'] = $commentaire->getDescription();

        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("commentaire/{idCommentaire}/modifier", name="modifierCommentaire")
     */
    public function modifierCommentaire(Request $request, $idCommentaire): Response
    {
        $modifiedCommentDescription = $request->get('modifiedComment');
        $commentaireRepository = $this->getDoctrine()->getManager();
        $commentaire = $commentaireRepository->getRepository(Commentaire::class)->find($idCommentaire);
        if ($modifiedCommentDescription != '') {
            $commentaire->setDescription($modifiedCommentDescription);
        }
        $commentaireRepository->flush();

        $jsonContent['id'] = $commentaire->getId();
        if ($commentaire->getUser()->getCandidat()) {
            $jsonContent['nom'] =
                $commentaire->getUser()->getCandidat()->getPrenom() . " " .
                $commentaire->getUser()->getCandidat()->getNom() . " :";
            $jsonContent['idPhoto'] = $commentaire->getUser()->getCandidat()->getIdPhoto();
        } elseif ($commentaire->getUser()->getSociete()) {
            $jsonContent['nom'] = $commentaire->getUser()->getSociete()->getNom() . " :";
            $jsonContent['idPhoto'] = $commentaire->getUser()->getSociete()->getIdPhoto();
        } else {
            $jsonContent['nom'] = "Admin :";
            $jsonContent['idPhoto'] = "/back-end/images/admin-icon.png";
        }
        $jsonContent['description'] = $commentaire->getDescription();

        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("commentaire/{idCommentaire}/supprimer", name="supprimerCommentaire")
     */
    public function supprimerCommentaire($idCommentaire): Response
    {
        $commentaireManager = $this->getDoctrine()->getManager();
        $commentaire = $commentaireManager->getRepository(Commentaire::class)->find($idCommentaire);
        $commentaireManager->remove($commentaire);
        $commentaireManager->flush();

        return new Response(null);
    }
}
