<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @UniqueEntity("title")
 * @ORM\EntityListeners({"App\EventListener\ProductListener"})
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class)
     * @var Collection
     */
    private $categories;

    /**
     * @ORM\Column(type="string", length=12)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 12,
     *      minMessage = "Title must be at least {{ limit }} characters long",
     *      maxMessage = "Title cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $title;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0,
     *      max = 200,
     *      notInRangeMessage = "Price must be between {{ min }} and {{ max }}",
     * )
     */
    private ?float $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $eid;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getEid(): ?int
    {
        return $this->eid;
    }

    public function setEid(?int $eid): self
    {
        $this->eid = $eid;

        return $this;
    }
}
