<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use OpenApi\Annotations as OA;

class AuthentificationController extends AbstractController
{
    /**
     * @OA\Response(
     *     response=200,
     *     description="Log the user"
     * )
     * @Route("/api/login", name="login", methods={"POST"})
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return JsonResponse
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return new JsonResponse([$lastUsername, $error]);
    }

    /**
     * @Route("api/logout", name="logout", methods={"POST"})
     */
    public function logout()
    {

    }
}