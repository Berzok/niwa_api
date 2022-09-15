<?php

namespace App\Controller\Api;

use App\Controller\Base\ApiController;
use App\Entity\Resource;
use App\Service\BucketService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/resource", name="resource_")
 */
#[Route("/resource", name: 'resource_')]
class ResourceController extends ApiController {

    protected string $entity = Resource::class;



    /**
     * @param BucketService $bucketService
     * @param int $id
     * @return Response
     */
    #[Route('/delete/{id}', name: 'delete_true', methods: 'DELETE')]
    public function deleteResource(BucketService $bucketService, int $id): Response {
        $em = $this->doctrine->getManager();
        $resource = $em->find(Resource::class, $id);
        //$bucketService->delete($resource);
        return $this->delete($resource->getId());

        //return $this->redirectToRoute('index');
    }
}
