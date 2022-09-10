<?php

namespace App\Controller;

use App\Form\Type\ImageUploadType;
use App\Service\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_upload')]
    public function index(
        Request $request,
        ImageManager $imageManager
    ): Response {
        $form = $this->createForm(ImageUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('image')->getData();

            if ($photo) {
                $imageManager->upload($photo);

                $this->addFlash('success', 'Image uploadée !');
            }
        }

        return $this->render('main/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
