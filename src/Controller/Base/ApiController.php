<?php

namespace App\Controller\Base;

use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
#[Route('/api', name: 'api_')]
class ApiController extends BaseController {

    protected ManagerRegistry $doctrine;
    protected SerializerInterface $serializer;

    protected string $entity = '';

    /**
     * @return JsonResponse
     */
    #[Route('/all', name: 'get_all')]
    public function getAll(): Response {
        $data = $this->doctrine->getRepository($this->entity)->findAll();

        $context = new SerializationContext();
        $context->setSerializeNull(true);

        $json = $this->serializer->serialize($data, 'json', $context);
        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_by_id', requirements: ['id' => '\d+'])]
    public function get(int $id): JsonResponse {
        $data = $this->doctrine->getManager()->find($this->entity, $id);

        $json = $this->serializer->serialize($data, 'json');
        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/delete/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(int $id): Response {
        $em = $this->doctrine->getManager();
        $data = $em->find($this->entity, $id);

        $em->remove($data);
        $em->flush();
        return new JsonResponse('ok');
    }
}
