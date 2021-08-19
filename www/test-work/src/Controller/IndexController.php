<?php

namespace App\Controller;

use App\Entity\Author;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        return $this->render('index/index.html.twig', []);
    }

    /**
     * @Route( "/author/create/ui",  name="create_author")
     */
    public function createAuthor(): Response
    {
        $authorForm = $this->createFormBuilder()
            ->add('ru_name', TextType::class, ['label' => 'ФИО на русском'])
            ->add('en_name', TextType::class, ['label' => 'ФИО на английском'])
            ->getForm();

        $jsonForm = $this->createFormBuilder()
            ->add('json', TextareaType::class,
                [
                    'label' => false,
                    'attr' => ['id' => 'json-area', 'rows' => 5]
                ]
            )
            ->getForm();

        return $this->render('index/create_author.html.twig', [
            'author_form' => $authorForm->createView(),
            'json_form' => $jsonForm->createView()
        ]);
    }

    /**
     * @Route( "/book/create/ui",  name="create_book")
     */
    public function createBook(): Response
    {
        $authorForm = $this->createFormBuilder()
            ->add('ru_name', TextType::class, ['label' => 'Название русском'])
            ->add('en_name', TextType::class, ['label' => 'Название на английском'])
            ->add('author_id_1', IntegerType::class, ['data' => 0, 'label' => 'ID (номер) первого автора'])
            ->add('author_id_2', IntegerType::class, ['data' => 0, 'label' => 'ID (номер) второго автора'])
            ->add('author_id_3', IntegerType::class, ['data' => 0, 'label' => 'ID (номер) третьего автора'])
            ->getForm();

        $jsonForm = $this->createFormBuilder()
            ->add('json', TextareaType::class,
                [
                    'label' => false,
                    'attr' => ['id' => 'json-area', 'rows' => 5]
                ]
            )
            ->getForm();

        return $this->render('index/create_book.html.twig', [
            'author_form' => $authorForm->createView(),
            'json_form' => $jsonForm->createView()
        ]);
    }

    /**
     * @Route("/info", name="php_info")
     */
    public function info()
    {
        phpinfo();
        exit();
    }
}
