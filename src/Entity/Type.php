<?php

namespace App\Entity;

use App\Entity\Interface\EntityInterface;
use App\Entity\Interface\SlugInterface;
use App\Entity\Trait\SluggeableTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\TypeRepository;
use App\Validator\Product\TypeConstraintValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Type implements EntityInterface, SlugInterface
{
    use TimestampableTrait;
    use SluggeableTrait;

    private const GROUP_CREATE = 'create_type';
    private const GROUP_EDIT = 'edit_type';
    private const GROUP_SHOW_ALL = 'show_types';
    private const GROUP_SHOW_ONE = 'show_type';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $id;

    #[ORM\Column(length: 10, nullable: false)]
    #[TypeConstraintValidator]
    #[Groups([self::GROUP_CREATE, self::GROUP_EDIT, self::GROUP_SHOW_ALL, self::GROUP_SHOW_ONE])]
    private string $name;

    /**
     * @var Collection<int, Type>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'type', orphanRemoval: true)]
    private Collection $products;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $product->setType($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            if ($product->getType() === $this) {
                $product->setType(null);
            }
        }

        return $this;
    }
}
