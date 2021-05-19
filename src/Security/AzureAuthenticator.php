<?php


namespace App\Security;


use App\Repository\UserRepository;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Client\Provider\AzureClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use TheNetworg\OAuth2\Client\Provider\AzureResourceOwner;

class AzureAuthenticator extends SocialAuthenticator
{
    use TargetPathTrait;

    private RouterInterface $router;
    private ClientRegistry $clientRegistry;
    private UserRepository $userRepository;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(RouterInterface $router, ClientRegistry $clientRegistry, UserRepository $userRepository,
                                UrlGeneratorInterface $urlGenerator)
    {
        $this->router = $router;
        $this->clientRegistry = $clientRegistry;
        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse($this->router->generate('login'));
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request): bool
    {
       return 'oauth_check' === $request->attributes->get('_route') && $request->get('service') === 'azure';
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->clientRegistry->getClient('azure'));
    }

    /**
     * @inheritDoc
     * @param $credentials AccessToken
     * @param UserProviderInterface $userProvider
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        /** @var AzureResourceOwner $azureUser */
        $azureUser = $this->getAzureClient()->fetchUserFromToken($credentials);
        return $this->userRepository->findOrCreateFromOAuth($azureUser);
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): RedirectResponse
    {
        return new RedirectResponse('/auth/login');
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    private function getAzureClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient('azure');
    }
}