<?php

namespace App\Controller;

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
        $uploadedFiles = $request->files->all(); // Obtener el archivo cargado del formulario
        try {
            foreach ($uploadedFiles as $fieldName => $files) {
                $uniqueId = uniqid('', true); // Usar el segundo argumento de uniqid() para obtener una cadena más única
                $randomBytes = random_bytes(10); // Generar 10 bytes aleatorios
                $fileName = $uniqueId . '_' . bin2hex($randomBytes) . '.' . $files->guessExtension(); // Usar uniqid() y random_bytes() en combinación

                dd($fileName);

                $files->move(
                    $this->getParameter('image_dir'), // Directorio de destino configurado en config/services.yaml
                    $files->$fileName
                );
                $datos[] = [
                    'img' => $fieldName,
                    'original_name' => $files->getClientOriginalName(),
                    'mime_type' => $files->getClientMimeType(),
                    'fileName' => $fileName
                ];
            }

            // Mover el archivo al directorio de destino

        } catch (FileException $e) {
            // Manejar errores de carga de archivos
            throw new \Exception('Ha ocurrido un error al subir el archivo');
        }

        // Hacer algo con el nombre de archivo, como guardarlo en la base de datos
        // ...

        // Retornar una respuesta adecuada
        return $this->json($datos);
    }
}
