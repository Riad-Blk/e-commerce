<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TestController extends AbstractController
{
    /**
     * @Route("/mdp", name="mdp")
     */
    public function index(UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();

        dump($encoder->encodePassword($user, 'admin'));

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
