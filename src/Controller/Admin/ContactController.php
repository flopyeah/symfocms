<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/admin/contact", name="admin_contact")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Contact::class);

        $contacts = $repository->findAll();

        return $this->render('admin/contact/list.html.twig', [
            'contacts' => $contacts,
        ]);
    }
}
