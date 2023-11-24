<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function authenticate()
    {
        $user = User::create([
            'name' => 'mohye',
            'email' => 'keth@gmail.com',
            'password' => Hash::make('123456789'),
        ]);

        $token = JWTAuth::fromUser($user);
        return $token;
    }


    public function test_making_an_api_request(): void
    {
        $note = [
            "title" => "management",
            "content" => "how to management"
        ];

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson(route('create.note'), $note);

        $response->assertStatus(200);
    }


    public function test_making_an_api_request_to_list_all_notes(): void
    {
        Note::create([
            "title" => "management",
            "content" => "how to management"
        ]);


        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson(route('notes.all'));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }


    public function test_making_an_api_request_to_update_note(): void
    {
        $note = Note::create([
            "title" => "management",
            "content" => "how to management"
        ]);


        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->putJson(route('update.note', ['note' => $note->id]), [
            "title" => "work",
        ]);

        $response->assertStatus(200);
        $this->assertEquals('Work', $note->title);
    }

    public function test_making_an_api_request_to_show_note(): void
    {
        $note = Note::create([
            "title" => "management",
            "content" => "how to management"
        ]);


        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson(route('show.note', ['note' => $note->id]));

        $response->assertStatus(200);
        $this->assertEquals('management', $response->json()['data']['title']);
    }

    public function test_making_an_api_request_to_delete_note(): void
    {
        $note = Note::create([
            "title" => "management",
            "content" => "how to management"
        ]);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson(route('delete.note', ['note' => $note->id]));

        $response->assertStatus(200);
        $this->assertEquals(0, $note->count());
    }

    public function test_making_an_api_request_to_list_notes_for_certain_user()
    {
        Note::create([
            "title" => "management",
            "content" => "how to management",
            "user_id" => 1
        ]);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson(route('user.all', ['id' => 1]));

        $response->assertStatus(200)
            ->assertJson([
                "success" => "Notes was Retrieved Successfully"
            ]);
    }
}
