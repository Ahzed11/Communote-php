<?php

namespace App\Controller\Authentication;

use App\Controller\BaseController;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class GoogleOAuthController extends AbstractOauthController
{
    public function __construct()
    {
        parent::__construct('google_main', [
            'openid',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile'
        ]);
    }

    #[Route(path: "/connect/google", name: "connect_google_start")]
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        return parent::connectAction($clientRegistry);
    }

    #[Route(path: "/connect/google/check", name:"connect_google_check")]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry): ?RedirectResponse {
        return parent::connectCheckAction($request, $clientRegistry);
    }
}
