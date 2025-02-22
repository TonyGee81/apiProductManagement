<?php

namespace App\Entity;

use App\Entity\Interface\EntityInterface;
use App\Entity\Trait\TimestampableTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface, EntityInterface
{
    use TimestampableTrait;
    private const GROUP_CREATE = 'create_user';
    private const GROUP_EDIT = 'edit_user';
    private const GROUP_SHOW_ALL = 'show_users';
    private const GROUP_SHOW_ONE = 'show_user';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([self::GROUP_EDIT])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups([self::GROUP_CREATE, self::GROUP_EDIT, self::GROUP_SHOW_ALL, self::GROUP_SHOW_ONE])]
    private string $username;

    #[ORM\Column(length: 180)]
    #[Groups([self::GROUP_CREATE, self::GROUP_EDIT, self::GROUP_SHOW_ALL, self::GROUP_SHOW_ONE])]
    private ?string $email;

    #[ORM\Column(length: 180, nullable: true)]
    #[Groups([self::GROUP_CREATE, self::GROUP_EDIT, self::GROUP_SHOW_ALL, self::GROUP_SHOW_ONE])]
    private ?string $firstName = null;

    #[ORM\Column(length: 180, nullable: true)]
    #[Groups([self::GROUP_CREATE, self::GROUP_EDIT, self::GROUP_SHOW_ALL, self::GROUP_SHOW_ONE])]
    private ?string $lastName = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups([self::GROUP_CREATE, self::GROUP_EDIT, self::GROUP_SHOW_ALL, self::GROUP_SHOW_ONE])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
