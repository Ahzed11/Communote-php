<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Security\Authenticator;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\MicrosoftClient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: "/auth")]
class AuthController extends BaseController
{
    /*
    #[Route(path: "/register", name: "register")]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em,
                            GuardAuthenticatorHandler $guardAuthenticatorHandler, Authenticator $authenticator): Response
    {
        $form = $this->createForm(RegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $user->setFirstName(ucfirst($user->getFirstName()));
            $user->setLastName(ucfirst($user->getFirstName()));
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $user->getPlainPassword()
            ));
            $user->eraseCredentials();

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
    */

    #[Route(path: "/login", name: "login")]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: "/connect/azure", name: "azure_connect")]
    public function connect(ClientRegistry $clientRegistry): RedirectResponse
    {
        $client = $clientRegistry->getClient('azure');
        return $client->redirect(['openid', 'email', 'profile'], []);
    }

    #[Route(path: "/logout", name: "logout")]
    public function logout()
    {

    }
}
