<?php

namespace App\Entity;

use App\Entity\Interface\EntityInterface;
use App\Entity\Interface\SlugInterface;
use App\Entity\Trait\SluggeableTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\ProductRepository;
use App\Validator\Product\CodeConstraintValidator;
use App\Validator\Product\PriceConstraintValidator;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Product implements EntityInterface, SlugInterface
{
    use TimestampableTrait;
    use SluggeableTrait;

    private const GROUP_CREATE = 'create_product';
    public const GROUP_EDIT = 'edit_product';
    private const GROUP_SHOW_ALL = 'show_products';
    private const GROUP_SHOW_ONE = 'show_product';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_SHOW_ALL,  self::GROUP_SHOW_ONE, self::GROUP_EDIT])]
    private $id;

    #[ORM\Column(length: 10, nullable: false)]
    #[Groups([self::GROUP_CREATE, self::GROUP_EDIT, self::GROUP_SHOW_ALL])]
    #[CodeConstraintValidator]
    private string $code;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GROUP_CREATE, self::GROUP_EDIT, self::GROUP_SHOW_ALL, self::GROUP_SHOW_ONE])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private string $slug;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GROUP_CREATE, self::GROUP_EDIT, self::GROUP_SHOW_ALL, self::GROUP_SHOW_ONE])]
    private ?string $country = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups([self::GROUP_CREATE, self::GROUP_EDIT, self::GROUP_SHOW_ALL, self::GROUP_SHOW_ONE])]
    private bool $isEuropeanUnion = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups([self::GROUP_CREATE, self::GROUP_EDIT, self::GROUP_SHOW_ALL, self::GROUP_SHOW_ONE])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: false)]
    #[Groups([self::GROUP_CREATE, self::GROUP_EDIT, self::GROUP_SHOW_ALL, self::GROUP_SHOW_ONE])]
    #[PriceConstraintValidator]
    private float $price = 0.00;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[Groups([self::GROUP_CREATE, self::GROUP_EDIT, self::GROUP_SHOW_ONE])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Supplier $supplier = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[Groups([self::GROUP_CREATE, self::GROUP_EDIT, self::GROUP_SHOW_ONE])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Category $category = null;

    public function getId()
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function isEuropeanUnion(): bool
    {
        return $this->isEuropeanUnion;
    }

    public function setIsEuropeanUnion(bool $isEuropeanUnion): self
    {
        $this->isEuropeanUnion = $isEuropeanUnion;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
