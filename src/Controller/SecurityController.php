<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\UpdatePasswordType;
use App\Form\UserRegistrationType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var \Twig_Environment
     */
    private $twig_Environment;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     *
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer, \Twig_Environment $twig_Environment)
    {

        $this->em = $em;
        $this->mailer = $mailer;
        $this->twig_Environment = $twig_Environment;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {

    }

    /**
     * @Route("/new_bene_rdcNation", name="new_bene_nation")
     */
    public function newUser(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash('success', 'Ton compte a bien été crée.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('security/newUser.html.twig', [
            'form' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/forgot_Password", name="forgot_password")
     * @throws \Exception
     */
    public function forgotPasswordAction(Request $request, UserRepository $userRepository)
    {

        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $user = $userRepository->findOneBy(['email' => $data['email']]);
            $user->setToken(md5(random_bytes(10)));
            $this->em->persist($user);
            $this->em->flush();
            $message = (new \Swift_Message('RDC Nation - Demande de nouveau mot de passe'))
                ->setFrom('rdc@nation.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->twig_Environment->render('email/resetPasswordEmail.html.twig',
                        ['user' => $user]),
                    'text/html'
                );
            $this->mailer->send($message);
            $this->addFlash('success', 'Mail de réinitialisation de mot de passe envoyé.');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('security/forgotPassword.html.twig', [
            'form' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/reset_Password/{token}", name="reset_password")
     */
    public function resetPasswordAction(User $user, Request $request)
    {
      $form = $this->createForm(UpdatePasswordType::class);
      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){
          $data = $form->getData();
          $user->setPassword($this->encoder->encodePassword($user, $data['password']));
          $user->setToken(null);
          $this->em->persist($user);
          $this->em->flush();
          $this->addFlash('success', 'Votre mot de passe a bien été changé.');
          return $this->redirectToRoute('app_login');

      }
      return $this->render('security/resetPassword.html.twig', array(
          'form' => $form->createView(),
          'user' => $user
      ));
    }


}
