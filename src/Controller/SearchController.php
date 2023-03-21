<?php

namespace App\Controller;

use App\Repository\HouseRepository;
use App\Repository\LocationRepository;
use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(LocationRepository $locations, Request $search): JsonResponse
    {
        // recibimos lo que busca el usuario @param string
        $locats = $locations->findBy(['province' => $search->get('search')]);
        if (count($locats) === 0) {
            $locats =
                $locations->findBy(['region' => $search->get('search')]);
        }
        return $this->json($locats);
    }

    #[Route('/casas', name: 'app_search_casas')]
    public function casas(HouseRepository $casas): JsonResponse
    {
        // recibimos lo que busca el usuario @param string
        $casa = $casas->findAll();
        dd($casa[0]);
        return $this->json($casa);
    }
}
