<?php

namespace App\Controller;

use App\Controller\Base\BaseController;
use App\Entity\Folder;
use App\Service\FolderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 * @IsGranted("IS_AUTHENTICATED")
 */
class UserController extends BaseController {

    protected string $entity = Folder::class;

    #[Route('', name: 'profile')]
    public function index(Request $request): Response {
        return $this->render('user/index.html.twig');
    }
}
