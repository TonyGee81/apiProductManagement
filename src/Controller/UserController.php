<?php

namespace App\Controller;

use App\DTO\paginationDTO;
use App\Repository\UserRepository;
use App\Service\UserInfo;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
#[IsGranted('ROLE_ADMIN')]
class UserController extends ApiController
{
    private const RESPONSE_404 = 'User not found';

    #[Route('/users', name: 'show_users', methods: ['GET'])]
    public function showUsers(
        Request $request,
        UserRepository $userRepository,
        #[MapQueryString]
        paginationDTO $paginationDTO,
    ): JsonResponse {
        $users = $userRepository->paginateUsers($paginationDTO->page);
        $groups = $request->query->all('groups');

        return $this->response($users, $groups);
    }

    #[Route('/users/{userId}', name: 'show_user', methods: ['GET'])]
    public function showUser(
        int $userId,
        Request $request,
        UserRepository $userRepository,
    ): JsonResponse {
        if (!$user = $userRepository->find($userId)) {
            return $this->responseNotFound(self::RESPONSE_404);
        }

        $groups = $request->query->all('groups');

        return $this->response($user, $groups);
    }

    #[Route('/user/current', name: 'current_user', methods: ['GET'])]
    public function get(
        Request $request,
        UserInfo $userInfo,
    ): JsonResponse {
        if (!$user = $userInfo->getUser()) {
            return $this->responseNotFound(self::RESPONSE_404);
        }

        $groups = $request->query->all('groups');

        return $this->response($user, $groups);
    }
}
