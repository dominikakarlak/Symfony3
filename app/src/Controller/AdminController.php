<?php
/**
 * Admin controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminEmailType;
use App\Form\AdminPasswordType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AdminController.
 *
 * @Route("/admin")
 */
class AdminController extends AbstractController
{

    /**
     * Show action.
     *
     * @param \App\Entity\User $admin User entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="admin_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     */
    public function show(User $admin): Response
    {
        return $this->render(
            'admin/show.html.twig',
            ['admin' => $admin]
        );
    }
    /**
     * Admin email action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\User                     $admin           User entity
     * @param \App\Repository\UserRepository        $userRepository User repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/admin-email",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_email",
     * )
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function adminEmail(Request $request, User $admin, UserRepository $userRepository): Response
    {
        $form = $this->createForm(AdminEmailType::class, $admin, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($admin);
            $this->addFlash('success', 'message_updated_successfully');
            return $this->redirectToRoute('admin_show',['id'=>$admin->getId()]);
        }
        return $this->render(
            'admin/email.html.twig',
            [
                'form' => $form->createView(),
                'admin' => $admin,
            ]
        );
    }
    /**
     * Admin password action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Entity\User                     $admin           User entity
     * @param \App\Repository\UserRepository        $userRepository User repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/admin-password",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_password",
     * )
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function adminPassword(Request $request, User $admin, UserRepository $userRepository,UserPasswordEncoderInterface $passwordEncoder): Response
    {

        $form = $this->createForm(AdminPasswordType::class, $admin, ['method' => 'PUT']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('new_password')->getData();
            $admin = $this->getUser();
            $admin->setPassword($passwordEncoder->encodePassword($admin, $newPassword));
            $userRepository->save($admin);
            $this->addFlash('success', 'message_updated_successfully');
            return $this->redirectToRoute('admin_show',['id'=>$admin->getId()]);
        }
        return $this->render(
            'admin/password.html.twig',
            [
                'form' => $form->createView(),
                'admin' => $admin,
            ]
        );
    }
}
