<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Form\EditprofilType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @IsGranted("ROLE_USER")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/account/profile", name="account_profile")
     */
    public function indexProfil(Request $request, ManagerRegistry $registry, UserRepository $repository, SerializerInterface $serializer): Response
    {
        $json = $serializer->serialize(
            $this->getUser()->getClient(),
            'json',
            ['groups' => 'show_infos']
        );

        dump($json);

        $repoUser = $this->getDoctrine()->getRepository(User::class);
        $user = $repoUser->findAll();
        
        return $this->render('account/index.html.twig', [
            'User' => $user,
            'adresses' => $this->getUser()->getClient()->getAdresses()->toArray(),
        ]);
    }
    
    /**
     * @Route("/edit/mon-compte", name="edit_account")
     */
    public function edit(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(EditprofilType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if  (null !== $this->getUser()->getEditEmail()) {
                $this->getUser()->setEmail($this->getUser()->getEditEmail());
            }
            if  (null !== $this->getUser()->getConfirmPassword()) {
                $hash = $encoder->encodePassword($this->getUser(), $this->getUser()->getConfirmPassword());
                $this->getUser()->setPassword($hash);
            }

            $manager->persist($this->getUser());
            $manager->flush();

            /* $this->addFlash('messsage', 'profil mis à jour'); */  //Alerte flash pour le changement des infos//
            
            return $this->RedirectToRoute('app_logout');
        }

        return $this->render('account/editprofil.html.twig', [
            'form' => $form->createView(), 
        ]);
    }

    /**
     * @Route("/edit/mon-compte/pass", name="edit_pass_account")
     */
    public function editpass(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        if ($request->isMethod('post')){
            $em = $this->getDoctrine()->getManager();
            
            $user = $this->getUser();

            // vérifier si les deux mot de passes sont identiques //
            if ($request->request->get('pass') == $request->request->get('pass2')){
                $user->setPassword($encoder->encodePassword($user, $request->request->get('pass')));
                $em->persist($user);
                $em->flush();
                $this->addFlash('message', 'votre mot de passe à été mis à jour avec succès');

                return $this->redirectToRoute('account_profile');
            }
                else{ $this->addFlash('erreur', 'les deux mots de passes ne sont pas identiques');
            }
        }

        return $this->render('account/editpass.html.twig', [
            /*'form' => $form->createView(),*/
        ]);
    }



    /**
     * @Route("mon-compte/adresse/new", name="adresse_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $adresse = new Adresse();
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $adresse->setClient($this->getUser()->getClient());
            $entityManager->persist($adresse);
            $entityManager->flush();

            return $this->redirectToRoute('account_profile');
        }

        return $this->render('account/new.html.twig', [
            'adresse' => $adresse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("account/adresse/{id}/edit", name="adresse_edit", methods={"GET","POST"})
     */
    public function editadresse(Request $request, Adresse $adresse): Response
    {
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('account_profile');
        }

        return $this->render('account/edit.html.twig', [
            'adresse' => $adresse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("mon-compte/adresse/delete/{id}", name="adresse_delete", methods={"POST"})
     */
    public function delete(Request $request, Adresse $adresse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adresse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adresse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('account_profile');
    }
}
