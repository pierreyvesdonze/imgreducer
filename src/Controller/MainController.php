<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageUploadType;
use App\Repository\ImageRepository;
use App\Service\ImageManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

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

            // Check Mime Type
            $mimeType = $img->getMimeType();
            if (
                $mimeType == 'image/jpeg' ||
                $mimeType == 'image/jpg'  ||
                $mimeType == 'image/png'  ||
                $mimeType == 'image/gif'  ||
                $mimeType == 'image/webp'
            ) {

                // Upload & resize file
                $file = $imageManager->upload($img);
                $imageManager->resize($file, $imgWidth);

                //$request->getSession()->set('file', $file);
                //Create and store Entity
                $imgEntity = new Image;
                $imgEntity->setPathName($file);
                $this->em->persist($imgEntity);
                $this->em->flush();

                return $this->redirectToRoute('download_img', [
                    'imgId' => $imgEntity->getId()
                ]);
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
     * @Route("/download/{imgId}", name="download_img", methods={"GET", "POST"})
     */
    public function downloadPage(
        $imgId,
        ImageRepository $imageRepository,
        Request $request
    ) {
        $img = $imageRepository->findOneBy([
            'id' => $imgId
        ]);

        $file = $img->getPathName();

        if ($request->isMethod('POST')) {
            return $this->file($file);
        }

        return $this->render('main/download.html.twig', [
            'img' => $img
        ]);
    }

    /**
     * @Route("/clear/{fileId}", name="clear_img", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function clearImg(
        $fileId,
        ImageManager $imageManager,
        ImageRepository $imageRepository
    ) {
        $file = $imageRepository->findOneBy([
            'id' => $fileId
        ]);

        $imageManager->deleteImage($file->getPathName());
        $this->em->remove($file);
        $this->em->flush();
        
        return new JsonResponse('ok');
    }
}
