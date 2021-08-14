<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;


/**
 * Book
 *
 * @ORM\Table(name="book")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book implements TranslatableInterface
{
    use TranslatableTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Author", mappedBy="books")
     */
    private $authors;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->authors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
            $author->addBook($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->removeElement($author)) {
            // set the owning side to null (unless already changed)
            $author->removeBook($this);
        }

        return $this;
    }

    public function toArray($locale): array
    {
        $authorsArray = [];
        $authorsEntities = $this->getAuthors();
        foreach ($authorsEntities as $a) {
            $authorsArray [] = $a->toArray($locale);
        }

        return [
            'Id' => $this->getId(),
            'Name' => $this->translate($locale)->getName(),
            'Authors' => $authorsArray
        ];
    }
}
