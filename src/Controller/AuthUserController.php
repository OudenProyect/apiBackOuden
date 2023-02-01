<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthUserController extends AbstractController
{
    #[Route('/auth/user', name: 'app_auth_user')]
    public function index(): Response
    {
        return dd("ohla");
    }

    #[Route('/signin', name: 'app_auth_user',methods:'POST')]
    public function register(Request $request,UserRepository $repo,UserPasswordHasherInterface $hash): JsonResponse
    {
        $data = json_decode($request->getContent());
        $user  = new User();
        $user->setName($data->name);
        $user->setEmail($data->email);
        $user->setRoles([]);
        $hashed =$hash->hashPassword($user,$data->password);
        $user->setPassword($hashed);
        $repo->save($user,true);
        return $this->json($user);
    }

    #[Route('/auth/login', name: 'app_auth_login', methods:'POST')]
    public function login(Request $request,UserRepository $repo): JsonResponse
    {
        $data = json_decode($request->getContent());
        $exits = $repo->searchUser($data->email);
        if(count($exits) <= 0){
            $exits = "usuario no encontrado";
        }
        
        return $this->json($exits);
    }

}
