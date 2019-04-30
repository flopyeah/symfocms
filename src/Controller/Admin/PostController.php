<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Utils\FormError;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/admin/post", defaults={"page": "1", "_format"="html"}, methods={"GET"}, name="admin_post")
     * @Route("/admin/post/page/{page<[1-9]\d*>}", defaults={"_format"="html"}, methods={"GET"}, name="admin_post_paginated")
     * @param PostRepository $postRepository
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(PostRepository $postRepository, PaginatorInterface $paginator, Request $request)
    {
        $query = $postRepository->findAllQuery();
        $results = $postRepository->findAllResult();

        $page = $request->query->getInt('page', 1);

        $posts = $paginator->paginate(
            $query, /* query NOT result */
            $page, /* page number*/
            5 /* limit per page*/
        );

        dump($results);
        dump(json_encode($posts->getItems()));

        if ((count($posts) === 0) && ($page > 1))
        {
            // page non existante 404
            throw new NotFoundHttpException('404 Not found ');
        }
/*
        return $this->json([
            'posts' => $posts->getItems()
        ]);

        return new JsonResponse([
            'posts' => $posts->getItems()
        ]);
*/
        return $this->render('admin/post/post.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/admin/post/edit/{id}", name="admin_post_edit")
     * @Route("/admin/post/add", name="admin_post_add")
     * @param PostRepository $postRepository
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function edit(PostRepository $postRepository, Request $request, $id = null)
    {
        if ($id === null)
        {
            $post = new Post();
            $action = $this->generateUrl('admin_post_add');
        }
        else
        {
            $post = $postRepository->find($id);
            $action = $this->generateUrl('admin_post_edit', ['id' => $post->getId()]);
        }

        $form = $this->createForm( PostType::class, $post );
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //$valid = $form->isValid();
            //$data = $request->getMethod();

            $errors = FormError::getErrors($form);

            if ($errors) {
                return new JsonResponse([
                    'error' => $errors
                ]);
            }
            else {
                if ($id === null)
                {
                    $post   ->setDateCreated(new \DateTime())
                            ->setUser($this->getUser())
                    ;
                    $message = 'Nouvelle publication enregistrée';
                }
                else
                {
                    $message = 'modifications enregistrées';
                }

                $post->setDateModified(new \DateTime());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($post);
                $entityManager->flush();

                return new JsonResponse([
                    'message' => $message,
                    'action' => $this->generateUrl('admin_post_edit', ['id' => $post->getId()])
                ]);
            }
        }

        return $this->render('admin/post/edit.html.twig', [
            'post'      => $post,
            'action'    => $action,
            'postForm'  => $form->createView()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/post/delete", name="admin_post_delete", methods="delete")
     */
    public function delete( Request $request )
    {
        $data = json_decode( $request->getContent());
        $id = $data->id;

        if ($this->isCsrfTokenValid( 'delete_'.$id, $data->_token ))
        {
            $manager = $this->getDoctrine()->getManager();
            $post = $manager->find(Post::class, $id);
            $manager->remove($post);
            $manager->flush();

            return new JsonResponse([
                'message' => 'Deleted'
            ]);
        }
        else
        {
            return new JsonResponse([
                'error' => 'Erreur lors de la suppression',
            ]);
        }
    }
}
