<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
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
        $book->translate('ru')->setName($json['ruName']);
        $book->translate('en')->setName($json['enName']);
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
}
