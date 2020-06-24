<?php
/**
 * Author controller.
 */

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AuthorController.
 *
 * @Route("/author")
 */
class AuthorController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Repository\AuthorRepository           $authorRepository Author repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="author_index",
     * )
     *
     */
    public function index(Request $request, AuthorRepository $authorRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $authorRepository->queryAll(),
            $request->query->getInt('page', 1),
            AuthorRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'author/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Author $author Author entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="author_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     */
    public function show(Author $author): Response
    {
        return $this->render(
            'author/show.html.twig',
            ['author' => $author]
        );
    }
    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\AuthorRepository        $authorRepository Author repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="author_create",
     * )
     *  @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request, AuthorRepository $authorRepository): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $authorRepository->save($author);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('author_index');
        }

        return $this->render(
            'author/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\Author                      $author           Author entity
     * @param \App\Repository\AuthorRepository        $authorRepository Author repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="author_edit",
     * )
     *  @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Author $author, AuthorRepository $authorRepository): Response
    {
        $form = $this->createForm(AuthorType::class, $author, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $authorRepository->save($author);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('author_index');
        }

        return $this->render(
            'author/edit.html.twig',
            [
                'form' => $form->createView(),
                'author' => $author,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\Author                      $author           Author entity
     * @param \App\Repository\AuthorRepository       $authoryRepository Author repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="author_delete",
     * )
     */
    public function delete(Request $request, Author $author, AuthorRepository $repository): Response
    {
        if ($author->getVinyls()->count()) {
            $this->addFlash('warning', 'message_author_contains_tasks');

            return $this->redirectToRoute('author_index');
        }

        $form = $this->createForm(AuthorType::class, $author, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($author);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('author_index');
        }
        return $this->render(
            'author/delete.html.twig',
            [
                'form' => $form->createView(),
                'author' => $author,
            ]
        );

    }
}
