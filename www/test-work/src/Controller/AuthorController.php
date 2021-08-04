<?php

namespace App\Controller;

//use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use App\Services\Locale;
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
        $author->translate(Locale::RU)->setName($json['ruName']);
        $author->translate(Locale::EN)->setName($json['enName']);
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
     *     name="api_get_author",
     *     requirements={"_locale": "en|ru"},
     *     methods={"GET"}
     * )
     */
    public function getAuthor(int $id, Request $request): JsonResponse
    {

        $repo = $this->getDoctrine()->getManager()->getRepository(Author::class);
        $author = $repo->find($id);

        if (!$author) {
            return $this->json(['status' => false, 'error' => 'Ошибка', 'error_text' => 'Автор не найден']);
        }


        return $this->json($author->toArray($request->getLocale()));
    }
}
