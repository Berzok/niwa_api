<?php

namespace App\Controller;

use App\Controller\Base\BaseController;
use App\Entity\Folder;
use App\Entity\Resource;
use App\Form\ResourceType;
use App\Service\FolderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 * @IsGranted("IS_AUTHENTICATED")
 */
class HomeController extends BaseController {

    protected string $entity = Folder::class;

    #[Route('/', name: 'redir_index')]
    public function toIndex(): Response {
        return $this->redirectToRoute('index');
    }

    #[Route('/public/{path}', name: 'index')]
    public function index(Request $request, FolderService $fService, string $path = ''): Response {
        $baseFolder = $fService->resolvePath($path);
        $childrens = $baseFolder->getChildrenFolder();
        $resources = $baseFolder->getContent();

        $previous = explode('/', $path);
        array_pop($previous);
        $previous = implode($previous);
        return $this->render('public/index.html.twig', [
            'folders' => $childrens,
            'resources' => $resources,
            'current_path' => $path,
            'previous_path' => $previous
        ]);
    }

    #[Route('/download/{path}', name: 'download')]
    public function folder(Request $request, string $path = ''): Response {
        $file = 'uploads/' . $path;
        return new BinaryFileResponse($file);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/details', name: 'resource_details')]
    public function edit(Request $request): Response {
        $em = $this->doctrine->getManager();
        $resource = new Resource();
        $form = $this->createForm(ResourceType::class, $resource, [
            'action' => $this->generateUrl('upload')
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($resource);
            //$em->flush();
        }

        return $this->renderForm('public/partials/details.html.twig', [
            'form' => $form
        ]);
    }
}
