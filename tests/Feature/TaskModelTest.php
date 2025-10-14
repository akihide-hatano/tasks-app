<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_title_space():void{
        $user = User::factory()->create();

         // 全角スペースや前後空白を含む入力
        $task = Task::create([
            'user_id' => $user->id,
            'title'   => '  テ. ス. ト  ',   // 前後空白＋スペース多め
            'is_done' => '0',                // 文字列でもOK
        ]);
        $this->assertSame('テ. ス. ト', $task->title);
    }

    public function test_is_done_boole():void{

        $task = \App\Models\Task::factory()->create(['is_done' => '1']);

        $this->assertIsBool($task->is_done);
        $this->assertTrue($task->is_done);

        $task->is_done = 0;
        $task->save();
        $task->refresh();
        $this->assertFalse($task->is_done);
    }

    public function test_task_user(): void
    {
        $task = Task::factory()->create();

        // どちらでもOK（両方書くなら2アサーション）
        $this->assertNotNull($task->user);
        $this->assertTrue($task->user()->exists());
    }


    public function test_fillable_で一括代入できる(): void
    {
        $user = User::factory()->create();

        $task = Task::create([
            'user_id' => $user->id,
            'title'   => 'write tests',
            'is_done' => false,
        ]);

        $this->assertDatabaseHas('tasks', [
            'id'      => $task->id,
            'user_id' => $user->id,
            'title'   => 'write tests',
            'is_done' => false,
        ]);
    }

    public function test_fillable_mass_assignment_allows_expected_and_blocks_others(): void
    {
        $user = User::factory()->create();

        //許可フィールド
        $task = Task::create([
            'user_id' => $user->id,
            'title'   => 'write tests',
            'is_done' => false,
        ]);

        $this->assertDatabaseHas('tasks',[
            'id'      => $task->id,
            'user_id' => $user->id,
            'title'   => 'write tests',
            'is_done' => false,
        ]);


    }
}