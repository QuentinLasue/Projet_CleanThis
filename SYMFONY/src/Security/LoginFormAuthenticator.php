<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private RouterInterface $router;
    private LoggerInterface $logger;

    public function __construct(RouterInterface $router, LoggerInterface $logger)
    {
        $this->router = $router;
        $this->logger = $logger;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        // Utilisez la constante LAST_USERNAME ici
        $request->getSession()->set(SecurityBundleSecurity::class, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?RedirectResponse
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            // Log target path for debugging
            $this->logger->info('Redirecting to target path: ' . $targetPath);

            // Ensure the target path is not the login page to avoid loops
            if ($targetPath !== $this->router->generate('app_login')) {
                return new RedirectResponse($targetPath);
            }
        }

        return new RedirectResponse($this->router->generate('app_operation'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('app_login');
    }
}
