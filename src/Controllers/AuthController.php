<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\FlashMessage;
use App\Core\Validator;

/**
 * Connexion / deconnexion des employes.
 */
final class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/');
        }

        $this->render('auth/login');
    }

    public function login(): void
    {
        $this->verifyCsrf();

        $email = (string) $this->input('email', '');
        $password = (string) $this->input('password', '');

        $validator = new Validator(
            ['email' => $email, 'password' => $password],
            ['email' => ['required', 'email'], 'password' => ['required']]
        );

        if ($validator->fails() || !Auth::attempt($email, $password)) {
            FlashMessage::error('Adresse email ou mot de passe incorrect.');
            $this->redirect('/login');
        }

        $this->redirect('/');
    }

    public function logout(): void
    {
        $this->verifyCsrf();
        Auth::logout();
        $this->redirect('/');
    }
}
