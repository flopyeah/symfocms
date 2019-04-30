<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(User::class);

        $users = $repository->findAll();

        dump($users);

        $owner = $this->getUser();

        return new JsonResponse([
            'users' => $users
        ]);

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'owner' => $owner
        ]);
    }
}
