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
            if($search->get('ubicacion') != 'all'){
                $locats = $services->findBy(['name' => $search->get('ubicacion')]);

                // buscamos en los servicios las casas relacionadas con la ubicacion y buscamos la publicacion
                if (count($locats[0]->getHouses()) > 0) {
                    foreach ($locats[0]->getHouses() as $h) {
                        $publi = $publication->findBy(['house' => $h->getId()]);
                        $result = array_merge($result, $publi);
                    }
                }else{
                    $result = 'No hay casas en esta ubicacion';
                }
            }else{
                $result = $publication->findAll();
            }
            

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
