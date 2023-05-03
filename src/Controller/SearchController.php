<?php

namespace App\Controller;

use App\Entity\LocationServices;
use App\Repository\HouseRepository;
use App\Repository\LocationRepository;
use App\Repository\LocationServicesRepository;
use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use Exception;
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
    public function index(LocationServicesRepository $services, Request $search, PublicationRepository $publication): JsonResponse
    {
        $result = [];

        try {
            $locats = $services->findBy(['name' => $search->get('ubicacion')]);

            if (count($locats[0]->getHouses()) > 0) {
                foreach ($locats[0]->getHouses() as $h) {
                    // dd($publication->findBy(['house' => $h->getId()]));
                    $publi = $publication->findBy(['house' => $h->getId()]);
                    // dd($publi);
                    $result[] = $publi;
                }
            }

            // if (count($locats[0]) > 0) {
            //     foreach ($publication->findAll() as $publi) {
            //         foreach ($publi->getHouse()->getAreaServices() as $service) {
            //             if (strtolower($service->getName()) == strtolower($search->get('ubicacion'))) {
            //                 // devolvemos la publicacion con todos sus datos de la casas etc
            //                 $result[] = $publi;
            //             }
            //         }
            //     }
            // }
        } catch (Exception $e) {
        }
        return $this->json(
            $result,
            200,
            [],
            // [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function () {
            //     return 'self';
            // }]
            [AbstractNormalizer::IGNORED_ATTRIBUTES => ['publications', 'publication', 'houses']]
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
