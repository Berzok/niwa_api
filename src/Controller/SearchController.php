<?php

namespace App\Controller;

use App\Controller\Base\BaseController;
use App\Entity\Resource;
use App\Service\FolderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/search", name="search_")
 * @IsGranted("IS_AUTHENTICATED")
 */
#[Route('/search', name: 'search_')]
class SearchController extends BaseController {

    protected string $entity = Resource::class;
}
