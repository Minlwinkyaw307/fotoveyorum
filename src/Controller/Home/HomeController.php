<?php

namespace App\Controller\Home;

use App\Entity\Category;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\SettingRepository;
use App\Security\AppCustomAuthenticator;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Bridge\Google\Smtp\GmailTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository, CategoryRepository $categoryRepository, SettingRepository $settingRepository)
    {
        return $this->render('home/index.html.twig', [
            'title' => 'FotoGrapher',
            'sliders' => $postRepository->findBy([], ['title' => 'DESC'], 3),
            'posts' => $postRepository->findBy([], ['updated_at' => 'ASC'], 6),
            'popular' => $postRepository->findBy([], ['view' => 'DESC'])[0],
            'others' => $postRepository->findBy([], ['view' => 'ASC'], 3),
            'setting' => $settingRepository->findAll()[0],
            'categories' => $categoryRepository->findBy(['parentid' => null]),
        ]);
    }

    /**
     * @Route("/register", name="register_new_user")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param AppCustomAuthenticator $authenticator
     * @return Response
     * @throws Exception
     */
    public function registerNewUser(Request $request, CategoryRepository $categoryRepository, SettingRepository $settingRepository, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppCustomAuthenticator $authenticator)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
                $user->setRoles($request->request->get('user')['roles']);
                $user->setStatus(1);
                $user->setCreatedAt(new DateTime());
                $user->setUpdatedAt(new DateTime());
            }
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('home/register.html.twig', [
            'title' => "Register | Join Us",
            'form' => $form->createView(),
            'setting' => $settingRepository->findAll()[0],
            'categories' => $categoryRepository->findBy(['parentid' => null]),
        ]);
    }

    /**
     * @Route("/about", name="about")
     * @param SettingRepository $settingRepository
     * @return Response
     */
    public function about(PostRepository $postRepository, CategoryRepository $categoryRepository, SettingRepository $settingRepository)
    {
        return $this->render('home/about/index.html.twig', [
            'title' => 'Blogger',
            'setting' => $settingRepository->findAll()[0],
            'popular' => $postRepository->findBy([], ['view' => 'DESC'])[0],
            'others' => $postRepository->findBy([], ['view' => 'ASC'], 3),
            'categories' => $categoryRepository->findBy(['parentid' => null]),
        ]);

    }

    /**
     * @Route("/references", name="references")
     * @param SettingRepository $settingRepository
     * @return Response
     */
    public function references(SettingRepository $settingRepository, CategoryRepository $categoryRepository, PostRepository $postRepository)
    {
        return $this->render('home/reference/index.html.twig', [
            'title' => 'Blogger',
            'setting' => $settingRepository->findAll()[0],
            'popular' => $postRepository->findBy([], ['view' => 'DESC'])[0],
            'others' => $postRepository->findBy([], ['view' => 'ASC'], 3),
            'categories' => $categoryRepository->findBy(['parentid' => null]),
        ]);

    }

    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @param SettingRepository $settingRepository
     * @param PostRepository $postRepository
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function contact(Request $request, CategoryRepository $categoryRepository, SettingRepository $settingRepository, PostRepository $postRepository)
    {
        $setting = $settingRepository->findAll()[0];
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $message->setIp($_SERVER['REMOTE_ADDR']);
            $message->setCreatedAt(new DateTime());
            $message->setUpdatedAt(new DateTime());
            $message->setStatus('New');
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($message);
            $entityManger->flush();
            $this->addFlash('success', "You message has been sent");
            //dump($setting->getSmtpemail());
            $email = (new Email())
                ->from($setting->getSmtpemail())
                ->to($message->getEmail())
                ->subject("FotoGrapher | Your Request")
                ->html('Dear'. $message->getName() . ',<br>'.
                "<p>We will evaluate your requests and contact you as soon as possible</p>");

            /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
            $transport = new GmailTransport($setting->getSmtpemail(), $setting->getSmtppassword());
            $mailer = new Mailer($transport);
            $sentEmail = $mailer->send($email);

            return $this->redirectToRoute('contact');
        }
        return $this->render('home/contact/index.html.twig', [
            'title' => 'Blogger',
            'setting' => $setting,
            'popular' => $postRepository->findBy([], ['view' => 'DESC'])[0],
            'others' => $postRepository->findBy([], ['view' => 'ASC'], 3),
            'categories' => $categoryRepository->findBy(['parentid' => null]),
        ]);

    }

    /**
     * @Route("/category", name="home_category_index")
     * @param SettingRepository $settingRepository
     * @return Response
     */
    public function categoryIndex(SettingRepository $settingRepository, CategoryRepository $categoryRepository, PostRepository $postRepository)
    {
        return $this->render('home/category/index.html.twig', [
            'title' => 'Catgories Index',
            'setting' => $settingRepository->findAll()[0],
            'posts' => $postRepository->findAll(),
            'popular' => $postRepository->findBy([], ['view' => 'DESC'])[0],
            'others' => $postRepository->findBy([], ['view' => 'ASC'], 3),
            'categories' => $categoryRepository->findBy(['parentid' => null]),
            'category' => null,
        ]);

    }


    /**
     * @Route("/category/{id}", name="home_subcategory_index")
     * @param SettingRepository $settingRepository
     * @return Response
     */
    public function subcategory(Category $categroy, SettingRepository $settingRepository, CategoryRepository $categoryRepository, PostRepository $postRepository)
    {
        return $this->render('home/category/index.html.twig', [
            'title' => 'Catgories Index',
            'setting' => $settingRepository->findAll()[0],
            'posts' => $postRepository->findBy(['category' => $categroy]),
            'popular' => $postRepository->findBy([], ['view' => 'DESC'])[0],
            'others' => $postRepository->findBy([], ['view' => 'ASC'], 3),
            'categories' => $categoryRepository->findBy(['parentid' => null]),
            'category' => $categroy,
        ]);

    }


}
