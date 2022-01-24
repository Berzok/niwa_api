<?php

namespace App\Controller;

use App\Entity\Resource;
use Aws\S3\S3Client;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResourceController extends AbstractController {

    /**
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @param SerializerInterface $serializer
     * @return Response
     */
    #[Route('/resource/delete/{id}', name: 'delete_resource')]
    public function upload(int $id, ManagerRegistry $doctrine, SerializerInterface $serializer): Response {
        $em = $doctrine->getManager();
        $resource = $em->find(Resource::class, $id);

        $client = new S3Client([
            'version' => 'latest',
            'region' => 'ams3',
            'endpoint' => $_ENV['DO_ENDPOINT'],
            'credentials' => [
                'key' => $_ENV['DO_KEY_ACCESS'],
                'secret' => $_ENV['DO_SECRET'],
            ],
            'http' => [
                'verify' => false
            ]
        ]);

        try {
            $client->deleteObject([
                'Bucket' => $_ENV['DO_SPACE'],
                'Key' => $resource->getFilename(),
            ]);

            $em->remove($resource);
            $em->flush();
        } catch (Exception $e) {
            var_dump($e);
        }

        $json = $serializer->serialize('ok', 'json');
        return new Response($json, Response::HTTP_OK);
    }
}
