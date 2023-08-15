<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    // public function test_save_task_in_db()
    // {
    //     $response = $this->post('task/save-task', [
    //         'name' => 'abcd',
    //         'status' => 'completed'
    //     ]);

    //     $this->assertAuthenticated();
    //     $response->assertRedirect('/task/tasks');
    // }
    public function test_create_task()
    {
        $response = User::make([
            'name' => 'abcd',
            'status' => 'completed'
        ]);

        $this->assertTrue($response->name ? true : false);
    }
}
