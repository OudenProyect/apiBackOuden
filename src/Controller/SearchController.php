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
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class SearchController extends AbstractController
{
    // Busqueda de casas por provincia o region
    #[Route('/search', name: 'app_search')]
    public function index(HouseRepository $cas, Request $search, LocationRepository $locations): JsonResponse
    {
        $casas = [];
        $codigo = Response::HTTP_OK;
        //buscar resultados
        $locats = $locations->findBy(['province' => $search->get('ubicacion')]);
        if (count($locats) === 0) {
            $locats = count($locations->findBy(['region' => $search->get('ubicacion')])) != 0 ? $locations->findBy(['region' => $search->get('ubicacion')]) : null;
        }


        // si los hay , consulta las casas de acuerdo al nombre de la localizacion
        if ($locats != null) {
            foreach ($locats as $casa => $va) {
                $bsq = $cas->findBy(['location' => $va->getId()]);
                $casas[] = $bsq[0];
            }
        }

        return $this->json(
            $casas,
            $codigo,
            [],
            [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function () {
                return 'self';
            }]
        );
    }

    #[Route('/casas', name: 'app_search_casas')]
    public function casas(HouseRepository $casas): JsonResponse
    {
        // recibimos lo que busca el usuario @param string
        $casa = $casas->findBy(['location' => 2]);
        dd($casa);
        return $this->json($casa);
    }
}
