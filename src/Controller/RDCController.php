<?php

namespace App\Controller;


use App\Entity\CR;
use App\Entity\Comments;
use App\Entity\Event;
use App\Entity\Presence;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\EditUserType;
use App\Form\PresenceType;
use App\Repository\CommentsRepository;
use App\Repository\CRRepository;
use App\Repository\EventRepository;
use App\Repository\PresenceRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class RDCController extends Controller
{

    /**
     * RDCController constructor.
     * @param UserRepository $userRepository
     * @param EntityManager $em
     * @var ObjectManager
     */
    private $em;

    public function __construct(UserRepository $userRepository, ObjectManager $em)
    {
        $this->UserRepository = $userRepository;
        $this->em =$em;
    }

    /**
     * @Route ("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('rdc/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route ("/infos", name="infos")
     * @Security("is_granted('ROLE_USER')")
     */
    public function infoAction()
    {
        return $this->render('rdc/info.html.twig', [
            'user' => $this->getUser()
    ]);
    }

    /**
     * @Route ("/compte-rendu", name="compteRendu.index")
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Security("is_granted('ROLE_USER')")
     */
    public function compteRenduAction(CRRepository $CRRepository)
    {
        $cr = $CRRepository->findLast();
        $last4 = $CRRepository->findBy([], ['id' => 'desc'], 4, 1);

        return $this->render('rdc/compteRendu.html.twig', array(
            'cr' => $cr,
            'last4' => $last4,
            'user' => $this->getUser()
        ));
    }

    /**
     * @param CRRepository $CRRepository
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Security("is_granted('ROLE_USER')")
     * @Route("/dernier-compte-rendu", name="compteRendu.lastCR")
     */
    public function lastCR(CRRepository $CRRepository)
    {
        $cr = $CRRepository->findLast();

        return $this->redirectToRoute('compteRendu.show', array(
            'id' => $cr->getId()
        ));

    }

    /**
     * @Route("/compte-rendu/{id}", name="compteRendu.show")
     * @Security("is_granted('ROLE_USER')")
     * @return Response
     * @param CR $cr
     * @param Comments $comment
     */
    public function showCR(CR $cr, CommentsRepository $commentsRepository, Request $request)
    {
        $listOfComments = $commentsRepository->findBy(array('cr' => $cr));
        $user = $this->getUser();
        $comment = new Comments();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){

            $comment->setAuthor($this->getUser());
            $comment->setStatut(0);
            $comment->setCr($cr);
            $this->em->persist($comment);
            $this->em->flush();
            $this->addFlash('success', 'Votre Commentaire a été ajouté.');
            return $this->redirectToRoute('compteRendu.show',['id' => $cr->getId()]);
        }

        return $this->render('rdc/showCR.html.twig', array(
            'cr' => $cr,
            'form' => $form->createView(),
            'listOfComments' => $listOfComments,
            'user' => $this->getUser()
        ));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/distribution" , name="distribution.index")
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function distributionAction(EventRepository $eventRepository, Request $request)
    {
        $lastDistribution = $eventRepository->findtoCome();
        $last4 = $eventRepository->findXtoCome();

        return $this->render('rdc/distributions.html.twig', array(
            'lastDistribution' => $lastDistribution,
            'last4' => $last4,
            'user' => $this->getUser()

        ));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/distribution/{id}/add-presence" , name="distribution.add_presence")
     */
    public function addDistributionPresenceAction(Event $event, Request $request, EntityManagerInterface $em, PresenceRepository $presenceRepository)
    {
        $presence = $presenceRepository->findOneByUserAndEvent($this->getUser(), $event) ?? new Presence();

        $form = $this->createForm(PresenceType::class, $presence);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $presence->setUser($this->getUser());
            $presence->setEvent($event);
            $em->persist($presence);
            $em->flush($presence);
            return $this->redirectToRoute('distribution.index');

        }

        return $this->render('rdc/addPresence.html.twig',[
            'form' => $form->createView(),
            'user' => $this->getUser(),
            'presence' => $presence
        ]);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/profile/{id}", name="profile.edit")
     */
    public function profileEditAction(User $user, Request $request)
    {
            $form = $this->createForm(EditUserType::class,  $user);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $this->em->flush();
                $this->addFlash('success', 'Votre profil a bien été modifié.');
                return $this->redirectToRoute('homepage', [
                    'user' => $this->getUser()
                ]);
            }

            return $this->render('rdc/profileEdit.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView()
            ]);
    }

}