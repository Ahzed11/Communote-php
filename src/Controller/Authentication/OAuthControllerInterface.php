<?php

namespace App\Controller\Authentication;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

interface OAuthControllerInterface
{
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse;
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry): ?RedirectResponse;
}