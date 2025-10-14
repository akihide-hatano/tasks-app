<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function is_done_boole():void{
        $task = Task::factory()->create(['is_done'=>'1']);

        $this->assertIsBool($task->is_done);
        $this->assertTrue($task->is_done);

        $task->is_done = 0;
        $task->save();
        $task->refresh();
        $this->assertFalse($task->is_done);
    }
}
