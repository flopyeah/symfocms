<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index()
    {
        $postRepository     = $this->getDoctrine()->getRepository(Post::class);
        $posts              = $postRepository->findBy(
            [],
            ['date_created' => 'DESC'],
            5
        );

        $contactRepository  = $this->getDoctrine()->getRepository(Contact::class);
        $contacts           = $contactRepository->findBy(
            [],
            ['date_created' => 'DESC'],
            5
        );

        return $this->render('admin/dashboard/index.html.twig', [
            'posts' => $posts,
            'contacts' => $contacts,
        ]);
    }
}
