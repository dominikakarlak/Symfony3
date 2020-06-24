<?php
/**
 * Vinyl controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Vinyl;
use App\Form\CommentType;
use App\Repository\VinylRepository;
use App\Repository\CommentRepository;

use App\Form\VinylType;

use phpDocumentor\Reflection\Types\String_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class VinylController.
 *
 * @Route("/vinyl")
 */
class VinylController extends AbstractController
{
    /**
     * Show action.
     *
     * @param Request $request
     * @param VinylRepository $vinylRepository
     * @param Comment $comment
     * @param $commentRepository
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Exception
     * @Route(
     *     "/{id}",
     *     methods={"GET", "POST" },
     *     name="vinyl_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Request $request, Vinyl $vinyl, CommentRepository $commentRepository, int $id): Response
    {
        return $this->render(
                'vinyl/show.html.twig',
                ['vinyl' => $vinyl]
        );
    }
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Repository\VinylRepository           $vinylRepository Vinyl repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="vinyl_index",
     * )
     */
    public function index(Request $request, VinylRepository $vinylRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $vinylRepository->queryAll(),
            $request->query->getInt('page', 1),
            VinylRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'vinyl/index.html.twig',
            ['pagination' => $pagination]
        );
    }


    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Repository\VinylRepository            $vinylRepository Vinyl repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="vinyl_create",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request, VinylRepository $vinylRepository): Response
    {
        $vinyl = new Vinyl();
        $form = $this->createForm(VinylType::class, $vinyl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vinylRepository->save($vinyl);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('vinyl_index');
        }

        return $this->render(
            'vinyl/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Entity\Vinyl                          $vinyl           Vinyl entity
     * @param \App\Repository\VinylRepository            $vinylRepository Vinyl repository
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
     *     name="vinyl_edit",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Vinyl $vinyl, VinylRepository $vinylRepository): Response
    {
        $form = $this->createForm(VinylType::class, $vinyl, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vinylRepository->save($vinyl);
            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('vinyl_index');
        }

        return $this->render(
            'vinyl/edit.html.twig',
            [
                'form' => $form->createView(),
                'vinyl' => $vinyl,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Entity\Vinyl                          $vinyl         Vinyl entity
     * @param \App\Repository\VinylRepository            $vinylRepository Vinyl repository
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
     *     name="vinyl_delete",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Vinyl $vinyl, VinylRepository $vinylRepository): Response
    {
        $form = $this->createForm(FormType::class, $vinyl, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $vinylRepository->delete($vinyl);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('vinyl_index');
        }

        return $this->render(
            'vinyl/delete.html.twig',
            [
                'form' => $form->createView(),
                'vinyl' => $vinyl,
            ]
        );
    }
}
