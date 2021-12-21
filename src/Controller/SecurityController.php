<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $manager=$this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        
        /* if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getPassword() === $user->getConfirmPassword()) {
                $form->setpassword($user->getpassword())
            }
        } */
        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getPassword() === $user->getConfirmPassword()) {
                $hash = $encoder->encodePassword($user, $user->getPassword());
                $user->SetPassword($hash);
                
                $manager->persist($user);
                $manager->flush();

                return $this->redirectToRoute('app_login');
            } else {
                $error = new FormError('Les mots de passes ne sont pas identiques');
                $form->addError($error);
                dump($form);
            }

        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
