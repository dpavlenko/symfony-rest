<?php

namespace App\Controller;

//use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Author;
use App\Entity\Book;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author", name="author")
     */
    public function index(): JsonResponse
    {
        $data = [
            'test' => 'OK'
        ];

        return $this->json($data);
    }

    /**
     * @Route(
     *     "/author/create",
     *     name="api_create_author",
     *     methods={"POST"}
     * )
     */
    public function createAuthor(Request $request): JsonResponse
    {
        $json = json_decode($request->getContent(), true);

        if (is_null($json)) {
            return $this->json(['status' => false, 'status_text' => 'Ошибка', 'error_text' => 'Допущена ошибка в формате JSON-даных.']);
        } elseif (empty($json['ruName']) || empty($json['enName'])) {
            return $this->json(['status' => false, 'status_text' => 'Ошибка', 'error_text' => 'Не указано имя автора на русском или английском языках.']);
        }

        $em = $this->getDoctrine()->getManager();
        $author = new Author();
        $author->translate('ru')->setName($json['ruName']);
        $author->translate('en')->setName($json['enName']);
        $em->persist($author);
        $author->mergeNewTranslations();
        $em->flush();

        if ($author->getId($author->getId())) {
            return $this->json(['status' => true, 'status_text' => 'Автор добавлен.', 'id' => $author->getId()]);
        } else {
            return $this->json(['status' => false, 'error_text' => 'Произошел сбой БД...']);
        }
    }

    /**
     * @Route(
     *     "/{_locale}/author/{id}",
     *     name="get_author",
     *     requirements={"_locale": "en|ru"},
     *     methods={"GET"}
     * )
     */
    public function getAuthor(int $id): JsonResponse
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Author::class);

        $author = $repo->find($id);


        $data = [
            "author" => $author->translate('en')->getName()
        ];
        return $this->json($data);
    }
}
