<?php

namespace App\Controller;

use App\Entity\CommentReport;
use App\Form\CommentReportType;
use App\Repository\CommentReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/comment_report")
 */
class CommentReportController extends AbstractController
{
    /**
     * @Route("/", name="comment_report_index", methods={"GET"})
     */
    public function index(CommentReportRepository $commentReportRepository): Response
    {
        return $this->render('admin/comment_report/index.html.twig', [
            'comment_reports' => $commentReportRepository->findAll(),
            'title' => 'Comment Report List',
        ]);
    }

    /**
     * @Route("/new", name="comment_report_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commentReport = new CommentReport();
        $form = $this->createForm(CommentReportType::class, $commentReport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentReport);
            $entityManager->flush();

            return $this->redirectToRoute('comment_report_index');
        }

        return $this->render('admin/comment_report/new.html.twig', [
            'comment_report' => $commentReport,
            'form' => $form->createView(),
            'title' => 'New Comment Report',
        ]);
    }

    /**
     * @Route("/{id}", name="comment_report_show", methods={"GET"})
     */
    public function show(CommentReport $commentReport): Response
    {
        $commentReport->setStatus("Read");
        $this->getDoctrine()->getManager()->flush();
        return $this->render('admin/comment_report/show.html.twig', [
            'comment_report' => $commentReport,
            'title' => $commentReport->getComment()->getTitle(),
        ]);
    }

//    /**
//     * @Route("/{id}/edit", name="comment_report_edit", methods={"GET","POST"})
//     */
//    public function edit(Request $request, CommentReport $commentReport): Response
//    {
//        $form = $this->createForm(CommentReportType::class, $commentReport);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('comment_report_index');
//        }
//
//        return $this->render('admin/comment_report/edit.html.twig', [
//            'comment_report' => $commentReport,
//            'form' => $form->createView(),
//        ]);
//    }

    /**
     * @Route("/{id}", name="comment_report_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CommentReport $commentReport): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentReport->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentReport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_report_index');
    }
}
