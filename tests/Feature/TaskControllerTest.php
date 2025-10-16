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

    /* ---------- store ---------- */
    public function test_store_creates_task_and_normalizes_title(): void
    {
        $me = User::factory()->create();

        $res = $this->actingAs($me)->post('/tasks',[
            'title'   => '  テ  ス  ト  ',  // 前後空白＋全角スペース
        ]);

        $res->assertRedirect(route('tasks.index'))
            ->assertSessionHas('status','Task created.');

        $this->assertDatabaseHas('tasks', [
            'user_id' => $me->id,
            'title'   => 'テ ス ト',   // ミューテータの正規化が効く
            'is_done' => false,        // デフォルト false
        ]);
    }

    public function test_store_validation_title_requreid_max():void{

        $me = User::factory()->create();
         // title なし → 422 相当。Webはリダイレクト+errors。
        $this->actingAs($me)->from('/tasks/create')
            ->post('/tasks',[/* no title*/])
            ->assertRedirect('/tasks/create')
            ->assertSessionHasErrors(['title']);

        //101文字はNG
        $tooLong= str_repeat('a',101);
                $this->actingAs($me)->from('/tasks/create')
                ->post('/tasks', ['title' => $tooLong])
                ->assertRedirect('/tasks/create')
                ->assertSessionHasErrors(['title']);
        $this->assertDatabaseMissing('tasks',[
            'user_id' => $me->id,
            'title'   => null, // or '', 送信してないならこの行は省略でもOK
        ]);

        //100文字はOK
        $ok = str_repeat('a',100);
                $this->actingAs($me)->post('/tasks', ['title' => $ok])
                ->assertRedirect(route('tasks.index'));
    }

    /* ---------- show / edit ---------- */
    public function test_show_my_task_is_ok_but_others_is_forbidden(): void{

        $me    = User::factory()->create();   // 1人目
        $other = User::factory()->create();   // 2人目

        $mine = Task::factory()->for($me)->create();
        $others = Task::factory()->for($other)->create();

        $this->actingAs($me)->get("/tasks/{$mine->id}")
            ->assertOk()->assertViewIs('tasks.show');

        // コントローラ実装は abort(403) なので 403 を期待
        $this->actingAs($me)->get("/tasks/{$others->id}")
            ->assertStatus(403);
    }


    /* ---------- udtate ---------- */
    public function test_update_patch_only_set_fields():void{

        $me = User::factory()->create();
        $task = Task::factory()->for($me)->create([
            'title' => 'old',
            'is_done' => false,
        ]);

        //is_doneだけtrueに、titleは据え置き
        $res = $this->actingAs($me)->patch("/tasks/{$task->id}", [
            'title'=>$task->title,
            'is_done' => true,
        ]);

        $res->assertRedirect(route('tasks.index'))
            ->assertSessionHas('status','Task updated.');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'old',
            'is_done' => true,
        ]);
    }
}
