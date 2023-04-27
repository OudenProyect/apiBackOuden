<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post', methods: 'POST')]
    public function index(Request $request): JsonResponse
    {
        $datos = [];
        try {
            // IMAGEN
            $uploadedFiles = $request->files->all(); // Obtener el archivo cargado del formulario

            if (!$uploadedFiles) {
                throw new FileException('No se ha seleccionado ningún archivo');
            }

            foreach ($uploadedFiles as $fieldName => $files) {

                if($files->getClientOriginalExtension() != 'jpg' || $files->getClientOriginalExtension() != 'png' ){
                    throw new FileException('El archivo no es una imagen JPG o PNG');

                }

                $uniqueId = uniqid('', true); // Usar el segundo argumento de uniqid() para obtener una cadena más única
                $randomBytes = random_bytes(10); // Generar 10 bytes aleatorios
                $fileName = $uniqueId . '_' . bin2hex($randomBytes) . '.' . $files->guessExtension(); // Usar uniqid() y random_bytes() en combinación

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
            throw new \Exception('Ha ocurrido un error al subir el archivo'.$e->getMessage());
        }

        try{
            $itulo = $request->get('titulo');
            $precio = $request->get('precio');
            $descripcionPortada = $request->get('descripcionPortada');
            $descripcion = $request->get('descripcion');
            $tipo = $request->get('tipo');
            $bedrooms = $request->get('bedrooms');
            $bathroom = $request->get('bathroom');
            $flats = $request->get('flats');
            $m2 = $request->get('m2');
            $m2util = $request->get('m2util');
            $extras = $request->get('extras');
            $ciudades = $request->get('ciudades');

        }catch(Exception $e){
            $datos = $e->getMessage();

        }

        return $this->json($datos);
    }
}
