<?php

namespace App\Controller\Authentication;

use App\Controller\BaseController;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AzureOAuthController extends AbstractOauthController
{
    public function __construct()
    {
        parent::__construct('azure_main', [
            'openid',
            'email',
            'profile'
        ]);
    }

    #[Route(path: "/connect/azure", name: "connect_azure_start")]
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        return parent::connectAction($clientRegistry);
    }

    #[Route(path: "/connect/azure/check", name:"connect_azure_check")]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry): ?RedirectResponse {
        return parent::connectCheckAction($request, $clientRegistry);
    }
}
