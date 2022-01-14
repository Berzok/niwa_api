<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController {

    #[Route(
        '/error',
        name: 'Fallback'
    )]
    public function index(UserPasswordHasherInterface $passwordHasher) {

        print_r('ogergnerg');

    }
}
