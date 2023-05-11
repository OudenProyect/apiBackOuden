<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Repository\UserRepository;
use Exception;
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
    #[Route('/api/delete', name: 'delete.user', methods: 'DELETE')]
    public function deleteUser(
        UserRepository $repo,
        TokenStorageInterface $token
    ): JsonResponse {
        try {
            $email = $token->getToken()->getUserIdentifier();
            $user = $repo->findBy(['email' => $email]);
            $repo->remove($user[0], true);
        } catch (Exception $e) {
            $e->getMessage();
        }

        return $this->json($user);
    }

    // ruta protegida
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
                        case 'nickname':
                            $user->setNickname($data['value']);
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

        // cogemos el usuario con el token, que devuelve el email del usuario que es como se idetifican
        $email = $pp->getToken()->getUserIdentifier();
        $PWD = $repo->findBy(['email' => $email]);
        $passwords = [];
        $data = json_decode($request->getContent(), true);
        $new_pass = $data['newPassword'];

        foreach ($PWD as $user) {
            $passwords[] = $user->getPassword();
        }
        $message = null;

        if (!password_verify($data['oldPassword'], $passwords[0])) {
            $message = "Error";
        } else {
            $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
            $PWD[0]->setPassword($hashed);
            $repo->save($PWD[0], true);
            $message = "Contrasena cambiada";
        }
        return $this->json(['message' => $message]);
    }
}
