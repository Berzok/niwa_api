<?php

namespace App\Controller;

use App\Entity\Image;
use App\Service\PixivAPI;
use Curl\Curl;
use Exception;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use JMS\Serializer\SerializerInterface;
use PixivAppAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\Annotation\Route;

class PixivController extends AbstractController {

    private PixivAPI $pixiv;

    public function __construct(PixivAPI $pixiv) {
        $this->pixiv = $pixiv;
    }

    /**
     * @throws Exception
     */
    #[Route('/pixiv/get/{id}', name: 'pixiv_details')]
    public function getDetails(int $id, SerializerInterface $serializer): Response {
        $this->pixiv->refreshAccessToken($this->getParameter('pixiv.refresh'));
        $data = $this->pixiv->illust_detail($id);
        $data = $data['illust'];

        $json = $serializer->serialize($data, 'json');

        return new Response($json, Response::HTTP_OK, ['content-type' => 'application/json']);
    }

    /**
     * @throws Exception
     */
    #[Route('/pixiv/original', name: 'pixiv_source')]
    public function getSource(Request $request, SerializerInterface $serializer, PixivAPI $pixiv): Response {
        $image = $request->toArray()['image'];
        $uri = $image['url'];

        $array = explode('.', $uri);
        $extension = array_pop($array);

        $pixiv->init();

        $pixiv->refreshAccessToken($this->getParameter('pixiv.refresh'));
        $image = $pixiv->fetch_source($uri);
        $data = $pixiv->imageToBase64($image, $extension);

        //$pixiv = new PixivAppAPI;
        //$pixiv->refreshAccessToken($this->getParameter('pixiv.refresh'));
        //$data = $pixiv->illust_detail(explode('/', $request->toArray()['image']['source'])[count(explode('/', $request->toArray()['image']['source'])) - 1]);
        //$data = $this->pixiv->illust_detail(explode('/', $request->toArray()['image']['source'])[count(explode('/', $request->toArray()['image']['source'])) - 1]);

        $json = $serializer->serialize($data, 'json');

        return new Response($json, Response::HTTP_OK, ['content-type' => 'application/json']);
    }


    #[Route('/pixiv/image_b64', name: 'pixiv_base64')]
    public function getBase64(Request $request, SerializerInterface $serializer, PixivAPI $pixiv): Response {
        $url = $request->toArray()['url'];

        $array = explode('.', $url);
        $extension = array_pop($array);

        $pixiv->init();

        $pixiv->refreshAccessToken($this->getParameter('pixiv.refresh'));
        $image = $pixiv->fetch_source($url);
        $data = $pixiv->imageToBase64($image, $extension);

        $json = $serializer->serialize($data, 'json');

        return new Response($json, Response::HTTP_OK, ['content-type' => 'application/json']);
    }

    /**
     * @throws ImagickException
     */
    #[Route('/pixiv/download', name: 'pixiv_upload')]
    public function download(Request $request, SerializerInterface $serializer): Response {
        $path = './../public/uploads/';
        //return new Response('<pre>' . print_r($params) . '</pre>');

        $url = $request->toArray()['url'];
        $filename = $request->toArray()['filename'];
        $extension = $request->toArray()['extension'];

        $fullpath = $path . $filename . $extension;

        $this->pixiv->init();

        $this->pixiv->refreshAccessToken($this->getParameter('pixiv.refresh'));
        $image = $this->pixiv->fetch_source($url);

        $f = fopen($fullpath, 'w');
        fwrite($f, $image);
        fclose($f);


        $info = getimagesize($fullpath);

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($fullpath);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($fullpath);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($fullpath);
        } else {
            die('Unknown image file format');
        }

        //compress and save file to jpg
        imagejpeg($image, $fullpath, 80);


        $json = $serializer->serialize('ok', 'json');

        return new Response($json, Response::HTTP_OK, ['content-type' => 'application/json']);
    }

}
