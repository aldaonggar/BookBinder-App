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

    #[ORM\Column(length: 30 ,nullable: true)]
    private ?string $isbn = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $genre = null;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Rating::class)]
    private Collection $ratings;

    #[ORM\ManyToMany(targetEntity: Label::class, mappedBy: 'bookLabel')]
    private Collection $labels;

    #[ORM\Column(length: 10000, nullable: true)]
    private ?string $synopsis = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cover = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FavoriteBook", mappedBy="book", orphanRemoval=true)
     */
    private $favoritedByUsers;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
        $this->labels = new ArrayCollection();
        $this->favoritedByUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setBook($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getBook() === $this) {
                $rating->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Label>
     */
    public function getLabels(): Collection
    {
        return $this->labels;
    }

    public function addLabel(Label $label): self
    {
        if (!$this->labels->contains($label)) {
            $this->labels->add($label);
            $label->addBookLabel($this);
        }

        return $this;
    }

    public function removeLabel(Label $label): self
    {
        if ($this->labels->removeElement($label)) {
            $label->removeBookLabel($this);
        }

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
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
