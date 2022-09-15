<?php

namespace App\Controller;

use App\Controller\Base\BaseController;
use App\Entity\Folder;
use App\Entity\Tag;
use App\Form\TagType;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * @IsGranted("IS_AUTHENTICATED")
 */
class AdminController extends BaseController {

    protected string $entity = Folder::class;


    #[Route('/tags', name: 'tags')]
    public function tags(Request $request, string $path = ''): Response {
        $repository = $this->doctrine->getRepository(Tag::class);
        $tags = $repository->findAll();

        $context = new SerializationContext();
        $context->setSerializeNull(true);

        $tags = $this->serializer->serialize($tags, 'json', $context);

        return $this->render('admin/tags/table.html.twig', [
            'tags' => $tags
        ]);
    }

    #[Route(name: 'tag')]
    public function tag(Request $request): Response {
        $em = $this->doctrine->getManager();
        $parameters = $request->request->all('tag');
        $id = $parameters['id'] ?? null;

        if($id){
            $tag = $em->find(Tag::class, $id);
        } else{
            $tag = new Tag();
        }

        $form = $this->createForm(TagType::class, $tag, [
            'action' => $this->generateUrl('tag')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... persist the $tag variable
            if(is_null($tag->getId())){
                $em->persist($tag);
            }
            $em->flush();

            return $this->redirectToRoute('tags');
        }

        return $this->renderForm('admin/tags/form.html.twig', [
            'form' => $form,
        ]);
    }

}
