<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\BookTranslation;
use App\Services\Locale;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @Route("/book/create",
     *     name="api_create_book",
     *     methods={"POST"}
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $json = json_decode($request->getContent(), true);

        if (is_null($json)) {
            return $this->json(['status' => false, 'status_text' => 'Ошибка', 'error_text' => 'Допущена ошибка в формате JSON-даных.']);
        } elseif (empty($json['ruName']) || empty($json['enName'])) {
            return $this->json(['status' => false, 'status_text' => 'Ошибка', 'error_text' => 'Не указано имя автора на русском или английском языках.']);
        } elseif (empty($json['authors']) || !is_array($json['authors'])) {
            return $this->json(['status' => false, 'status_text' => 'Ошибка', 'error_text' => 'Не корректно указан список авторов']);
        }

        $em = $this->getDoctrine()->getManager();
        $book = new Book();
        $book->translate(Locale::RU)->setName($json['ruName']);
        $book->translate(Locale::EN)->setName($json['enName']);
        $em->persist($book);
        $book->mergeNewTranslations();

        $repo = $this->getDoctrine()->getRepository(Author::class);
        $authorsCount = 0;
        foreach ($json['authors'] as $authorId) {
            $authorId = intval($authorId);
            if (empty($authorId)) {
                continue;
            }

            $author = $repo->find($authorId);

            if (empty($author)) {
                continue;
            }

            $book->addAuthor($author);
            ++$authorsCount;
        }

        if ($authorsCount === 0) {
            return $this->json(['status' => false, 'status_text' => 'Ошибка', 'error_text' => 'Не найдено ни одного автора с такими Id']);
        }

        $em->flush();

        if ($book->getId()) {
            return $this->json(['status' => true, 'status_text' => 'Книга добавлена.', 'id' => $book->getId()]);
        } else {
            return $this->json(['status' => false, 'error_text' => 'Произошел сбой БД...']);
        }
    }

    /**
     * @Route(
     *     "/{_locale}/book/{id}",
     *     name="api_get_book",
     *     requirements={"_locale": "en|ru"},
     *     methods={"GET"}
     * )
     */
    public function getBook(int $id, Request $request): JsonResponse
    {

        $repo = $this->getDoctrine()->getManager()->getRepository(Book::class);
        $book = $repo->find($id);

        if (!$book) {
            return $this->json(['status' => false, 'error' => 'Ошибка', 'error_text' => 'Книга не найдена']);
        }


        return $this->json($book->toArray($request->getLocale()));
    }

    /**
     * @Route(
     *     "/book/search",
     *     name="api_search_book",
     *     methods={"POST"}
     * )
     */
    public function searchBook(Request $request): JsonResponse
    {
        $json = json_decode($request->getContent(), true);

        if (is_null($json)) {
            return $this->json(['status' => false, 'status_text' => 'Ошибка', 'error_text' => 'Допущена ошибка в формате JSON-даных.']);
        } elseif (empty($json['book_name'])) {
            return $this->json(['status' => false, 'status_text' => 'Ошибка', 'error_text' => 'Не указано название книги для поиска']);
        }

        $repo = $this->getDoctrine()->getManager()->getRepository(Book::class);
        $books = $repo->getBooksUsingIndex(trim($json['book_name']));

        return $this->json($books);
    }
}
