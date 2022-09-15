<?php

namespace App\Controller\Api;

use App\Controller\Base\ApiController;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", name="user_")
 */
class UserController extends ApiController {

    protected string $entity = User::class;


}
