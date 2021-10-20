<?php

namespace App\Controller\Authentication;

use App\Controller\BaseController;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractOauthController extends BaseController implements OAuthControllerInterface
{
    private string $client;
    private array $scopes;

    public function __construct(string $client, array $scopes)
    {
        $this->client = $client;
        $this->scopes = $scopes;
    }

    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        $client = $clientRegistry->getClient($this->client);
        return $client->redirect($this->scopes, []);
    }

    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry): ?RedirectResponse
    {
        return null;
    }
}