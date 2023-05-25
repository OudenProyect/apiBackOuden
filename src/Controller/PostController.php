<?php

namespace App\Controller;

use App\Entity\House;
use App\Entity\Image;
use App\Entity\Publication;
use App\Repository\CompanyRepository;
use App\Repository\FeatureRepository;
use App\Repository\HouseRepository;
use App\Repository\ImageRepository;
use App\Repository\LocationServicesRepository;
use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post', methods: 'POST')]
    public function index(Request $request, HouseRepository $houserepo, CompanyRepository $company, FeatureRepository $feact, LocationServicesRepository $service, PublicationRepository $pos, ImageRepository $imgrepo): JsonResponse
    {
        $datos = [];
        try {
            // IMAGEN
            $uploadedFiles = $request->files->all(); // Obtener el archivo cargado del formulario
            if (!$uploadedFiles) {
                throw new FileException('No se ha seleccionado ningún archivo');
            }

            // post

            // buscamos empresa
            $empresa = $company->find($request->get('empresa'));

            if (!$empresa) {
                throw new Exception('Error codigo de empresa', 400);
            }

            // crear casa
            $titulo = $request->get('titulo');
            $precio = $request->get('precio');
            $descripcionPortada = $request->get('descripcionPortada');
            $descripcion = $request->get('descripcion');
            $tipo = $request->get('tipo');
            $bedrooms = $request->get('bedrooms');
            $bathroom = $request->get('bathroom');
            $flats = $request->get('flats');
            $m2 = $request->get('m2');
            $m2util = $request->get('m2util');

            // extras
            $jardin = $request->get('garden');
            $heating = $request->get('heating');
            $balcon = $request->get('balcony');
            $swimmingPool = $request->get('swimmingPool');
            $parking = $request->get('parking');
            $chimney = $request->get('chimney');
            $storageroom = $request->get('storage_room');

            //locations
            $Barcelona = $request->get('Barcelona');
            $Girona = $request->get('Girona');

            $house = new House();
            $house->setType($tipo);
            $house->setNBedrooms($bedrooms);
            $house->setToilets($bathroom);
            $house->setPrice($precio);
            $house->setUsefulLivinArea($m2util);
            // el metro de toda la casa
            $house->setBuildedSurface($m2);
            $house->setFloors($flats);

            //añadimos las caracteristicas
            if ($parking) {
                $house->addFeature($feact->find($parking));
            }
            if ($heating) {
                $house->addFeature($feact->find($heating));
            }
            if ($balcon) {
                $house->addFeature($feact->find($balcon));
            }
            if ($swimmingPool) {
                $house->addFeature($feact->find($swimmingPool));
            }
            if ($jardin) {
                $house->addFeature($feact->find($jardin));
            }
            if ($chimney) {
                $house->addFeature($feact->find($chimney));
            }
            if ($storageroom) {
                $house->addFeature($feact->find($storageroom));
            }

            //añadimos servicios

            if ($Barcelona && $service->findBy(['name' => $Barcelona])) {
                $locat = $service->findBy(['name' => $Barcelona]);
                $house->addAreaService($locat[0]);
            }

            if ($Girona && $service->findBy(['name' => $Girona])) {
                $locat = $service->findBy(['name' => $Girona]);
                $house->addAreaService($locat[0]);
            }

            // $houserepo->save($house, true);

            // crear publicacion
            $publicacion = new Publication();
            $publicacion->setTitle($titulo);
            $publicacion->setDescription($descripcionPortada);
            $publicacion->setDetails($descripcion);
            $publicacion->setIdCompany($empresa);
            $publicacion->setHouse($house);

            foreach ($uploadedFiles as $fieldName => $files) {

                $uniqueId = uniqid('', true); // Usar el segundo argumento de uniqid() para obtener una cadena más única
                $randomBytes = random_bytes(10); // Generar 10 bytes aleatorios
                $fileName = $uniqueId . '_' . bin2hex($randomBytes) . '.' . $files->guessExtension(); // Usar uniqid() y random_bytes() en combinación

                $image = new Image();
                $image->setName($fileName);
                $image->setPublication($publicacion);

                date_default_timezone_set('Europe/Madrid');

                $format = date('Y-m-d');

                $hora = date('H:i:s');
                $date = new DateTimeImmutable($format, new DateTimeZone('Europe/Madrid'));
                $h = new DateTimeImmutable($hora, new DateTimeZone('Europe/Madrid'));

                $hora = new \DateTimeImmutable();

                $publicacion->setDate($date);
                $publicacion->setHour($h);

                $publicacion->addImage($image);

                $pos->save($publicacion, true);
                $imgrepo->save($image, true);

                $files->move(
                    $this->getParameter('image_dir'), // Directorio de destino configurado en config/services.yaml
                    $fileName
                );

                $datos[] = [
                    'img' => $fieldName,
                    'original_name' => $files->getClientOriginalName(),
                    'mime_type' => $files->getClientMimeType(),
                    'fileName' => $fileName
                ];
            }
        } catch (FileException $e) {
            // Manejar errores de carga de archivos
            throw new \Exception($e->getMessage());
        }



        return $this->json($datos);
    }

    #[Route('/api/favorite', name: 'app_favorit', methods: 'POST')]
    public function addFavorite(
        Request $id,
        PublicationRepository $pub,
        TokenStorageInterface $token,
        UserRepository $ser
    ) {
        $message = 'Añaddido a favoritos';
        try {
            $i = json_decode($id->getContent())->id;
            $email = $token->getToken()->getUserIdentifier();
            $user = $ser->findOneBy(['email' => $email]);
            $publicacion = $pub->find($i);
            $user->addFavoritPublication($publicacion);
            $ser->save($user, true);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return $this->json($i);
    }

    #[Route('/api/remFavorite', name: 'app_favoritremove', methods: 'POST')]
    public function removeFavorite(
        Request $id,
        PublicationRepository $pub,
        TokenStorageInterface $token,
        UserRepository $ser
    ) {
        $message = 'Eliminado de favoritos';
        try {
            $i = json_decode($id->getContent())->id;
            $email = $token->getToken()->getUserIdentifier();
            $user = $ser->findOneBy(['email' => $email]);
            $publicacion = $pub->find($i);
            $user->removeFavoritPublication($publicacion);
            $ser->save($user, true);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return $this->json($message);
    }

    #[Route('/api/favorites', name: 'app_favorites', methods: 'GET')]
    public function favorites(UserRepository $user, TokenStorageInterface $tok)
    {
        $email = $tok->getToken()->getUserIdentifier();
        $e = $user->findOneBy(['email' => $email]);
        $message = 'No hay favoritos';
        $posts = $e->getFavoritPublications()->toArray();
        if (count($posts) > 0) {
            $message = $posts;
        }
        return $this->json($message, 200, [], [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['publications', 'user', 'usersFavorit', 'houses']
        ]);
    }
}
