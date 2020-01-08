<?php

namespace App\Controller\Home;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use App\Form\UserType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\SettingRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user", name="home_user")
 */
class HomeUserController extends AbstractController
{
    /**
     * @Route("/", name="_profile")
     */
    public function index(Request $request, SettingRepository $settingRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $img = $request->files->get('user')['image'];
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
                $user->setImage($newName);

            } else {
                $user->setImage($this->getUser()->getImage());
            }

            if ($request->request->get('password') != "") {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $request->request->get('password')
                    )
                );
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('home_user_profile');
        }
        return $this->render('home/User/index.html.twig', [
            'title' => 'Your Profile',
            'form' => $form->createView(),
            'setting' => $settingRepository->findAll()[0],

        ]);
    }

    /**
     * @Route("/posts", name="post_index")
     */
    public function postslist(Request $request, SettingRepository $settingRepository, PostRepository $postRepository)
    {
        $posts = $postRepository->findBy(['created_by' => $this->getUser()]);
        return $this->render('home/User/post.html.twig', [
            'title' => 'Your Posts List',
            'posts' => $posts,
            'setting' => $settingRepository->findAll()[0],

        ]);
    }

    /**
     * @Route("/posts/new", name="post_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function newPost(Request $request, SettingRepository $settingRepository): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new DateTime());
            $post->setUpdatedAt(new DateTime());
            $post->setCreatedBy($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('home_userpost_index');
        }

        return $this->render('home/User/new-post.html.twig', [
            'title' => 'Create New Post',
            'post' => $post,
            'form' => $form->createView(),
            'setting' => $settingRepository->findAll()[0],

        ]);
    }

    /**
     * @Route("/posts/{id}/edit", name="post_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function editPost(Request $request, SettingRepository $settingRepository, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('home/User/post-edit.html.twig', [
            'title' => 'Edit Post',
            'post' => $post,
            'form' => $form->createView(),
            'setting' => $settingRepository->findAll()[0],

        ]);
    }

    /**
     * @Route("/posts/{id}", name="post_show", methods={"GET"})
     */
    public function showPost(Post $post, SettingRepository $settingRepository): Response
    {
        return $this->render('home/User/post-show.html.twig', [
            'title' => $post->getTitle(),
            'post' => $post,
            'setting' => $settingRepository->findAll()[0],

        ]);
    }

    /**
     * @Route("/posts/{id}", name="post_delete", methods={"DELETE"})
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function deletePost(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home_userpost_index');
    }

    /**
     * @Route("/comments/", name="comment_index", methods={"GET"})
     */
    public function comments(CommentRepository $commentRepository, SettingRepository $settingRepository): Response
    {
        return $this->render('home/User/comment.html.twig', [
            'title' => 'Your Comments List',
            'comments' => $commentRepository->findAll(),
            'setting' => $settingRepository->findAll()[0],

        ]);
    }


    /**
     * @Route("/comments/{id}", name="comment_show", methods={"GET"})
     */
    public function showcomment(Comment $comment, SettingRepository $settingRepository): Response
    {
        return $this->render('home/User/comment-show.html.twig', [
            'title' => $comment->getTitle(),
            'comment' => $comment,
            'setting' => $settingRepository->findAll()[0],

        ]);
    }

    /**
     * @Route("/comments/{id}/edit", name="comment_edit", methods={"GET","POST"})
     */
    public function editcomment(Request $request, SettingRepository $settingRepository, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home_usercomment_index');
        }

        return $this->render('home/User/comment-edit.html.twig', [
            'title' => 'Edit Comment',
            'comment' => $comment,
            'form' => $form->createView(),
            'setting' => $settingRepository->findAll()[0],
        ]);
    }

    /**
     * @Route("/comments/{id}", name="comment_delete", methods={"DELETE"})
     * @param Request $request
     * @param SettingRepository $settingRepository
     * @param Comment $comment
     * @return Response
     */
    public function deletecomment(Request $request, SettingRepository $settingRepository, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home_usercomment_index');
    }
}
