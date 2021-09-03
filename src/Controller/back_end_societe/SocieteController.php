<?php

namespace App\Controller\back_end_societe;

use App\Entity\Societe;
use App\Entity\User;
use App\Form\SocieteType;
use Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("espace_societe/")
 */
class SocieteController extends AbstractController
{
    /**
     * @Route("societe/{idSociete}/profil", name="profil_societe")
     */
    public function profile($idSociete): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('back_end_societe/societe/_profile/afficher.html.twig', [
            'societe' => $this->getDoctrine()->getRepository(Societe::class)->find($idSociete),
        ]);
    }

    /**
     * @Route("societe/{idSociete}/modifier", name="modifier_societe")
     */
    public function modifierSociete(Request $request, $idSociete, UserPasswordEncoderInterface $passwordEncoder)
    {
        $manager = $this->getDoctrine()->getManager();
        $societe = $manager->getRepository(Societe::class)->find($idSociete);
        $user = $manager->getRepository(User::class)->find([
            'id' => $societe->getUser()->getId(),
        ]);

        $form = $this->createForm(SocieteType::class, $societe);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $file = $request->files->get('societe')['idPhoto'];
                $uploads_directory = $this->getParameter('uploads_directory_societe');
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $uploads_directory,
                    $filename
                );
                $societe->setIdPhoto('http://localhost/khedemti/images/societe/' . $filename);
            } catch (Error $e) {

            }

            $societe = $form->getData();
            $manager->persist($user);
            $manager->persist($societe);
            $manager->flush();

            return $this->redirect('/espace_societe/societe/' . $societe->getId() . '/profil');
        }

        return $this->render('back_end_societe/societe/_profile/modifier.html.twig', [
            'societe' => $societe,
            'form' => $form->createView(),
            'manipulation' => "Modifier"
        ]);
    }

    /**
     * @Route("societe/{idUser}/modifier_email", name="modifier_email")
     */
    public function modifierEmail(Request $request, $idUser)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository(User::class)->find($idUser);

        $form = $this->createForm(UserType::class, $user);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $societe = $form->getData();
            $manager->persist($societe);
            $manager->flush();

            return $this->render('/espace_societe/societe/' . $societe->getId() . '/profil');
        }

        return $this->render('back_end_societe/societe/modification_email.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'manipulation' => "Modifier"
        ]);
    }

    /**
     * @Route("societe/{idUser}/modifier_mot_de_passe", name="modifier_mot_de_passe")
     */
    public function modifierMotDePasse(Request $request, $idUser)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository(User::class)->find($idUser);

        $form = $this->createForm(UserType::class, $user);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $societe = $form->getData();
            $manager->persist($societe);
            $manager->flush();

            return $this->render('/espace_societe/societe/' . $societe->getId() . '/profil');
        }

        return $this->render('back_end_societe/societe/modification_mot_de_passe.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'manipulation' => "Modifier"
        ]);
    }
}
