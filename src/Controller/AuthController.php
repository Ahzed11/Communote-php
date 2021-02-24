<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Security\Authenticator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: "/auth")]
class AuthController extends AbstractController
{
    #[Route(path: "/register", name: "register")]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em,
                            GuardAuthenticatorHandler $guardAuthenticatorHandler, Authenticator $authenticator): Response
    {
        $form = $this->createForm(RegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /**
             * @var $user User
             */
            $user = $form->getData();

            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $user->getPlainPassword()
            ));
            $user->eraseCredentials();
            $user->setCreatedAt(new DateTime("NOW"));
            $user->setUpdatedAt($user->getCreatedAt());

            $em->persist($user);
            $em->flush();

            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                "main"
            );
        }

        return $this->render("security/register.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    #[Route(path: "/login", name: "login")]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: "/logout", name: "logout")]
    public function logout()
    {

    }
}
