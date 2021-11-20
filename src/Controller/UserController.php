<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'register', methods: ['POST'])]
    public function register_new_user(Request $request,UserRepository $userRepository): Response{
        $login = $request->get('login');
        $password = $request->get('password');

        if (($login == '') || ($password == '')){
            return $this->json ([
                'status'=>400,
                'message'=>"Enter login or password"
            ]);
        }

        if (count($userRepository->findBy(['login'=>$login])) > 0){
            return $this->json ([
                'status'=>400,
                'message'=>"This login is already taken, choose another"
            ]);
        }

        $user = new User();
        $user->setLogin($login);
        $user->setPassword(hashPassword($password, PASSWORD_DEFAULT));

        $add = $this->getDoctrine()->getManager();
        $add->persist($user);
        $add->flush();

        return $this->json([
            'status'=>200,
            'message'=>"OK"
        ]);
    }
}
