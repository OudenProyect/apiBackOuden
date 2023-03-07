<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Flex\Response as FlexResponse;

class UserController extends AbstractController
{
    #[Route('/delete/{id}', name: 'delete.user', methods: 'DELETE')]
    public function deleteUser(UserRepository $repo, int $id): JsonResponse
    {
        $user = $repo->find($id);
        $message = "usuario no encontrado";
        if ($user != null) {
            $repo->remove($user, true);
            $message = "usuario eliminado";
        }
        return $this->json($message);
    }

    // esta ruta era inicialmente para traer informacion del usuario pero el token
    // como trae la informacion se la coge desde ella
    #[Route('/profile/{id}', name: 'profile.user', methods: 'GET')]
    public function profile(UserRepository $repo, int $id): JsonResponse
    {
        try {
            $user = $repo->find($id);
            if ($user == null) {
                $user = "usuario no encontrado";
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
        return $this->json($user);
    }

    #[Route('/api/profile/edit', name: 'profile.edit', methods: 'PUT')]
    public function edit(UserRepository $repo, Request $req): JsonResponse
    {
        $name = null;

        try {
            $data = json_decode($req->getContent(), true);
            $user = $repo->find($data['id']);
            if ($user) {
                // Siempre se recibira el dato edit
                if (isset($data['value'])) {
                    switch ($data['edit']) {
                        case 'name':
                            $user->setName($data['value']);
                            break;

                        case 'phone';
                            $user->setPhone((int)$data['value']);
                            break;
                        case 'cif_company';
                            $user->setCifCompany($data['value']);
                            break;
                        default:
                            $name = "No se recibio el campo a cambiar";
                    }
                } else {
                    $name = "El campo esta vacio";
                }
                $repo->save($user, true);
                $name = $user;
            } else {
                $name = "usuario no encontrado";
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }

        return $this->json($name);
    }

    #[Route('/api/changeUserPwd', name: 'app_change_pass', methods: 'PUT')]
    public function changeUserPwd(Request $request, UserRepository $repo, UserPasswordHasherInterface $hash, TokenStorageInterface $pp): JsonResponse
    {
        $email = $pp->getToken()->getUserIdentifier();
        $PWD = $repo->findBy(['email'=>$email]);
        $passwords = [];

        foreach ($PWD as $user) {
            $passwords[] = $user->getPassword();
        }   
        // recibimos id usuario y contraseña actual para validar el cambio
        // y luego hasheamos la nueva contraseña
        $data = json_decode($request->getContent());
        // $pass = $data->oldPassword;
        // $new_pass = $data->newPassword;
        // $confirm_pass = $data->confirmPassword;
        // $user = $repo->find((int)$data->id);

        // if (!password_verify($pass, $user->getPassword())) {
        //     $message = "La contraseña no es valida";
        // } else {
        //     $hashed = $hash->hashPassword($user, $new_pass);
        //     $user->setPassword($hashed);
        //     $repo->save($user, true);
        //     $message = "Contraseña cambiada";
        // }
        return $this->json($passwords);
    }
}
