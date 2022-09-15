<?php

namespace App\Controller\Base;

use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="_")
 */
class BaseController extends AbstractController {

    protected ManagerRegistry $doctrine;
    protected SerializerInterface $serializer;

    protected string $entity = '';

    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
        $serializer = SerializerBuilder::create()
            ->setSerializationContextFactory(function () {
                return SerializationContext::create()
                    ->enableMaxDepthChecks();
            })
            ->build();
        $this->serializer = $serializer;
    }
}
