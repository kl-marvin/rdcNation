<?php

namespace App\Controller\Admin;

use App\Entity\CR;
use App\Form\CrType;
use App\Repository\CRRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


class AdminCRController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     *
     * @param CRRepository $CRRepository
     * @param ObjectManager $em
     */
    public function __construct(CRRepository $CRRepository, ObjectManager $em)
    {
        $this->CRrepository = $CRRepository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/cr", name="admin.cr.index")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('ROLE_ADMIN')")
     *
     */
    public function indexAction()
    {
        $last1O = $this->CRrepository->findBy([], ['id' => 'desc'], 10, 0);

        return $this->render('admin/CR/indexCr.html.twig', array(
            'last10' => $last1O
        ));
    }

    /**
     * @Route("/admin/credit/{id}", name="admin.cr.edit")
     * @param CR $CR
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function editAction(CR $CR, Request $request)
    {
        $form = $this->createForm(CrType::class, $CR);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Le compte rendu a bien été modifié.');
            return $this->redirectToRoute('admin.cr.index');
        }
        return $this->render('admin/CR/editCr.html.twig', [
            'cr' => $CR,
            'form' =>$form->createView()
        ]);
    }

    /**
     * @Route("/admin/cr/create", name="admin.cr.create", methods="GET|POST")
     * @param CR $CR
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function createAction(Request $request)
    {
        $cr = new CR();
        $form = $this->createForm(CrType::class, $cr);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($cr);
            $this->em->flush();
            $this->addFlash('success', 'Le compte rendu a bien été crée.');
            return $this->redirectToRoute('admin.cr.index');
        }
        return $this->render('admin/CR/createCr.html.twig', [
            'cr' => $cr,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/cr/{id}", name="admin.cr.delete", methods="DELETE")
     * @param CR $CR
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteAction(CR $cr)
    {
        $this->em->remove($cr);
        $this->em->flush();
        $this->addFlash('success', 'Le compte rendu a bien été supprimé.');
        return $this->redirectToRoute('admin.cr.index');
    }
}