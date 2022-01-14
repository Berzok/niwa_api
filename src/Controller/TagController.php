<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\TypeTag;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

class TagController extends AbstractController {

    #[Route('/tags', name: 'all_tags')]
    public function getAll(SerializerInterface $serializer): Response {
        $repository = $this->getDoctrine()->getRepository(Tag::class);
        $data = $repository->findAll();

        $json = $serializer->serialize($data, 'json');

        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }


    #[Route('/tags/types', name: 'get_types')]
    public function getTypes(SerializerInterface $serializer): Response {
        $repository = $this->getDoctrine()->getRepository(TypeTag::class);
        $data = $repository->findAll();

        $json = $serializer->serialize($data, 'json');

        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }


    #[Route('/tags/match', name: 'match_tags')]
    public function getMatch(Request $request, SerializerInterface $serializer): Response {
        $text = $request->query->get('q');

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT T FROM App\Entity\Tag T WHERE T.name LIKE :text');
        $query->setParameter('text', '%' . $text . '%');
        $result = $query->getResult();

        $json = $serializer->serialize($result, 'json');

        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }
}
