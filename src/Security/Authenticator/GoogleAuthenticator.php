<?php


namespace App\Security\Authenticator;


use App\Repository\UserRepository;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use TheNetworg\OAuth2\Client\Provider\googleResourceOwner;

class GoogleAuthenticator extends MyOAuth2Authenticator
{
    use TargetPathTrait;

    public function __construct(RouterInterface $router, ClientRegistry $clientRegistry, UserRepository $userRepository,
                                UrlGeneratorInterface $urlGenerator)
    {
        parent::__construct(
            $router,
            $clientRegistry,
            $userRepository,
            $urlGenerator,
            'connect_google_check',
            'google_main'
        )
        ;
    }
}