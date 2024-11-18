<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

readonly class UserInfo
{
    public function __construct(
        private Security $security,
        private UserRepository $userRepository,
    ) {
    }

    public function getUser(): User
    {
        return $this->getUserFromRepository();
    }

    public function getRoles(): string
    {
        return $this->getUserFromRepository()->getRoles()[0];
    }

    private function getUserFromRepository(): ?User
    {
        return $this->userRepository->findOneByEmail($this->security->getUser()->getUserIdentifier());
    }
}
