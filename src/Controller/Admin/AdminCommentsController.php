<?php

namespace App\Controller\Admin;

use App\Entity\Comments;
use App\Repository\CommentsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentsController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("admin/allcomments", name="admin.comments.index")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction(CommentsRepository $commentsRepository)
    {
        $comments = $commentsRepository->findAll();

        return $this->render('admin/Comments/indexComments.html.twig', array(
            'comments' => $comments
        ));
    }

    /**
     * @Route("/allcomments/{id}", name="admin.comments.delete", methods="DELETE")
     * @param Comments $Comments
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * param Comments $comments
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteAction(Comments $Comments)
    {
        $this->em->remove($Comments);
        $this->em->flush();
        $this->addFlash('sucess', 'Le commentaire a bien été supprimé');

        return $this->redirectToRoute('admin.comments.index');
    }

    /**
     * @param Comments $Comments
     * @param CommentsRepository $commentsRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("all/comments/{id}/edit", name="admin.comments.update", methods="GET")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function updateAction(Comments $Comments, CommentsRepository $commentsRepository)
    {
        $moderateStatus = $Comments->setStatut(1);
        $this->em->persist($moderateStatus);
        $this->em->flush($moderateStatus);

        return $this->redirectToRoute('admin.comments.index');

    }

    /**
     * @param Comments $Comments
     * @param CommentsRepository $commentsRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("all/comments/{id}/putback", name="admin.comments.putback", methods="GET")
     */
    public function showbackAction(Comments $Comments, CommentsRepository $commentsRepository)
    {
        $visibleStatus = $Comments->setStatut(0);
        $this->em->persist($visibleStatus);
        $this->em->flush($visibleStatus);

        return $this->redirectToRoute('admin.comments.index');
    }

}