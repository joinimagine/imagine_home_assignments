<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookGenre;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin@admin')
        ]);

        $admin->attachRole(Role::getAdminRole());

        $bookGenres = BookGenre::factory(50)->create();

        foreach ($bookGenres as $bookGenre) {

            Book::factory(5)->create([
                'book_genre_id' => $bookGenre->id
            ]);
        }
    }
}
