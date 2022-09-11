<?php

namespace App\Controller;

use App\Form\Type\ImageUploadType;
use App\Service\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(
        Request $request,
        ImageManager $imageManager
    ): Response {
        $form = $this->createForm(ImageUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $img = $form->get('image')->getData();
            $imgWidth = ($form->get('width')->getData());

            $mimeType = $img->getMimeType();
            if (
                $mimeType == 'image/jpeg' ||
                $mimeType == 'image/jpg'  ||
                $mimeType == 'image/png'  ||
                $mimeType == 'image/gif'  ||
                $mimeType == 'image/webp'
            ) {
              
                $file = $imageManager->upload($img);
                $imageManager->resize($file, $imgWidth);

                $request->getSession()->set('file', $file);

                return $this->file($file);
            } else {
                $this->addFlash('error', 'format de fichier invalide');
                return $this->redirectToRoute('main');
            }
        }

        return $this->render('main/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/clear", name="clear_folder", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function clearFolder(
        ImageManager $imageManager,
        Request $request
    ) {
        $file = $request->getSession()->get('file');
        $imageManager->deleteImage($file);

        return new JsonResponse('ok');
    }
}
