<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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
    // las rutas protegidas inician con el prefijo /api
    // #[Route('/api/user', name: 'app_auth_userrrr', methods: 'GET')]
    // public function index(TokenStorageInterface $tokenStorage): JsonResponse
    // {
    //     return $this->json([
    //         'user' => $tokenStorage->getToken()->getUser()
    //     ]);
    // }
    #[Route('/api/user', name: 'app_auth_userrrr', methods: 'GET')]
    public function index(TokenStorageInterface $tokenStorage): JsonResponse
    {
        return $this->json([
            'user' => $tokenStorage->getToken()->getUser()
        ], 200, [], [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function () {
            return 'self';
        }]);
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
            $user = $e->getMessage();
            // $user = "El email ya esta registrado";
        }

        return $this->json($user, $codigo);
    }


    #[Route('/register', name: 'app_auth_userr', methods: 'POST')]
    public function registerCompany(Request $request, CompanyRepository $comp, UserRepository $users, UserPasswordHasherInterface $hash): JsonResponse
    {
        try {
            $data = json_decode($request->getContent());
            $codigo = Response::HTTP_BAD_REQUEST;

            // Verificar si el email ya está en uso
            $existingUser = $users->findOneBy(['email' => $data->email]);
            if ($existingUser) {
                throw new \Exception('El email ya está en uso');
            }

            // Verificar si el cif empresa ya está en uso
            $existingCompany = $comp->findOneBy(['Cif_company' => $data->cifEmpresa]);
            if ($existingCompany) {
                throw new \Exception('El cif empresa ya está en uso');
            }

            // Si no hay duplicados, crear los registros
            $user  = new User();
            $user->setName($data->usuario);
            $user->setEmail($data->email);
            $user->setRoles([]);
            $hashed = $hash->hashPassword($user, $data->password);
            $user->setPassword($hashed);
            $user->setPhone($data->phone);

            $company  = new Company();
            $company->setName($data->nombreEmpresa);
            $company->setCifCompany($data->cifEmpresa);
            $company->setLinkWeb($data->linkPagina);
            $company->setLocation($data->localizacionEmpresa);
            $company->setDescription($data->descripcion);
            $company->setPhone($data->phone);
            $comp->save($company, true);

            $user->setCifCompany($company);
            $users->save($user, true);

            $codigo = Response::HTTP_OK;
        } catch (\Exception $e) {
            $user = $e->getMessage();
        }

        return $this->json($user, $codigo, [], [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function () {
            return 'self';
        }]);
    }
}
