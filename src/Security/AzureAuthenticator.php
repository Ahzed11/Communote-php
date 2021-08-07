<?php


namespace App\Security;


use App\Repository\UserRepository;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
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
use TheNetworg\OAuth2\Client\Provider\AzureResourceOwner;

class AzureAuthenticator extends OAuth2Authenticator
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
    public function supports(Request $request): bool
    {
       return 'oauth_check' === $request->attributes->get('_route') && $request->get('service') === 'azure';
    }

    public function authenticate(Request $request): PassportInterface
    {
        $client = $this->getAzureClient();
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken, function () use ($accessToken, $client) {
                /** @var AzureResourceOwner $azureUser */
                $azureUser = $client->fetchUserFromToken($accessToken);

                return $this->userRepository->findOrCreateFromAzureOAuth($azureUser);
            })
        );
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?RedirectResponse
    {
        $targetPath = $this->getTargetPath($request->getSession(), $firewallName);
        $targetUrl = $targetPath !== null ? $targetPath : $this->router->generate('profile_me');

        return new RedirectResponse($targetUrl);
    }


    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    private function getAzureClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient('azure');
    }
}