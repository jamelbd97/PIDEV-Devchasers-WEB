<?php

namespace App\Controller\Mobile;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Mission;
use App\Entity\Societe;
use DateTime;

/**
 * @Route("mobile/")
 */
class MissionController extends AbstractController
{
    /**
     * @Route("/mission", name="mission")
     */
    public function index(): Response
    {
        return $this->render('mission/index.html.twig', [
            'controller_name' => 'MissionController',
        ]);
    }

    /**
     * @Route("recuperer_mission")
     * @return Response
     */
    public function recupererMissions(): Response
    {
        $missions = $this->getDoctrine()->getRepository(Mission::class)->findAll();
        $jsonContent = null;
        $i = 0;

        if (!$missions) {
            return new Response(null);
        }

        foreach ($missions as $mission) {
            $jsonContent[$i]['id'] = $mission->getId();
            $jsonContent[$i]['idSociete'] = $mission->getSociete()->getId();
            $jsonContent[$i]['nom'] = $mission->getNom();
            $jsonContent[$i]['description'] = $mission->getDescription();
            $jsonContent[$i]['date'] = $mission->getDate();
            $jsonContent[$i]['nombreHeures'] = $mission->getNombreHeures();
            $jsonContent[$i]['prixHeure'] = $mission->getPrixHeure();
            $jsonContent[$i]['ville'] = $mission->getVille();
            $jsonContent[$i]['longitude'] = $mission->getLongitude();
            $jsonContent[$i]['latitude'] = $mission->getLatitude();
            $i++;
        }

        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("recuperer_societe_mission")
     * @return Response
     */
    public function recupererSocieteMission(): Response
    {
        $societes = $this->getDoctrine()->getRepository(Societe::class)->findAll();
        $jsonContent = null;
        $i = 0;

        if (!$societes) {
            return new Response(null);
        }

        foreach ($societes as $societe) {
            $jsonContent[$i]['idSociete'] = $societe->getId();
            $jsonContent[$i]['nomSociete'] = $societe->getNom();
            $jsonContent[$i]['idPhotoSociete'] = $societe->getIdPhoto();
            $jsonContent[$i]['telSociete'] = "T" . $societe->getTel();

            $j = 0;
            foreach ($societe->getOffreDeTravail() as $offreDeTravail) {
                $jsonContent[$i]['offres'][$j]['idOffre'] = $offreDeTravail->getId();
                $jsonContent[$i]['offres'][$j]['nomOffre'] = $offreDeTravail->getNom();
                $j++;
            }

            $i++;
        }

        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("AddMission")
     * @throws Exception
     */
    public function manipulerMission(Request $request): Response
    {
        $idMission = (int)$request->get("id");

        if ($idMission == null) {
            $mission = new Mission();
        } else {
            $mission = $this->getDoctrine()->getRepository(Mission::class)->find($idMission);
        }
        $societe = $this->getDoctrine()->getRepository(Societe::class)->find(1);

        $nom = (string)$request->get("nom");

        $description = $request->get("description");
        $date = $request->get("date");
        $nbheure = (int)$request->get("nombreHeures");
        $prixheure = (float)$request->get("prixHeure");
        $ville = $request->get("ville");
        $longitude = (string)$request->get("longitude");
        $latitude = (string)$request->get("latitude");

        $mission
            ->setNom($nom)
            ->setSociete($societe)
            ->setDescription($description)
            ->setDate(DateTime::createFromFormat('d/m/Y', $date))
            ->setNombreHeures($nbheure)
            ->setPrixHeure($prixheure)
            ->setVille($ville)
            ->setLongitude($longitude)
            ->setLatitude($latitude);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($mission);
        $manager->flush();

        return new Response("Ajout/Modification effectué");
    }

    /**
     * @Route("supprimer_mission")
     * @param Request $request
     * @return Response
     */
    public function supprimerMission(Request $request): Response
    {
        $idMission = (int)$request->get("id");

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($this->getDoctrine()->getRepository(Mission::class)->find($idMission));
        $manager->flush();

        return new Response("Suppression effectué");
    }
}
