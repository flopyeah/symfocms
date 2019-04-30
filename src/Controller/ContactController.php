<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Utils\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact_index")
     */
    public function index(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

//        if ($request->isMethod('POST')) {
        if ($form->isSubmitted()) {

            //$valid = $form->isValid();

            $errors = FormError::getErrors($form);

            if ( $errors )
            {
                return new JsonResponse([
                    'error'=>$errors
                ]);
            }
            else
            {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($contact);
                $entityManager->flush();

                return new JsonResponse([
                    'contact'=> $contact,
                    'message' => 'Message Envoyé']);
            }
        }

        return $this->render('contact/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
