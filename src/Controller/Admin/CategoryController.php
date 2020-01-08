<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET"})
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'title' => 'Category Index',
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $image = $request->files->get('category')['image'];
            if ($image) {
                $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $newName = $imageName. '-' .uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('uploaded_image'),
                        $newName
                    );
                } catch (FileException $e) {
                    dump("Hata");
                    die();
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $category->setImage($newName);
                $category->setCreatedAt(new \DateTime());
                $category->setUpdatedAt(new \DateTime());

            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('admin/category/new.html.twig', [
            'title' => 'New Category',
            'category' => $category,
            'categories' => $categoryRepository->findBy(['parentid' => NULL]),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     * @param Category $category
     * @return Response
     */
    public function show(Category $category): Response
    {
        return $this->render('admin/category/show.html.twig', [
            'title' => $category->getTitle(),
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Category $category
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $image = $request->files->get('category')['image'];
            if ($image) {
                $imageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $newName = $imageName. '-' .uniqid().'.'.$image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('uploaded_image'),
                        $newName
                    );
                } catch (FileException $e) {
                    dump("Hata");
                    die();
                }

                unlink($this->getParameter('uploaded_image').'/'.$category->getImage());
                $category->setImage($newName);
                $category->setCreatedAt(new \DateTime());
            }
            $category->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'title' => 'Edit Category',
            'category' => $category,
            'categories' => $categoryRepository->findBy(['parentid' => NULL]),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"DELETE"})
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            unlink($this->getParameter('uploaded_image').'/'.$category->getImage());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index');
    }
}
