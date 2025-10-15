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

    /*----------- index --------------*/
    
    public function test_index_shows_only_my_tasks():void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();

        Task::factory()->for($me)->count(2)->create(['title'=>'mine']);
        Task::factory()->for($other)->count(1)->create(['title'=>'other']);

        $res = $this->actingAs($me)->get('/tasks');

        $res->assertOk()
            ->assertViewIs('tasks.index')
            ->assertSee('mine')
            ->assertDontSee('others'); // 他人のは出ない

    }
}
