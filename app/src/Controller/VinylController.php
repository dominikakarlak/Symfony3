<?php
/**
 * Vinyl controller.
 */

namespace App\Controller;

use App\Entity\Vinyl;
use App\Repository\VinylRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * Show action.
     *
     * @param \App\Entity\Vinyl $vinyl Vinyl entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="vinyl_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Vinyl $vinyl): Response
    {
        return $this->render(
            'vinyl/show.html.twig',
            ['vinyl' => $vinyl]
        );
    }
}