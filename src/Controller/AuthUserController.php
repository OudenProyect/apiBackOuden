<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthUserController extends AbstractController
{
    #[Route('/auth/user', name: 'app_auth_user')]
    public function index(): Response
    {
        return dd("ohla");
    }
}
