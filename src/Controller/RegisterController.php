<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\RegisterFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function Register(Request $request, ValidatorInterface $validator)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        $data = json_decode($request->getContent(), true);
        $form =$this->createForm(RegisterFormType::class, $user);
        $form->submit($data);
        $violation = $validator->validate($user);
        if(0 !== count($violation)){
            foreach($violation as $errors)
            {
                return new JsonResponse($errors->getMessage(),Response::HTTP_BAD_REQUEST);
            }
        }
        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse("User created", Response::HTTP_OK);
    }
}
