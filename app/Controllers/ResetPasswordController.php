<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Models\User;
use App\Models\PasswordReset;

class ResetPasswordController
{
    public function forgotPassword(Request $request, Response $response)
    {
        if ($request->getMethod() === 'post') {
            $data = $request->getBody();
            $email = $data['email'];

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $passwordReset = new PasswordReset();
                $passwordReset->user_id = $user['id'];
                $passwordReset->token = $token;
                $passwordReset->expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $passwordReset->create();

                // In a real application, you would send an email here.
                // For this example, we'll just show the link.
                $url = "http://localhost:8080/reset-password?token={$token}";
                (new Session())->setFlash('success', "Password reset link: <a href='{$url}'>{$url}</a>");
            } else {
                (new Session())->setFlash('error', 'No user found with that email address.');
            }
        }

        return (new \App\Core\Router(new Request(), new Response()))->renderView('forgot');
    }

    public function resetPassword(Request $request, Response $response)
    {
        $token = $_GET['token'] ?? null;
        $passwordResetModel = new PasswordReset();
        $reset = $passwordResetModel->findByToken($token);

        if (!$reset) {
            (new Session())->setFlash('error', 'Invalid or expired token.');
            return $response->redirect('/forgot-password');
        }

        if ($request->getMethod() === 'post') {
            $data = $request->getBody();
            $validator = new Validator();
            $rules = [
                'password' => ['required', 'min:8', 'confirmed'],
            ];

            if ($validator->validate($data, $rules)) {
                $userModel = new User();
                $user = $userModel->findById($reset['user_id']);
                $userModel->id = $user['id'];
                $userModel->password = $data['password'];
                // This is a simplified update. A real app would have a dedicated update method.
                $conn = (new \App\Core\Database())::getInstance()->getConnection();
                $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
                $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':id', $user['id']);
                $stmt->execute();


                $passwordResetModel->delete($reset['id']);
                (new Session())->setFlash('success', 'Password has been reset.');
                return $response->redirect('/login');
            } else {
                (new Session())->setFlash('errors', $validator->getErrors());
            }
        }

        return (new \App\Core\Router(new Request(), new Response()))->renderView('reset-password', ['token' => $token]);
    }
}
