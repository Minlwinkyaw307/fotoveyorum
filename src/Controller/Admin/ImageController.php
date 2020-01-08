<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Post;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/image")
 */
class ImageController extends AbstractController
{
    /**
     * @Route("/", name="image_index", methods={"GET"})
     * @param ImageRepository $imageRepository
     * @return Response
     */
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->render('admin/image/index.html.twig', [
            'images' => $imageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/newpostimage/{id}", name="image_new_post", methods={"GET","POST"})
     * @param Request $request
     * @param Post $post
     * @param ImageRepository $imageRepository
     * @return Response
     */
    public function newPostImage(Request $request, Post $post, ImageRepository $imageRepository): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $img = $request->files->get('image')['image'];
            if ($img) {
                $imgName = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                $newName = $imgName . '-' . uniqid() . '.' . $img->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $img->move(
                        $this->getParameter('uploaded_image'),
                        $newName
                    );
                } catch (FileException $e) {
                    dump("Hata");
                    die();
                }
                $image->setImage($newName);
                $entityManager->persist($image);
                $entityManager->flush();
                $post->setImg($image);
                $entityManager->persist($post);
                $entityManager->flush();
            }
            return $this->redirectToRoute('image_new_post', ['id' => $post->getId()]);
        }
        return $this->render('admin/image/new-post.html.twig', [
            'img' => $image,
            'images' => $imageRepository->findAll(),
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    /**
     * @Route("/updatepostimage/{imgid}/{postid}", name="image_update_post", methods={"GET","POST"})
     * @param Request $request
     * @param $imgid
     * @param $postid
     * @param PostRepository $postRepository
     * @param ImageRepository $imageRepository
     * @return Response
     */
    public function updatepost(Request $request, $imgid, $postid, PostRepository $postRepository, ImageRepository $imageRepository): Response
    {
        $post = $postRepository->findOneBy(['id' => $postid]);
        $post->setImg($imageRepository->findOneBy(['id' => $imgid]));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();
        return $this->redirectToRoute('image_new_post', ['id' => $post->getId()]);
    }


    /**
     * @Route("/new", name="image_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $img = $request->files->get('image')['image'];
            if ($img) {
                $imgName = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                $newName = $imgName . '-' . uniqid() . '.' . $img->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $img->move(
                        $this->getParameter('uploaded_image'),
                        $newName
                    );
                } catch (FileException $e) {
                    dump("Hata");
                    die();
                }
                $image->setImage($newName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($image);
            $entityManager->flush();

            return $this->redirectToRoute('image_index');
        }

        return $this->render('admin/image/new.html.twig', [
            'image' => $image,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="image_show", methods={"GET"})
     * @param Image $image
     * @return Response
     */
    public function show(Image $image): Response
    {
        return $this->render('admin/image/show.html.twig', [
            'image' => $image,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="image_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Image $image
     * @return Response
     */
    public function edit(Request $request, Image $image): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $img = $request->files->get('image')['image'];
            if ($img) {
                $imgName = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                $newName = $imgName . '-' . uniqid() . '.' . $img->guessExtension();
                unlink($this->getParameter('uploaded_image').'/'.$image->getImage());
                try {
                    $img->move(
                        $this->getParameter('uploaded_image'),
                        $newName
                    );
                } catch (FileException $e) {
                    dump("Hata");
                    die();
                }
                $image->setImage($newName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('image_index');
        }

        return $this->render('admin/image/edit.html.twig', [
            'image' => $image,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("deletepostimage/{id}/{postid}", name="image_delete_post", methods={"DELETE"})
     * @param Request $request
     * @param $postid
     * @param Image $image
     * @return Response
     */
    public function deletepostimage(Request $request,$postid, Image $image): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            unlink($this->getParameter('uploaded_image').'/'.$image->getImage());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($image);
            $entityManager->flush();
        }
        return $this->redirectToRoute('image_new_post', ['id'=> $postid]);
    }

    /**
     * @Route("/{id}", name="image_delete", methods={"DELETE"})
     * @param Request $request
     * @param Image $image
     * @return Response
     */
    public function delete(Request $request, Image $image): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            unlink($this->getParameter('uploaded_image').'/'.$image->getImage());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($image);
            $entityManager->flush();
        }
        return $this->redirectToRoute('image_index');
    }
}
