<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $author = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $isbn = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $genre = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $cover = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FavoriteBook", mappedBy="book", orphanRemoval=true)
     */
    private $favoritedByUsers;

    public function __construct()
    {
        $this->favoritedByUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * @return Collection|FavoriteBook[]
     */
    public function getFavoritedByUsers(): Collection
    {
        return $this->favoritedByUsers;
    }

    public function addFavoritedByUser(FavoriteBook $favoritedByUser): self
    {
        if (!$this->favoritedByUsers->contains($favoritedByUser)) {
            $this->favoritedByUsers[] = $favoritedByUser;
            $favoritedByUser->setBook($this);
        }

        return $this;
    }

    public function removeFavoritedByUser(FavoriteBook $favoritedByUser): self
    {
        if ($this->favoritedByUsers->removeElement($favoritedByUser)) {
            // set the owning side to null (unless already changed)
            if ($favoritedByUser->getBook() === $this) {
                $favoritedByUser->setBook(null);
            }
        }

        return $this;
    }

    public function show(Book $book)
    {
        $usersWhoFavorited = $book->getFavoritedByUsers()->map(function($favorite) {
            return $favorite->getUser();
        });

        return $this->render('book.html.twig', [
            'book' => $book,
            'usersWhoFavorited' => $usersWhoFavorited,
        ]);
    }


}
