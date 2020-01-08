<?php

namespace App\Controller\Admin;

use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_admin")
     */
    public function index(PostRepository $postRepository, CommentRepository $commentRepository)
    {
        return $this->render('admin/index.html.twig', [
            'posts' => $postRepository->findBy([], ['updated_at' => 'DESC'], 4),
            'comments' => $commentRepository->findBy([], ['updated_at' => 'DESC'], 4),
        ]);
    }
}
