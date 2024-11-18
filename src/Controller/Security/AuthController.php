<?php

namespace App\Controller\Security;

use App\Controller\ApiController;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class AuthController extends ApiController
{
    #[Route('/register', name: 'register', methods: 'post')]
    public function register(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $em = $doctrine->getManager();
        $request = $this->transformJsonBody($request);
        $password = $request->get('password');
        $email = $request->get('email');
        $roles = $request->get('roles');

        if (empty($password) || empty($email)) {
            return $this->respondValidationError('Invalid Password or Email');
        }

        $user = new User();
        $user
            ->setPassword($passwordHasher->hashPassword($user, $password))
            ->setEmail($email)
            ->setUsername($email)
            ->setRoles($roles)
        ;
        $em->persist($user);
        $em->flush();

        return $this->respondWithSuccess(sprintf('User %s successfully created', $user->getUsername()));
    }

    /**
     * @return JsonResponse
     */
    public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager)
    {
        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }
}
