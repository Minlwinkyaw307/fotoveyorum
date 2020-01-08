<?php

namespace App\Controller\Home;

use App\Entity\Comment;
use App\Entity\CommentReport;
use App\Entity\Post;
use App\Entity\PostReport;
use App\Entity\Setting;
use App\Form\CommentType;
use App\Repository\CommentReportRepository;
use App\Repository\ImageRepository;
use App\Repository\PostReportRepository;
use App\Repository\PostRepository;
use App\Repository\SettingRepository;
use DateTime;
use Exception;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class HomePostController extends AbstractController
{
    /**
     * @Route("/post/{id}", name="home_post")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param SettingRepository $settingRepository
     * @param Post $post
     * @param PostRepository $postRepository
     * @param ImageRepository $imageRepository
     * @return Response
     * @throws Exception
     */
    public function index(\Symfony\Component\HttpFoundation\Request $request,SettingRepository $settingRepository, Post $post, PostRepository $postRepository, ImageRepository $imageRepository)
    {
        $post->setView($post->getView() + 1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();
        $comment = new Comment();
        $comment->setPost($post);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $comment->setIp($_SERVER['REMOTE_ADDR']);
            $comment->setCommentedBy($this->getUser());
            $comment->setCreatedAt(new DateTime());
            $comment->setUpdatedAt(new DateTime());


            $em->persist($comment);
            $em->flush();
            $comment = new Comment();
        }

        return $this->render('home/post/index.html.twig', [
            'title' => $post->getTitle(),
            'post' => $post,
            'form' => $form->createView(),
            'popular' => $postRepository->findBy([], ['view' => 'DESC'])[0],
            'others' => $postRepository->findBy([], ['view' => 'ASC'], 3),
            'setting' => $settingRepository->findAll()[0],
//            'images' => $imageRepository->findBy(['posts' => $post]),
        ]);
    }

    /**
     * @Route("/post/{id}/report", name="report_post")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param Post $post
     * @param PostRepository $postRepository
     * @return RedirectResponse
     * @throws Exception
     */
    public function postreport(\Symfony\Component\HttpFoundation\Request $request, SettingRepository $settingRepository,  Post $post, PostReportRepository $postReportRepository)
    {
        if($postReportRepository->findBy(['reported_by' => $this->getUser(), 'post' => $post]) == NULL) {
            $postReport = new PostReport();
            $postReport->setPost($post);
            $postReport->setReportedBy($this->getUser());
            $postReport->setStatus("New");
            $postReport->setCreatedAt(new DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($postReport);
            $entityManager->flush();
            $this->addFlash('success_post', "Post has been reported");
        }else{
            $this->addFlash('error_post', "You already reported this post");
        }
        return $this->redirectToRoute('home_post', ['id' => $post->getId()]);



    }

    /**
     * @Route("/post/{id}/reportcomment/", name="report_comment")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param Comment $comment
     * @param PostRepository $postRepository
     * @return RedirectResponse
     * @throws Exception
     */
    public function postcomment(\Symfony\Component\HttpFoundation\Request $request,Comment $comment, CommentReportRepository $commentReportRepository)
    {
        if($commentReportRepository->findBy(['reported_by' => $this->getUser(), 'comment' => $comment]) == NULL)
        {
            $commentReport = new CommentReport();
            $commentReport->setComment($comment);
            $commentReport->setReportedBy($this->getUser());
            $commentReport->setStatus("New");
            $commentReport->setCreatedAt(new DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentReport);
            $entityManager->flush();
            $this->addFlash('success_comment', "Comment has been reported");
        }
        else{
            $this->addFlash('error_comment', "You already reported this comment");
        }

        return $this->redirectToRoute('home_post', ['id' => $comment->getPost()->getId()]);



    }
}
