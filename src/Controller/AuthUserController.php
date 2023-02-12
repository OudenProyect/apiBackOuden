<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthUserController extends AbstractController
{

    // #[Route('/api/login', name: 'api_loginnn', methods: 'POST')]
    // public function login(Request $request, HttpClientInterface $httpClient)
    // {
    //     try {
    //         $content = json_decode($request->getContent(), true);
    //         $response = $httpClient->request('POST', 'http://127.0.0.1:8000/api/login_check', ['body' => $content]);
    //         return $this->json($response);
    //     } catch (Exception $e) {
    //         return $this->json([
    //             'exceptionMessage' => $e->getMessage(),
    //             'exception' => $e,
    //             'message' => 'Email o contraseña incorrectos tetas'
    //         ]);
    //     }
    // }


    // ruta para coger al usuario por el token
    #[Route('/api/user', name: 'app_auth_userrrr', methods: 'GET')]
    public function index(TokenStorageInterface $tokenStorage): JsonResponse
    {
        return $this->json([
            'user' => $tokenStorage->getToken()->getUser()
        ]);
    }

    // creacion nuevo usuario
    #[Route('/signin', name: 'app_auth_user', methods: 'POST')]
    public function register(Request $request, UserRepository $repo, UserPasswordHasherInterface $hash): JsonResponse
    {
        try {
            $data = json_decode($request->getContent());
            $codigo = Response::HTTP_BAD_REQUEST;

            $user  = new User();
            $user->setName($data->usuario);
            $user->setEmail($data->email);
            $user->setRoles([]);
            $hashed = $hash->hashPassword($user, $data->password);
            $user->setPassword($hashed);
            $repo->save($user, true);
            $codigo = Response::HTTP_OK;
        } catch (\Exception $e) {
            $user = "El email ya esta registrado";
        }

        return $this->json($user, $codigo);
    }
}
