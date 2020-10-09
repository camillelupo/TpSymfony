<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthentificationController extends AbstractController
{
    /**
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