<?php

namespace App\Controller\Api;

use App\Controller\Base\ApiController;
use App\Entity\Folder;
use App\Form\FolderType;
use App\Service\FolderService;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/folder", name="folder_")
 */
class FolderController extends ApiController {

    protected string $entity = Folder::class;

    /**
     * @param FolderService $folderService
     * @param int $id
     * @return Response
     */
    #[Route('/count')]
    public function count(FolderService $folderService, int $id): Response {
        $folder = $this->doctrine->getRepository(Folder::class)->find($id);
        $count = $folderService->childrensCount($folder);

        return new Response($count);
    }

    /**
     * @param Request $request
     * @param string $path
     * @return Response
     */
    #[Route('/create', name: 'create')]
    public function new(Request $request, string $path = ''): Response {
        $em = $this->doctrine->getManager();
        $folder = new Folder();
        $form = $this->createForm(FolderType::class, $folder, [
            'action' => $this->generateUrl('folder_create')
        ]);
        $parent = $this->doctrine->getRepository(Folder::class)->findOneBy(['name' => $path]);
        if(is_null($parent)){
            $parent = $em->find($this->entity, 1);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $folder = $form->getData();

            $folder->setParent($parent);
            $folder->setDepth($parent->getDepth()+1);

            $em->persist($folder);
            $em->flush();

            return $this->redirectToRoute('index', [
                'path' => $path
            ]);
        }

        return $this->renderForm('folder/new.html.twig', [
            'form' => $form,
            'current_path' => $path
        ]);
    }

    /**
     * @param string $name
     * @return JsonResponse
     */
    #[Route('/name/{name}', name: 'get_by_name')]
    public function getByName(string $name): JsonResponse {
        $repository = $this->doctrine->getRepository($this->entity);
        $data = $repository->findByName($name);

        $json = $this->serializer->serialize($data, 'json');
        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }

    /**
     * @param FolderService $folderService
     * @param string $path
     * @return JsonResponse
     */
    #[Route('/resolve', name: 'resolve_path')]
    public function resolve(FolderService $folderService, string $path = ''): JsonResponse {
        $path = $path ?? 'books';
        $data = $folderService->resolvePath($path);
        $json = $this->serializer->serialize($data, 'json');

        return JsonResponse::fromJsonString($json, Response::HTTP_OK);
    }


    #[Route('/structure', name: 'get_structure')]
    public function getStructure(ManagerRegistry $doctrine, SerializerInterface $serializer): JsonResponse {
        $repository = $doctrine->getRepository(Folder::class);
        $root = $repository->find(1);

        $data = [
            'key' => $root->getId(),
            'name' => $root->getName(),
            'children' => []
        ];

        foreach ($root->getChildrenFolder() as $children) {
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
