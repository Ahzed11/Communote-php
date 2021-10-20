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

abstract class MyOAuth2Authenticator extends OAuth2Authenticator
{
    use TargetPathTrait;

    private RouterInterface $router;
    private ClientRegistry $clientRegistry;
    private UserRepository $userRepository;
    private UrlGeneratorInterface $urlGenerator;
    private string $checkPath;
    private string $client;


    public function __construct(RouterInterface $router, ClientRegistry $clientRegistry, UserRepository $userRepository,
                                UrlGeneratorInterface $urlGenerator, string $checkPath, string $client)
    {
        $this->router = $router;
        $this->clientRegistry = $clientRegistry;
        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;
        $this->checkPath = $checkPath;
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request): bool
    {
       return $request->attributes->get('_route') === $this->checkPath;
    }

    public function authenticate(Request $request): PassportInterface {
        $client = $this->clientRegistry->getClient($this->client);
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken, function () use ($accessToken, $client) {
                $user = $client->fetchUserFromToken($accessToken);

                return $this->userRepository->findOrCreate($user, $this->client);
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
}