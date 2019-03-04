<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class AdminUserController extends AbstractController
{

    /**
     * @var ObjectManager
     */
        private $em;

    /**
     * param ObjectManager $em
     */
    public function __construct(UserRepository $userRepository, ObjectManager $em)
    {
        $this->UserRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/user", name="admin.user.index")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        $users = $this->UserRepository->findAll();

        return $this->render('admin/User/indexUser.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/user/{id}/edit", name="admin.user.edit")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function editRoleAction($id)
    {
        $user = $this->UserRepository->find($id);

        if($user->containsRole('ROLE_ADMIN')){
            $user->setRoles(['ROLE_USER']);
            $this->em->flush();
        }else{
            $user->setRoles(['ROLE_ADMIN']);
            $this->em->flush();
        }
        $this->addFlash('success', 'Le rôle de l\'utilisateur a bien été modifié.');
        return $this->redirectToRoute('admin.user.index');

    }

    /**
     * @Route("/admin/user/{id}/delete", name="admin.user.delete")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param User $user
     *return \Symfony\Component\HttpFoundation\RedirectResponse
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
        $this->addFlash('sucess', 'Cet utilisateur a bien été supprimé.');

        return $this->redirectToRoute('admin.user.index');
    }

    /**
     * @param User $user
     * @Route("/admin/user/{id}/show", name="admin.user.show")
     * @Security("is_granted('ROLE_ADMIN')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showUserAction(User $user)
    {
        return $this->render('admin/User/adminUserShow.html.twig', [
            'user' => $user
        ]);
    }
}