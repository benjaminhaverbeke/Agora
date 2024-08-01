<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
public function __construct(
private UrlGeneratorInterface $urlGenerator,
) {
}

public function start(Request $request, ?AuthenticationException $authException = null): RedirectResponse
{
// add a custom flash message and redirect to the login page
$request->getSession()->getFlashBag()->add('note', 'Cette page est réservé aux administrateurs');

return new RedirectResponse($this->urlGenerator->generate('home'));
}
}