<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Models\User;

class AuthController
{
    public function register(Request $request, Response $response)
    {
        if ($request->getMethod() === 'post') {
            $data = $request->getBody();
            $validator = new Validator();
            $rules = [
                'name' => ['required'],
                'email' => ['required', 'email'],
                'password' => ['required', 'min:8', 'confirmed'],
            ];

            if ($validator->validate($data, $rules)) {
                $user = new User();
                if ($user->findByEmail($data['email'])) {
                    (new Session())->setFlash('error', 'Email already exists.');
                    return $response->redirect('/register');
                }

                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = $data['password'];

                if ($user->create()) {
                    (new Session())->set('user_id', $user->id);
                    return $response->redirect('/dashboard');
                }
            } else {
                (new Session())->setFlash('errors', $validator->getErrors());
            }
        }

        return (new \App\Core\Router(new Request(), new Response()))->renderView('register');
    }

    public function login(Request $request, Response $response)
    {
        if ($request->getMethod() === 'post') {
            $data = $request->getBody();
            $validator = new Validator();
            $rules = [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ];

            if ($validator->validate($data, $rules)) {
                $userModel = new User();
                $user = $userModel->findByEmail($data['email']);

                if ($user && password_verify($data['password'], $user['password'])) {
                    (new Session())->set('user_id', $user['id']);
                    session_regenerate_id(true);
                    return $response->redirect('/dashboard');
                } else {
                    (new Session())->setFlash('error', 'Invalid credentials.');
                }
            } else {
                (new Session())->setFlash('errors', $validator->getErrors());
            }
        }

        return (new \App\Core\Router(new Request(), new Response()))->renderView('login');
    }

    public function logout(Request $request, Response $response)
    {
        (new Session())->remove('user_id');
        session_destroy();
        return $response->redirect('/');
    }
}
