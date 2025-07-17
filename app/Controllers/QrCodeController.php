<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Models\QrCode;
use App\Models\VisitorSession;
class QrCodeController
{
    public function index(Request $request, Response $response)
    {
        $session = new Session();
        if ($session->get('user_id')) {
            return $this->dashboard($request, $response);
        }

        $visitor = new VisitorSession();
        $ip = $visitor->getIp();
        $qrCodeModel = new QrCode();
        $qrcodes = $qrCodeModel->findByIp($ip);

        return (new \App\Core\Router(new Request(), new Response()))->renderView('index', ['qrcodes' => $qrcodes]);
    }

    public function generate(Request $request, Response $response)
    {
        $data = $request->getBody();
        $content = $data['content'];

        $filename = uniqid('qrcode_') . '.png';
        $path = __DIR__ . '/../../storage/' . $filename;

        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($content);
        $qrCodeImage = file_get_contents($qrCodeUrl);
        file_put_contents($path, $qrCodeImage);

        $qrCodeModel = new QrCode();
        $qrCodeModel->content = $content;
        $qrCodeModel->image_path = '/storage/' . $filename;

        $session = new Session();
        if ($userId = $session->get('user_id')) {
            $qrCodeModel->user_id = $userId;
            $qrCodeModel->expires_at = null;
        } else {
            $visitor = new VisitorSession();
            $qrCodeModel->ip_address = $visitor->getIp();
            $qrCodeModel->expires_at = date('Y-m-d H:i:s', strtotime('+1 day'));
        }

        $qrCodeModel->create();

        return $response->redirect('/');
    }

    public function dashboard(Request $request, Response $response)
    {
        $session = new Session();
        if (!$userId = $session->get('user_id')) {
            return $response->redirect('/login');
        }

        $qrCodeModel = new QrCode();
        $qrcodes = $qrCodeModel->findByUserId($userId);

        return (new \App\Core\Router(new Request(), new Response()))->renderView('dashboard', ['qrcodes' => $qrcodes]);
    }

    public function delete(Request $request, Response $response)
    {
        $session = new Session();
        if (!$userId = $session->get('user_id')) {
            return $response->redirect('/login');
        }

        $data = $request->getBody();
        $id = $data['id'];

        $qrCodeModel = new QrCode();
        $qrcode = $qrCodeModel->findById($id);

        if ($qrcode && $qrcode['user_id'] == $userId) {
            unlink(__DIR__ . '/../../public' . $qrcode['image_path']);
            $qrCodeModel->delete($id);
        }

        return $response->redirect('/dashboard');
    }
}
