<?php
// src/Security/ApiKeyAuthenticator.php
namespace App\Security;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class CustomAuth extends AbstractAuthenticator {

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool {
        return ($request->getPathInfo() === '/login' && $request->isMethod('POST'));
    }

    public function authenticate(Request $request): Passport {
        $form = $request->toArray();
        $login = $form['username'];
        $password = $form['password'];

        $passport = new Passport(
            new UserBadge($login),
            new PasswordCredentials($password)
        );

        return $passport;
    }


    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response {
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
            'role' => 2
        );
        $data['userData'] = $userData;

        return new Response(json_encode($data), Response::HTTP_OK, ['content-type' => 'application/json']);
    }
}

?>