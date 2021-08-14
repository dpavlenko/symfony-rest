<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;


/**
 * Author
 *
 * @ORM\Table(name="author")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass=AuthorRepository::class)
 */
class Author implements TranslatableInterface
{
    use TranslatableTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Book", inversedBy="authors")
     * @ORM\JoinTable(name="author_book",
     *   joinColumns={
     *     @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="book_id", referencedColumnName="id")
     *   }
     * )
     */
    private $books;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->contains($book)) {
            $this->books->removeElement($book);
            $book->removeAuthor($this);
        }

        return $this;
    }

    public function toArray($locale): array
    {
        return [
            'Id' => $this->getId(),
            'Name' => $this->translate($locale)->getName()
        ];

    }
}
