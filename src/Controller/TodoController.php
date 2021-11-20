<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/todo')]
class TodoController extends AbstractController
{
    #[Route('/', name: 'getlist', methods: ['GET'])]
    public function get_list(Request $request,TodoRepository $todoRepository,UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if ((data['login'] == '') || (data['password'] == '')){
            return $this->json ([
                'status'=>400,
                'message'=>"Enter login or password"
            ]);
        }

        if (count($userRepository->findBy(['login'=>data['login']])) == 0){
            return $this->json ([
                'status'=>400,
                'message'=>"Login error"
            ]);
        }

        if ($userRepository->findBy(['login'=>data['login']])->getPassword() != hashPassword(data['password'], PASSWORD_DEFAULT)){
            return $this->json ([
                'status'=>400,
                'message'=>"Password error"
            ]);
        }

        $result=$todoRepository->findBy(['login'=>data['login']]);

        $res=[];
        foreach ($result as $todo){
            $res=["todo"=>$todo->getText()];
        }

        return $this->response($res);

    }

    #[Route('/', name: 'add', methods: ['POST'])]
    public function add(Request $request,TodoRepository $todoRepository,UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if ((data['login'] == '') || (data['password'] == '')){
            return $this->json ([
                'status'=>400,
                'message'=>"Enter login or password"
            ]);
        }

        if (count($userRepository->findBy(['login'=>data['login']])) == 0){
            return $this->json ([
                'status'=>400,
                'message'=>"Login error"
            ]);
        }

        if ($userRepository->findBy(['login'=>data['login']])->getPassword() != hashPassword(data['password'], PASSWORD_DEFAULT)){
            return $this->json ([
                'status'=>400,
                'message'=>"Password error"
            ]);
        }

        $to_do = new Todo();
        $to_do->setLogin(data['login']);
        $to_do->setText(data['text']);

        $add = $this->getDoctrine()->getManager();
        $add->persist($to_do);
        $add->flush();

        return $this->json([
            'status'=>200,
            'message'=>"OK"
        ]);

    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(Request $request,TodoRepository $todoRepository,UserRepository $userRepository,$id): Response
    {
        $data = json_decode($request->getContent(), true);

        if ((data['login'] == '') || (data['password'] == '')){
            return $this->json ([
                'status'=>400,
                'message'=>"Enter login or password"
            ]);
        }

        if (count($userRepository->findBy(['login'=>data['login']])) == 0){
            return $this->json ([
                'status'=>400,
                'message'=>"Login error"
            ]);
        }

        if ($userRepository->findBy(['login'=>data['login']])->getPassword() != hashPassword(data['password'], PASSWORD_DEFAULT)){
            return $this->json ([
                'status'=>400,
                'message'=>"Password error"
            ]);
        }


        $to_do = $todoRepository->findBy($id,['login'=>data['login']]);

        $to_do->setText(data['text']);

        $add = $this->getDoctrine()->getManager();
        $add->persist($to_do);
        $add->flush();

        return $this->json([
            'status'=>200,
            'message'=>"OK"
        ]);

    }

    #[Route('/{id}', name: 'del', methods: ['DELETE'])]
    public function delete(Request $request,TodoRepository $todoRepository,UserRepository $userRepository,$id): Response
    {
        $data = json_decode($request->getContent(), true);

        if ((data['login'] == '') || (data['password'] == '')){
            return $this->json ([
                'status'=>400,
                'message'=>"Enter login or password"
            ]);
        }

        if (count($userRepository->findBy(['login'=>data['login']])) == 0){
            return $this->json ([
                'status'=>400,
                'message'=>"Login error"
            ]);
        }

        if ($userRepository->findBy(['login'=>data['login']])->getPassword() != hashPassword(data['password'], PASSWORD_DEFAULT)){
            return $this->json ([
                'status'=>400,
                'message'=>"Password error"
            ]);
        }


        $to_do = $todoRepository->findBy($id,['login'=>data['login']]);

        $del = $this->getDoctrine()->getManager();
        $del->remove($to_do);
        $del->flush();

        return $this->json([
            'status'=>200,
            'message'=>"OK"
        ]);

    }
}
