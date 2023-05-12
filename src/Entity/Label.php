<?php

namespace App\Entity;

use App\Repository\LabelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LabelRepository::class)]
class Label
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $labelName = null;

    #[ORM\ManyToMany(targetEntity: book::class, inversedBy: 'labels')]
    private Collection $bookLabel;

    public function __construct()
    {
        $this->bookLabel = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabelName(): ?string
    {
        return $this->labelName;
    }

    public function setLabelName(string $labelName): self
    {
        $this->labelName = $labelName;

        return $this;
    }

    /**
     * @return Collection<int, book>
     */
    public function getBookLabel(): Collection
    {
        return $this->bookLabel;
    }

    public function addBookLabel(book $bookLabel): self
    {
        if (!$this->bookLabel->contains($bookLabel)) {
            $this->bookLabel->add($bookLabel);
        }

        return $this;
    }

    public function removeBookLabel(book $bookLabel): self
    {
        $this->bookLabel->removeElement($bookLabel);

        return $this;
    }
}
