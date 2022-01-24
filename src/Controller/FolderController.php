<?php

namespace App\Controller;

use App\Entity\Folder;
use Aws\S3\S3Client;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FolderController extends AbstractController {

    #[Route('/folders', name: 'list_folders')]
    public function getAll(Request $request, ManagerRegistry $doctrine, SerializerInterface $serializer): Response {
        $repository = $doctrine->getRepository(Folder::class);
        $data = $repository->findAll();

        $json = $serializer->serialize($data, 'json');
        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }

    #[Route('/folder/{id}', name: 'get_folder_content', requirements: ['id' => '.+'])]
    public function getFolderContent(ManagerRegistry $doctrine, SerializerInterface $serializer, int $id = 1): Response {
        $repository = $doctrine->getRepository(Folder::class);
        $folder = $repository->find($id);

        if(is_null($folder)){
            return new Response('not found', Response::HTTP_NOT_FOUND);
        }

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

        $objects = $client->listObjects([
            'Bucket' => 'niwa',
        ]);


        foreach ($folder->getContent() as $r) {
            $cmd = $client->getCommand('GetObject', [
                'Bucket' => 'niwa',
                'Key' => $r->getFilename()
            ]);

            $request = $client->createPresignedRequest($cmd, '+25 minutes');
            $presignedUrl = (string)$request->getUri();

            $r->url = $presignedUrl;
        }

        $json = $serializer->serialize($folder, 'json');
        //return new JsonResponse(json_encode($json), Response::HTTP_OK);

        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }


    #[Route('/folders/structure', name: 'get_structure')]
    public function getStructure(ManagerRegistry $doctrine, SerializerInterface $serializer){
        $repository = $doctrine->getRepository(Folder::class);
        $root = $repository->find(1);

        $data = [
            'key' => $root->getId(),
            'name' => $root->getName(),
            'children' => []
        ];

        foreach ($root->getChildrenFolder() as $children){
            $data['children'][] = [
                'key' => $children->getId(),
                'name' => $children->getName(),
                'children' => []
            ];
        }

        $json = $serializer->serialize($data, 'json');
        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }
}
