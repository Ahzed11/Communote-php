<?php

namespace App\Security\EntryPoint;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class OAuthEntryPoint implements \Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface
{

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null) : Response
    {
        return new RedirectResponse("/login");
    }
}