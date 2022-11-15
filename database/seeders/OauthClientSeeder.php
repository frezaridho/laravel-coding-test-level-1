<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OauthClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->updateOrInsert(
            [
                'id' => 1,
            ],
            [
                'name' => 'Event App Personal Access Client',
                'secret' => '40bMqYiu7tNbqrGQ2JzB0WwMuXQgDZOuBvO04P7q',
                'provider' => NULL,
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('oauth_clients')->updateOrInsert(
            [
                'id' => '2',
            ],
            [
                'name' => 'Event App Password Grant Client',
                'secret' => 'yjwVXCTAmVr9j16bhvUeYi6NQeUNX5lCPd7I8vpB',
                'provider' => 'users',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('oauth_personal_access_clients')->updateOrInsert(
            [
                'id' => '1',
            ],
            [
                'client_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
