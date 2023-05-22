<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\House;
use App\Entity\LocationServices;
use App\Repository\HouseRepository;
use App\Repository\LocationRepository;
use App\Repository\LocationServicesRepository;
use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\OrderBy;
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
            if ($search->get('ubicacion') != 'all') {
                $locats = $services->findBy(['name' => $search->get('ubicacion')]);

                // buscamos en los servicios las casas relacionadas con la ubicacion y buscamos la publicacion
                if (count($locats[0]->getHouses()) > 0) {
                    foreach ($locats[0]->getHouses() as $h) {
                        $publi = $publication->findBy(['house' => $h->getId()]);
                        $result = array_merge($result, $publi);
                    }
                } else {
                    $result = 'No hay casas en esta ubicacion';
                }
            } else {
                $result = $publication->findAll();
            }
        } catch (Exception $e) {
        }
        return $this->json(
            $result,
            200,
            [],
            [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                },
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['publications', 'publication', 'houses',]
            ]
            // [AbstractNormalizer::IGNORED_ATTRIBUTES => ['publications', 'publication', 'user', 'house']]
        );
    }

    #[Route('/casas', name: 'app_search_casas')]
    public function casas(HouseRepository $casas): JsonResponse
    {
        // recibimos lo que busca el usuario @param string
        $casa = $casas->findBy(['location' => 2]);
        return $this->json($casa);
    }

    #[Route('/filtrar', name: 'app_filtrar')]
    public function filtros(Request $request, EntityManagerInterface $entityManager, PublicationRepository $publi)
    {
        $res = [];
        $tipo = $request->get('tipo');
        $hab = $request->get('hab');
        $bath = $request->get('bath');
        $pricemin = $request->get('pricemin');
        $pricemax = $request->get('pricemax');
        $surfacemin = $request->get('surfacemin');
        $surfacemax = $request->get('surfacemax');
        $extras = $request->get('extras') ? explode(',', $request->get('extras')) : [];

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('p')
            ->from('App\Entity\House', 'p');

        if (count($extras) > 0) {
            $queryBuilder->leftJoin('p.feature', 'he');
        }
        if ($tipo != "Indiferent") {
            $queryBuilder->where('p.type = :tipo')
                ->setParameter('tipo', $tipo);
        }

        if ($hab != 'null') {
            $queryBuilder->andWhere('p.n_bedrooms = :hab')
                ->setParameter('hab', $hab);
        }
        if ($bath != 'null') {
            $queryBuilder->andWhere('p.toilets = :bath')
                ->setParameter('bath', $bath);
        }
        if ($pricemin != 'Indiferent' && $pricemax != 'Indiferent') {
            $queryBuilder->andWhere('p.price BETWEEN :pricemin AND :pricemax')
                ->setParameter('pricemin', $pricemin)
                ->setParameter('pricemax', $pricemax);
        }

        if ($surfacemin != 'Indiferent' && $surfacemax != 'Indiferent') {
            $queryBuilder->andWhere('p.UsefulLivinArea BETWEEN :surfacemin AND :surfacemax')
                ->setParameter('surfacemin', (int) $surfacemin)
                ->setParameter('surfacemax', (int) $surfacemax);
        }

        if (count($extras) > 1) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->in('he.id', $extras)
            )
                ->groupBy('p.id')
                ->having($queryBuilder->expr()->eq($queryBuilder->expr()->countDistinct('he.id'), 2));
        } else if (count($extras) == 1) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->in('he.id', $extras)
            )
                ->groupBy('p.id')
                ->having($queryBuilder->expr()->eq($queryBuilder->expr()->countDistinct('he.id'), 1));
        }

        $query = $queryBuilder->getQuery()->getResult();

        //busca en todas las publicaciones, para devolver ya teniendo el id de las casas
        if (count($query) > 0) {
            foreach ($query as $casa) {
                $casaid = $casa->getId();
                $p = $publi->findBy(['house' => $casaid]);
                if (count($p) > 0) {
                    $res = array_merge($res, $p);
                }
            }
        }

        // returns an array of Product objects
        return $this->json(
            $res,
            200,
            [],
            [AbstractNormalizer::IGNORED_ATTRIBUTES => ['publications', 'publication', 'houses', 'user']]
            // [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function () {
            //     return 'self';
            // }]
        );
    }

    #[Route('/publicacion', name: 'app_publication')]
    public function publicacion(Request $req, PublicationRepository $pub)
    {
        $id = $req->get('id');
        $publicacion = $pub->find($id);
        if (!$publicacion) {
            $publicacion = 'No existe esa publicacion con el id';
        }
        // returns an array of Product objects
        return $this->json(
            $publicacion,
            200,
            [],
            [AbstractNormalizer::IGNORED_ATTRIBUTES => ['publications', 'publication', 'houses']]
        );
    }
}
