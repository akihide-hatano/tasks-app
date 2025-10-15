<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;


class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /* ---------- 認証まわり ---------- */

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/tasks')->assertRedirect('/login');
        $this->post('/tasks', [])->assertRedirect('/login');
    }
}
