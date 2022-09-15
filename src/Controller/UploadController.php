<?php

namespace App\Controller;

use App\Controller\Base\BaseController;
use App\Entity\Folder;
use App\Entity\Resource;
use App\Form\ResourceType;
use App\Service\UploadService;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/upload")
 * @IsGranted("IS_AUTHENTICATED")
 */
class UploadController extends BaseController {

    protected string $entity = Folder::class;

    /**
     * @param Request $request
     * @param UploadService $uploadService
     * @param SluggerInterface $slugger
     * @param ValidatorInterface $validator
     * @param string|null $path
     * @return Response
     * @throws Exception
     */
    #[Route('/', name: 'upload')]
    public function upload(Request $request, UploadService $uploadService, SluggerInterface $slugger, ValidatorInterface $validator, ?string $path): Response {
        $em = $this->doctrine->getManager();
        $resource = new Resource();
        $form = $this->createForm(ResourceType::class, $resource, [
            'action' => $this->generateUrl('upload')
        ]);

        $folder = $this->doctrine->getRepository(Folder::class)->findOneBy(['name' => $path]);
        if(is_null($folder)){
            $folder = $em->find($this->entity, 1);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($file) {
                $name = $uploadService->upload($file, $this->getParameter('upload_directory'));

                //Set the filename of the resource to the file name (with an unique id).
                $resource->setFilename($name['new']);

                if (is_null($form->get('name')->getData())) {
                    $resource->setName($name['safe'] . '.' . $file->guessExtension());
                }

                $folder = $this->doctrine->getRepository(Folder::class)->findOneBy(['name' => $form['folder']->getData()]);
                if(is_null($folder)){
                    $folder = $em->find($this->entity, 1);
                }
            }

            $resource->setFolder($folder);

            // ... persist the $product variable or any other work
            $em->persist($resource);
            $em->flush();

            return $this->redirect('/public/' . $path);
        }

        return $this->renderForm('resource/upload.html.twig', [
            'form' => $form,
            'current_path' => $path
        ]);
    }
}
