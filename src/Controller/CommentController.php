<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Movie;
use App\Entity\User;
use App\Form\CommentFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class CommentController extends AbstractController
{
    #[Route('/comment-add/{movie_id}', name: 'comment_add', methods: ['POST'])]
    public function add(Request $request,
        EntityManagerInterface $em,
        #[MapEntity(id: 'movie_id')]
        Movie $movie
    ): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->denyAccessUnlessGranted('ROLE_USER');

            $comment->setUser($this->getUser());
            $comment->setMovie($movie);

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('movie_show', [
                'slug' => $movie->getSlug(),

            ]);
        }

        $this->addFlash('error', 'An error occurred.');
        return $this->redirectToRoute('movie_show');
    }


}
