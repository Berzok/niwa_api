<?php

namespace App\Controller;

use App\Controller\Base\BaseController;
use App\Entity\Folder;
use App\Service\FolderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tree")
 * @IsGranted("IS_AUTHENTICATED")
 */
class TreeController extends BaseController {

    protected string $entity = Folder::class;

    #[Route('/', name: 'tree')]
    public function index(Request $request, FolderService $folderService): Response {
        $repository = $this->doctrine->getRepository(Folder::class);
        $folders = $repository->findAll();
        $data = $folders;

        $root = $repository->find(1);

        $data = $folderService->orgTree($root);

        $json = $this->serializer->serialize($data, 'json');

        return $this->render('tree.html.twig', [
            'data' => $json
        ]);
    }
}
