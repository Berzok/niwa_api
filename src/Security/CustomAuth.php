<?php
// src/Security/ApiKeyAuthenticator.php
namespace App\Security;

use App\Entity\User;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class CustomAuth extends AbstractLoginFormAuthenticator {

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool {
        return false;
        //return ($request->getPathInfo() === '/login' && $request->isMethod('POST'));
    }

    /**
     * @param Request $request
     * @return Passport
     */
    public function authenticate(Request $request): Passport {
        $form = $request->toArray();
        $login = $form['username'];
        $password = $form['password'];

        return new Passport(
            new UserBadge($login),
            new PasswordCredentials($password)
        );
    }


    /**
     * @inheritDoc
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        return new Response($exception->getMessage(), Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Called when authentication executed and was successful!
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the last page they visited.
     *
     * If you return null, the current request will continue, and the user
     * will be authenticated. This makes sense, for example, with an API.
     * @throws Exception
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response {
        /** @var User $user */
        $user = $token->getUser();

        // TODO:
        //  Éditer le token, et le renvoyer avec la réponse (enlever le champ password, par exemple)
        $data = array(
            'status' => 1,
            'token' => password_hash($user->getPassword(), PASSWORD_BCRYPT)
            //            'token' => bin2hex(random_bytes(16))
        );
        $userData = array(
            'id' => $user->getId(),
            'username' => $user->getUserIdentifier(),
            'role' => $user->getRole()->getId(),
            'creation' => $user->getRole()->getCanCreate(),
            'deletion' => $user->getRole()->getCanDelete()
        );
        $data['userData'] = $userData;

        return new Response(json_encode($data), Response::HTTP_OK, ['content-type' => 'application/json']);
    }

    /**
     * Return the URL to the login page.
     */
    protected function getLoginUrl(Request $request): string {
        return '/login';
    }
}