<?php

namespace App\Controller\Admin;


use App\Repository\EventRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin.index")
     * @Security("is_granted('ROLE_ADMIN')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('admin/index.html.twig');
    }

    public function sendMailAction(EventRepository $eventRepository)
    {
    }


}


