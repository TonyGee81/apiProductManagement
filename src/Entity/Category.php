<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use App\Service\SlugService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Category
{
    private const GROUP_CREATE = 'create_category';
    private const GROUP_EDIT = 'edit_category';
    private const GROUP_SHOW_ALL = 'show_categories';
    private const GROUP_SHOW_ONE = 'show_category';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_SHOW_ALL,  self::GROUP_SHOW_ONE, self::GROUP_EDIT])]
    private $id;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GROUP_CREATE, self::GROUP_EDIT, self::GROUP_SHOW_ALL, self::GROUP_SHOW_ONE])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private string $slug;

    /**
     * @var Collection<int, Type>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'type', orphanRemoval: true)]
    private Collection $products;

    private SlugService $slugService;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->slugService = new SlugService();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }

    #[ORM\PrePersist]
    public function createSlug(): void
    {
        $this->slug = $this->slugService->slugify($this->name);
    }
}
