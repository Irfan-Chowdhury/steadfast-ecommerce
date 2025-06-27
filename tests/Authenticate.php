<?php

namespace Tests;

use App\Models\User;

trait Authenticate
{
    public function userAuthenticated()
    {
        User::factory()->create();

        $this->post('/login',[
            'email' => 'admin@gmail.com',
            'password' => 'admin@gmail.com',
        ]);

        $this->assertAuthenticated();
    }
}
