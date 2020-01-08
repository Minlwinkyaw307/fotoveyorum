<?php

namespace App\Controller;

use App\Entity\PostReport;
use App\Form\PostReportType;
use App\Repository\PostReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post_report")
 */
class PostReportController extends AbstractController
{
    /**
     * @Route("/", name="post_report_index", methods={"GET"})
     */
    public function index(PostReportRepository $postReportRepository): Response
    {
        return $this->render('admin/post_report/index.html.twig', [
            'post_reports' => $postReportRepository->findAll(),
            'title' => 'Post Report List',
        ]);
    }

    /**
     * @Route("/new", name="post_report_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $postReport = new PostReport();
        $form = $this->createForm(PostReportType::class, $postReport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($postReport);
            $entityManager->flush();

            return $this->redirectToRoute('post_report_index');
        }

        return $this->render('admin/post_report/new.html.twig', [
            'post_report' => $postReport,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/{id}", name="post_report_show", methods={"GET"})
     */
    public function show(PostReport $postReport): Response
    {
        return $this->render('admin/post_report/show.html.twig', [
            'post_report' => $postReport,
            'title' => $postReport->getPost()->getTitle(),

        ]);
    }

//    /**
//     * @Route("/{id}/edit", name="post_report_edit", methods={"GET","POST"})
//     */
//    public function edit(Request $request, PostReport $postReport): Response
//    {
//        $form = $this->createForm(PostReportType::class, $postReport);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('post_report_index');
//        }
//
//        return $this->render('admin/post_report/edit.html.twig', [
//            'post_report' => $postReport,
//            'form' => $form->createView(),
//        ]);
//    }

    /**
     * @Route("/{id}", name="post_report_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PostReport $postReport): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postReport->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($postReport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_report_index');
    }
}
