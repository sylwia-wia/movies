<?php

namespace App\Controller;

use App\Dto\MovieFilterDto;
use App\Entity\Comment;
use App\Entity\Movie;
use App\Entity\User;
use App\EventSubscriber\TwigEventSubscriber;
use App\Form\CommentFormType;
use App\Form\MovieFilterType;
use App\Form\MovieFormType;
use App\Repository\CommentRepository;
use App\Repository\MovieRepository;
use App\Service\UploadedHelper;
use ContainerOw1OHe2\get_Console_Command_ValidatorDebug_LazyService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MovieController extends AbstractController
{
    public function __construct(private readonly MovieRepository $movieRepository, private readonly EntityManagerInterface $em)
    {

    }

    #[Route('/', name: 'movies', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $filterData = new MovieFilterDto();
        $filterForm = $this->createForm(MovieFilterType::class, $filterData);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted()
            && $filterForm->isValid()
            && $filterData->isEmpty() === false
        ) {
            $queryBuilder = $this->movieRepository->findAllBySearchFilterQueryBuilder($filterData);
        } else {
            $queryBuilder = $this->movieRepository->findAllQueryBuilder();
        }

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );


       // $movies = $this->movieRepository->findAll();

        return $this->render('movies/index.html.twig', [
            'filterForm' => $filterForm,
            'pagination' => $pagination,
            'page' => $request->query->getInt('page', 1),

        ]);
    }

    #[Route('/movies/create', name: 'create_movie')]
    public function create(Request $request, UploadedHelper $uploadedHelper): Response
    {

        $movie = new Movie();
        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form->get('imagePath')->getData();

            if($uploadedFile)
            {
                $newFileName = $uploadedHelper->uploadMovieImage($uploadedFile);

                $movie->setImagePath('/uploads/' . $newFileName);
            }

            $this->em->persist($movie);
            $this->em->flush();

            return $this->redirectToRoute('movies');
        }


        return $this->render('/movies/create.html.twig', [
            'form' => $form->createView(),
            'movie' => $movie,
        ]);
    }

    #[Route('/movies/edit/{slug}', name: 'movie_edit', methods: ['GET', 'POST'])]
    public function edit($slug, Request $request, #[Autowire('%app.file_dir%')] string $fileDir): Response
    {
        $movie = $this->movieRepository->findOneBy(['slug' => $slug]);
        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleFileUpload($form, $movie);

            $this->em->flush();
            return $this->redirectToRoute('movies');
        }


        return $this->render('movies/edit.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    #[Route('movies/{slug}', name: 'movie_show', methods: ['GET', 'POST'])]
    public function show(Request $request, #[CurrentUser] ?User $user, ?Movie $movie, CommentRepository $commentRepository)
    {
        $offset = max(0, $request->query->getInt('offset', 0));

        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment, [
            'action' => $this->generateUrl('comment_add', ['movie_id' => $movie->getId()])
        ]);

        $paginator = $commentRepository->getCommentPaginator($movie, $offset);

        return $this->render('movies/show.html.twig', [
            'user' => $this->getUser(),
            'movie' => $movie,
            'form' => $form,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
        ]);
    }


    #[Route('movies/delete/{slug}', name: 'movie_delete', methods: ['GET', 'DELETE'])]
    public function delete($slug): Response
    {
        $movie = $this->movieRepository->findOneBy(['slug' => $slug]);
        $this->em->remove($movie);
        $this->em->flush();

        return $this->redirectToRoute('movies');

    }

    #[Route('movies/{id}/render-file', name: 'movie_render_file')]
    public function renderFile(int $id): Response
    {
        $movie = $this->movieRepository->findOneBy(['id' => $id]);
        $dir = $this->getParameter('file_dir');
//        dd($this->getParameter('file_dir'));

        return new BinaryFileResponse(sprintf('%s%s', $dir, $movie->getImagePath()));
    }

    private function handleFileUpload(FormInterface $form, Movie $movie): void
    {

        $imagePath = $form->get('imagePath')->getData();

        if ($imagePath) {
            $originalImagePath = sprintf('%s', pathinfo($imagePath->getClientOriginalName(), PATHINFO_FILENAME));
            $newImagePath = sprintf('%s_%s.%s', $originalImagePath, uniqid(), $imagePath->guessExtension());

            $imagePath->move(
                $this->getParameter('kernel.project_dir') . '/public/uploads',
                $newImagePath
            );

            $movie->setImagePath('/uploads/' . $newImagePath);

        }
    }


}
