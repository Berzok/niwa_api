<?php

namespace App\Controller\Api;

use App\Entity\Folder;
use App\Entity\Resource;
use Aws\S3\S3Client;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController {

    #[Route('/files', name: 'list_files')]
    public function getAll(SerializerInterface $serializer): Response {
        return new Response('');
    }

    /**
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param SerializerInterface $serializer
     * @return Response
     */
    #[Route('/file/upload', name: 'upload_file')]
    public function upload(Request $request, ManagerRegistry $doctrine, SerializerInterface $serializer): Response {
        $em = $doctrine->getManager();

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

        $files = $request->files->get('files');
        $data = [];

        foreach($files as $f){

            try{
                $key = str_replace('_', ' ', $f->getClientOriginalName());
                $folder = $em->find(Folder::class, $request->get('folder'));

                $resource = new Resource();
                $resource->setFolder($folder);
                $resource->setName($key);
                $resource->setFilename($key);

                $client->putObject([
                    'Bucket' => $_ENV['DO_SPACE'],
                    'Key' => $resource->getFilename(), //The Key (filename, it seems))
                    'Body' => $f->getContent(), //The contents of the file
                    'ACL' => 'private',
                    'Metadata' => array(
                        'x-amz-meta-my-key' => 'your-value'
                    )
                ]);

                $em->persist($resource);
                $em->flush();
            } catch (Exception $e){
                var_dump($e);
            }
            /*

            */
            $data[] = $f;
        }

        $json = $serializer->serialize($data, 'json');
        return new Response($json, Response::HTTP_OK);
    }
}
