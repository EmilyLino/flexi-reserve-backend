<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{

    /**
     * @Route("/register",methods={"POST"}, name="api_register")
     */
    public function register(
        Request $request,
        ManagerRegistry $doctrine,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator
    ): JsonResponse {
        try{
            $data = json_decode($request->getContent(), true);
            $userName = $data['user_name'] ?? null;
            $plainPwd = $data['password'] ?? null;

            if (!$userName || !$plainPwd) {
                return new JsonResponse(
                    ['message' => 'user_name and password are required'],
                    JsonResponse::HTTP_BAD_REQUEST
                );
            }

            $userRepository = $doctrine->getManager();
            if ($userRepository->getRepository(User::class)->findOneBy(['user_name' => $userName])) 
            {
                return new JsonResponse(
                    ['message' => 'El nombre de usuario ya existe'],
                    JsonResponse::HTTP_CONFLICT
                );
            }

            $user = new User();
            $user->setUserName($userName);

            //Hashea la contraseña
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPwd);
            $user->setPassword($hashedPassword);

            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                return new JsonResponse(
                    ['message' => (string) $errors],
                    JsonResponse::HTTP_BAD_REQUEST
                );
            }

            // 7. Persiste y guarda
            $userRepository->persist($user);
            $userRepository->flush();

            return new JsonResponse(
                ['message' => 'User registered successfully'],
                JsonResponse::HTTP_CREATED
            );
        }catch (\Exception $e)
        {
            return new JsonResponse(["message"=>$e->getMessage()], 500);
        }
        
    }
}
