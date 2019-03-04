<?php

namespace App\Controller\Admin;



use App\Entity\Event;
use App\Form\EventCreationType;
use App\Repository\EventRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminEventController extends AbstractController
{

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(EventRepository $eventRepository, ObjectManager $em)
    {
        $this->eventRepository = $eventRepository;
        $this->em = $em;
    }

    /**
     * @Route("/event", name="admin.event.index")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        $last5 = $this->eventRepository->findBy([], ['id' => 'asc'], 5, 0);

        return $this->render('admin/event/indexEvent', array(
            'last5' => $last5
        ));
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("admin/event/create", name="admin.event.create")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventCreationType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($event);
            $this->em->flush();
            $this->addFlash('success', 'La distribution a bien été crée.' );
            return $this->redirectToRoute('admin.event.index');
        }
        return $this->render('admin/event/createEvent.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/event/{id}", name="admin.event.edit", methods="GET|POST")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function editAction(Event $event, Request $request)
    {
        $form = $this->createForm(EventCreationType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success', 'La distribution a bien été modifié');
            return $this->redirectToRoute('admin.event.index');
        }
        return $this->render('admin/event/editEvent.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/admin/event/{id}", name="admin.event.delete", methods="DELETE")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @param Event $event
     */
    public function deleteAction(Event $event)
    {
        $this->em->remove($event);
        $this->em->flush();
        $this->addFlash('success', 'La distribution a bien été supprimé.');
        return $this->redirectToRoute('admin.event.index');
    }
}