<?php

namespace App\Controller\Api;

use App\Controller\Base\ApiController;
use App\Entity\Tag;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tag", name="tag_")
 */
class TagController extends ApiController {

    protected string $entity = Tag::class;

    #[Route('/match', name: 'match_tags')]
    public function getMatch(Request $request, SerializerInterface $serializer): Response {
        $text = $request->query->get('q');

        $em = $this->doctrine->getManager();
        $query = $em->createQuery('SELECT T FROM App\Entity\Tag T WHERE T.name LIKE :text');
        $query->setParameter('text', '%' . $text . '%');
        $result = $query->getResult();

        $json = $serializer->serialize($result, 'json');

        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }
}
