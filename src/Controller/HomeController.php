<?php

namespace App\Controller;

use App\Service\UserInfo;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
#[IsGranted('ROLE_ADMIN')]
class HomeController extends ApiController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(UserInfo $userInfo): JsonResponse
    {
        return $this->response([
            'message' => 'Welcome to your administration '.$userInfo->getUser()->getUserIdentifier(),
        ], []);
    }
}
