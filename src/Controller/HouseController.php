<?php

namespace App\Controller;

use App\Entity\House;
use App\Repository\FeatureRepository;
use App\Repository\HouseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class HouseController extends AbstractController
{
    #[Route('/type', name: 'app_type', methods: 'GET')]
    public function tipos(HouseRepository $feature): JsonResponse
    {
        $tipos = [];
        // Obtener el objeto ClassMetadata de la entidad
        $classMetadata = $feature->getEnum();
        preg_match("/^enum\(\'(.*)\'\)$/", $classMetadata, $matches);

        if (count($matches) > 1) {
            $allowedValues = explode("','", $matches[1]);
            // Ahora puedes hacer lo que quieras con los valores permitidos, por ejemplo, imprimirlos
            foreach ($allowedValues as $value) {
                $tipos[] = $value;
            }
        }
        return $this->json($tipos);
    }

    #[Route('/extras', name: 'app_extras', methods: 'GET')]
    public function extras(FeatureRepository $feature): JsonResponse
    {

        return $this->json(
            $feature->findAll(),
            200,
            [],
            [AbstractNormalizer::IGNORED_ATTRIBUTES => ['houses']]
        );
    }
}
