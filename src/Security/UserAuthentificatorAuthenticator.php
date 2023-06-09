<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserAuthentificatorAuthenticator extends AbstractLoginFormAuthenticator
{   public $userRepository;

    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator,UserRepository $userRepository)
    { $this->userRepository=$userRepository;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
         if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        } 
       // dd($request->request->getParameters());
       $user=$this->userRepository->findOneBy(['email'=>$request->request->all()['email']]);
      // dd($request->request->all()['email']);
      $role=$user->getRoles();
       if ( in_array("ROLE_MEDECIN",$role)) {
        return new RedirectResponse($this->urlGenerator->generate('app_dashboard_medecin'));
       }
       if ( in_array("ROLE_AGENT",$role)) {
        return new RedirectResponse($this->urlGenerator->generate('app_dashboard_agent'));
       }
       if ( in_array("ROLE_ADMIN",$role)) {
        //return new RedirectResponse($this->urlGenerator->generate('app_dashboard_agent'));
        return new RedirectResponse($this->urlGenerator->generate('app_admin_dashboard'));
       }

        // For example:
       // return new RedirectResponse($this->urlGenerator->generate('app_admin_dashboard'));
       // return new RedirectResponse($this->urlGenerator->generate('app_dashboard_agent'));
     
      
       // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
