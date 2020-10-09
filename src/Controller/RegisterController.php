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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function Register(Request $request, ValidatorInterface $validator, UserPasswordEncoderInterface $userPasswordEncoder)
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
        $password = $userPasswordEncoder->encodePassword($user , $data['password']);
        $user->setPassword($password);
        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse("User created", Response::HTTP_OK);
    }


    /**
     * @Route("/api/update", name="update", methods={"PUT"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function Update(Request $request, UserRepository $userRepository, ValidatorInterface $validator)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $data = json_decode($request->request->getContent(),true);
        $user = $userRepository->findOneBy(['id' => $data['id']]);

        $form =$this->createForm(RegisterFormType::class, $user);
        $form->submit($data);
        $violation = $validator->validate($user);
        if(0 !== count($violation)){
            foreach($violation as $errors)
            {
                return new JsonResponse($errors->getMessage(),Response::HTTP_BAD_REQUEST);
            }
        }
        if (isset($data['email'])){
            $user->setEmail($data['email']);
        }
        if (isset($data['password'])){
            $user->setEmail($data['password']);
        }
        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse("User updated", Response::HTTP_OK);

    }

    /**
     * @Route("/api/delete", name="update", methods={"DELETE"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function Delete(UserRepository $userRepository,Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $data = json_decode($request->request->getContent(),true);
        $user = $userRepository->find($data['id']);
        if ($user) {
            $entityManager->remove($user);
            $entityManager->flush();

            return new JsonResponse("User deleted", Response::HTTP_OK);
        }else {
            return new JsonResponse("Bad request", Response::HTTP_BAD_REQUEST);
        }

    }
}
