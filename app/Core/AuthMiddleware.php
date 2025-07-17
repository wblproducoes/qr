<?php

namespace App\Core;

class AuthMiddleware
{
    public function handle()
    {
        if (!(new Session())->get('user_id')) {
            (new Response())->redirect('/login');
            exit;
        }
    }
}
