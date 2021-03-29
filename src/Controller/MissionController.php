<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Mission;
use App\Entity\Questionnaire;
use App\Entity\Question;
use App\Entity\CandidatureMission;
use App\Entity\Candidat;
use App\Form\FormType;
use App\Form\QuestType;
use App\Form\ReponseType;
use App\Repository\MissionRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class MissionController extends Controller
{
    // /**
    //  * @Route("/mission", name="mission")
    //  */
    // public function index(): Response
    // {
    //     return $this->render('/frontEnd/mission/afficher.html.twig', [
    //         'controller_name' => 'MissionController',
    //     ]);
    // }


      /**
     * @Route("/mission", name="mission")
     */
    public function Mission(Request $request)
    {
        $mission= new Mission();
        $repository = $this->getDoctrine()->getRepository(Mission::class);
        $missions= $repository->findAll();
        $paginator = $this->get('knp_paginator');
        $res = $paginator->paginate(
          $missions,
          $request->query->getInt('page', 1),
          $request->query->getInt('limit', 3));
        return $this->render('/frontEnd/mission/afficher.html.twig',['missions' => $res]);
    }

      /**
     * @Route("/missionsearch", name="missionsearch")
     */
    public function missionsearch(Request $request,NormalizerInterface $normalizer)
    {
      $recherche = $request->get("searchValue"); 
      //$mission=$this->getDoctrine()->getRepository(Mission::class)->findBy(['mission_name'=>$recherche]); 
      $mission=$this->getDoctrine()->getRepository(Mission::class)->findOneByMissionName($recherche); 
      
      // $maValeur = $request->get("tab");
      // $tab=explode (',' ,$maValeur );
      // $em=$this->getDoctrine()->getManager();
      //   for($i=0; $i<count($tab);$i++)
      //   {if($tab[$i]!="undefined")
      //    { $Questionnaire= new Questionnaire();
      //     $Questionnaire->setDescription($tab[$i])->setMission($mission);
      //    $em->persist($Questionnaire);
      //    $em->flush();}
      //   }
      //    return $this->redirectToRoute('mission');

        $jsonContent = $normalizer->normalize($mission, 'json',['groups' => 'post:read',]);
        $retour = json_encode($jsonContent);
        return new Response($retour);
    }

     /**
     * @Route("/ajouterMission", name="AjouterMission")
     */
    public function AddMission(Request $request): Response
    {  $mission= new Mission();
      $form= $this->createForm(FormType::class,$mission);
      $form->add('Ajouter',SubmitType::class, array('label' => 'Ajouter / Donner vos questions  >'));
      $form->handleRequest($request);
      // && $form->isValid()
      if($form->isSubmitted()&& $form->isValid())
      {
         $em=$this->getDoctrine()->getManager();
         $em->persist($mission);
         $em->flush();
         $id=$mission->getId();
         return $this->redirectToRoute('addQuest', array('name' => $id));
      }
        return $this->render('/frontEnd/mission/essai.html.twig', [
          'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/addQuest/{name}", name="addQuest")
     */
    public function AddQuestMission(Request $request,$name): Response
    {  $Questionnaire= new Questionnaire();
      $form= $this->createForm(QuestType::class,$Questionnaire);
      // $form->add('Ajouter',SubmitType::class, array('label' => 'Ajouter votre questionnaire >'));
      $maValeur = $request->request->get("valeurArecuperer", "valeur par défaut si le champ n'existe pas");
      // $form->handleRequest($request);
      // if($form->isSubmitted()&& $form->isValid())
      // {
      //    $em=$this->getDoctrine()->getManager();
      //    $em->persist($Questionnaire);
      //    $em->flush();
      //   //  return $this->redirectToRoute('/questMission/',['id'=>$mission.id]);
      // }
        return $this->render('/frontEnd/mission/addQuestMission.html.twig', [
          'form' => $form->createView(),
          'id2'=>$name,
        ]);
    }

      /**
     * @Route("/addQuest/ajouterchamp/pp", name="ajouterchamp")
     */
    public function ajouterchamp(Request $request): Response
    { 
      return new Response(null);

    }

      /**
     * @Route("addQuest/ajouterQuestionnaire/p", name="ajouterQuestionnaire")
     */
    public function ajouterQuestionnaire(Request $request,NormalizerInterface $normalizer): Response
    { $id = $request->get("id"); 
      $mission=$this->getDoctrine()->getRepository(Mission::class)->find($id); 
      
      $maValeur = $request->get("tab");
      $tab=explode (',' ,$maValeur );
      $em=$this->getDoctrine()->getManager();
        for($i=0; $i<count($tab);$i++)
        {if($tab[$i]!="undefined")
         { $Questionnaire= new Questionnaire();
          $Questionnaire->setDescription($tab[$i])->setMission($mission);
         $em->persist($Questionnaire);
         $em->flush();}
        }
         return $this->redirectToRoute('mission');
        //
        // $jsonContent = $normalizer->normalize($tab, 'json',['groups' => 'post:read',]);
        // $retour = json_encode($jsonContent);
        // return new Response($retour);
    }

    /**
     * @Route("/condidatureMission/{id}", name="condidatureMission")
     */
    public function condidature(Request $request,$id): Response
    { $em=$this->getDoctrine()->getManager();
      $mission=$em->getRepository(Mission::class)->find($id);
        return $this->render('/frontEnd/mission/condiMission.html.twig', [
          'missions' =>$mission,
        ]);
    }

       /**
     * @Route("/questMission/{id}", name="questMission")
     */
    public function questionnaire(Request $request,$id): Response
    { 
       $reponse= new Questionnaire();
      // $form= $this->createForm(ReponseType::class,$reponse);
      // $form->add('Ajouter',SubmitType::class);
      // $form->handleRequest($request);
      $em=$this->getDoctrine()->getManager();
      $question=$em->getRepository(Questionnaire::class)->findBy(['Mission' => $id]);
      $num=$em->getRepository(Question::class)->findAll();
      $i=0;
      foreach($question as $reponse)
      {
        $i++;
      }
      foreach($num as $reponse)
      {
        $idreponse = $reponse->getId();
      }
        return $this->render('/frontEnd/mission/questMission.html.twig', [
          'question'=>$question,
          'nb'=>$i,
          'id'=>$id,
          'num'=>$idreponse
        ]);

    }

      /**
     * @Route("questMission/condidature/i", name="condidature")
     */
    public function Addcondidature(Request $request,NormalizerInterface $normalizer): Response
    {
       $id = $request->get("id"); 
       $em=$this->getDoctrine()->getManager();
       $question=$em->getRepository(Questionnaire::class)->findBy(['Mission' => $id]);
       $num = $request->get("num"); 
      $maValeur = $request->get("ch");
      $tab=explode (',' ,$maValeur );
      // $em=$this->getDoctrine()->getManager();
//replir la table question
        for($i=0; $i<count($tab);$i++)
        {if($tab[$i]!="undefined")
         {
           $Questionnaire= new Question();
          $Questionnaire->setDescription($tab[$i])->setQuestionnaire($question[$i])->setNumReponse($num);
         $em->persist($Questionnaire);
         $em->flush();}
        }
//remplir la table condiature 
        $condidat= $em->getRepository(Candidat::class)->find(1);
        $mission=$em->getRepository(Mission::class)->find($id);
        $condidature= new CandidatureMission();
        $condidature->setCandidat($condidat)->setMission($mission)->setNumreponse($num);
        $em->persist($condidature);
        $em->flush();
         return $this->redirectToRoute('mission');
        // $jsonContent = $normalizer->normalize($maValeur, 'json',['groups' => 'post:read',]);
        // $retour = json_encode($jsonContent);
        // return new Response($retour);
    }

         /**
     * @Route("/societeMission/{id}", name="societeMission")
     */
    public function societeMission(Request $request,$id): Response
    { $em=$this->getDoctrine()->getManager();
      $mission=$em->getRepository(Mission::class)->findBy(['societe'=>$id]);
        return $this->render('/frontEnd/mission/societeMission.html.twig', [
          'missions' =>$mission,
        ]);
    }

     /**
     * @Route("/condidatureList/{id}", name="condidatureList")
     */
    public function condidatureList($id)
    {
      $em=$this->getDoctrine()->getManager();
      $condidature=$em->getRepository(CandidatureMission::class)->findBy(['mission'=>$id]);
      // $condidat= $em->getRepository(Candidat::class)->find(1);
      $i=0;
      foreach($condidature as $reponse)
      {
        $condidat[$i]=$reponse->getCandidat();
        $i++;
      }
      return $this->render('/frontEnd/mission/condidature.html.twig', [
        'condidature'=>$condidature,
        'condidat'=>$condidat
      ]);
    }

      /**
     * @Route("/reponse/{id}", name="reponse")
     */
    public function reponse($id)
    {
      $em=$this->getDoctrine()->getManager();
      $question=$em->getRepository(Question::class)->findBy(['num_reponse'=>$id]);
      // // $condidat= $em->getRepository(Candidat::class)->find(1);
      $i=0;
      foreach($question as $reponse)
      {
        $quest[$i]=$reponse->getQuestionnaire();
        $i++;
      }
      return $this->render('/frontEnd/mission/reponse.html.twig', [
        'reponse'=>$question,
        'question'=>$quest
      ]);
    }

        /**
     * @Route("/deleteMission/{id}", name="deleteMission")
     */
    public function deleteMission($id)
    {
        $em=$this->getDoctrine()->getManager();
        $classe=$em->getRepository(Mission::class)->find($id);
        $em->remove($classe);
        $em->flush();
        return $this->redirectToRoute("mission");
    }

      /**
     * @Route("/updateMission/{id}", name="updateMission")
     */
    public function UpdateMission(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $classroom=$em->getRepository(Mission::class)->find($id);
        $form=$this->createForm(FormType::class,$classroom);
        $form->add('Modifier',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute('mission');
        }
        return $this->render('/frontEnd/mission/updateMission.html.twig', [
            "form-title" =>"Modifier une Mission",
            "form" => $form->createView(),
        ]);
    }
}
