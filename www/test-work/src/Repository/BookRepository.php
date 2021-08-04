<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @return Book[] Returns an array of Book objects
     * Этот запрос не использует индекс! Поэтому работает медленно.
     */
    public function getBooksByName($name)
    {
        return $this->createQueryBuilder('b')
            ->addSelect('translation')
            ->leftJoin('b.translations', 'translation')
            ->andWhere('translation.name LIKE :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->execute();

    }


    public function getBooksUsingIndex($name)
    {
        $connection = $this->getEntityManager()->getConnection();
        $sql = ' SELECT `translatable_id` as `book_id`, `locale` FROM `book_translation` WHERE `name` LIKE :name';
        $stmt = $connection->prepare($sql);
        $stmt->execute(['name' => $name ]);
        $booksIdsAndLocales = $stmt->fetchAllKeyValue();
        $ids = array_keys($booksIdsAndLocales);

        $books = $this->findBy(['id' => $ids]);

        $result = [];
        foreach ($books as $b) {
            $result[]= $b->toArray($booksIdsAndLocales[$b->getId()]);
        }

        return $result;
    }
}
