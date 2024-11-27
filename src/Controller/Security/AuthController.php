<?php

namespace App\Controller\Security;

use App\Controller\ApiController;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class AuthController extends ApiController
{
    #[Route('/register', name: 'register', methods: 'POST')]
    public function register(
        Request $request,
        ManagerRegistry $doctrine,
        UserPasswordHasherInterface $passwordHasher,
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['create_user'],
            ]
        )]
        User $user,
    ): JsonResponse {
        $em = $doctrine->getManager();
        $request = $this->transformJsonBody($request);
        $password = $request->get('password');
        $email = $request->get('email');

        if (empty($password) || empty($email)) {
            return $this->responseValidationError('Invalid Password or Email');
        }

        $user
            ->setPassword($passwordHasher->hashPassword($user, $password))
            ->setUsername($email)
        ;
        $em->persist($user);
        $em->flush();

        return $this->responseWithSuccess(sprintf('User %s successfully created', $user->getUsername()));
    }
}
