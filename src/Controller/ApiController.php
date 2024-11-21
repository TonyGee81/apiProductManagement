<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    /**
     * @var int HTTP status code - 200 (OK) by default
     */
    protected int $statusCode = 200;

    /**
     * Gets the value of statusCode.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param int $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function response(mixed $data, array $groups, array $headers = []): JsonResponse
    {
        $dataSerialized = $this->serializer->serialize($data, 'json', ['groups' => $groups]);

        return new JsonResponse($dataSerialized, $this->getStatusCode(), $headers, true);
    }

    public function responseWithErrors(string $errors, array $headers = []): JsonResponse
    {
        $data = [
            'status' => $this->getStatusCode(),
            'errors' => $errors,
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    public function responseWithSuccess(string $success, array $headers = []): JsonResponse
    {
        $data = [
            'status' => $this->getStatusCode(),
            'success' => $success,
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Returns a 401 Unauthorized http response.
     */
    public function responseUnauthorized(string $message = 'Not authorized!'): JsonResponse
    {
        return $this->setStatusCode(401)->responseWithErrors($message);
    }

    /**
     * Returns a 422 Unprocessable Entity.
     */
    public function responseValidationError(string $message = 'Validation errors'): JsonResponse
    {
        return $this->setStatusCode(422)->responseWithErrors($message);
    }

    /**
     * Returns a 404 Not Found.
     */
    public function responseNotFound(string $message = 'Not found!'): JsonResponse
    {
        return $this->setStatusCode(404)->responseWithErrors($message);
    }

    /**
     * Returns a 201 Created.
     */
    public function responseCreated(array $data, array $groups): JsonResponse
    {
        return $this->setStatusCode(201)->response($data, $groups);
    }

    // this method allows us to accept JSON payloads in POST requests
    // since Symfony 4 doesn’t handle that automatically:

    protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if (null === $data) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}
