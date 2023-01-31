<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Flex\Response as FlexResponse;

class UserController extends AbstractController
{
    #[Route('/delete/{id}' ,name:'delete.user', methods:'DELETE')]
    public function deleteUser(UserRepository $repo, int $id): JsonResponse
    {
        $user = $repo->find($id);
        $message = "usuario no encontrado";
        if($user != null){
            $repo->remove($user,true);
            $message = "usuario eliminado";
        }
        return $this->json($message);
    }

    #[Route('/profile/{id}' ,name:'profile.user', methods:'GET')]
    public function profile(UserRepository $repo, int $id): JsonResponse
    {
        try{
            $user = $repo->find($id);
            if($user == null){
                $user = "usuario no encontrado";
            }
        }catch(\Exception $e){
            $e->getMessage();
        }
        return $this->json($user);
    }

    #[Route('/profile/edit' ,name:'profile.edit', methods:'PUT')]
    public function edit(UserRepository $repo, Request $req): Response
    {
        $name= null;

        try{
            $data = json_decode($req->getContent(),true);

            // Siempre se recibira el dato edit

            switch($data['edit']){
                case 'name':
                    $name = "name edcion";
                    break;
                case 'email':
                    $name = "email";
                    break;
                case 'img_profile':
                    $name = "profile_ img";
                    break;
                case 'phone';
                    $name = "phone";
                    break;
                case 'cif_company';
                    $name = "cif_company";
                    break;
                default:
                    $name = "Error";
    
            }
        }catch(\Exception $e){
            $e->getMessage();
        }
        return new Response($name);
    }
}
