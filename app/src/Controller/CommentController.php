<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Vinyl;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\VinylRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Comment Controller.
 *
 * @Route("/comment")
 */
class CommentController extends AbstractController
{


    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Repository\CommentRepository $commentRepository Comment repository
     *
     * @param Vinyl $vinyl
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create-comment/{id}",
     *     methods={"GET", "POST"},
     *     name="comment_create",
     *     requirements={"id": "[1-9]\d*"},
     *
     * )
     */
    public function create(Request $request, CommentRepository $commentRepository, Vinyl $vinyl): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime());
            $comment->setVinyl($vinyl);
            $comment->setAuthor($this->getUser());
            $commentRepository->save($comment);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('vinyl_show', ['id'=> $vinyl->getId()]);
        }

        return $this->render(
            'comment/create.html.twig',
            [
                'form' => $form->createView(),
                'vinyl'=> $vinyl]
        );
    }


    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Comment $comment Comment entity
     * @param \App\Repository\CommentRepository $commentRepository Comment repository
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route(
     *     "/edit-comment/{id}",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="comment_edit",
     * )
     * @Security("is_granted('ROLE_ADMIN') or is_granted('EDIT', comment)")
     */
    public function edit(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        $vinyl=$comment->getVinyl();
        $form = $this->createForm(CommentType::class, $comment, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->save($comment);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('vinyl_show', ['id'=> $vinyl->getId()]);
        }

        return $this->render(
            'comment/edit.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
                'vinyl'=> $comment->getVinyl(),

            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\Comment                      $comment          Comment entity
     * @param \App\Repository\CommentRepository        $commentRepository Comment repository
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
     *     name="comment_delete",
     * )
     * @Security("is_granted('ROLE_ADMIN') or is_granted('DELETE', comment)")
     */
    public function delete(Request $request, Comment $comment, CommentRepository $repository): Response
    {


        $vinyl=$comment->getVinyl();

        $form = $this->createForm(FormType::class, $comment, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($comment);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('vinyl_show', ['id'=> $vinyl->getId()]);
        }

        return $this->render(
            'comment/delete.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
                'vinyl'=> $comment->getVinyl(),

            ]
        );
    }
}
