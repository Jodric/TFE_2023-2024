<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // Exclure la migration create_users_table en ne l'appelant pas ici
            // CreateUsersTableSeeder::class,
            // Vous pouvez ajouter d'autres seeders ici si nécessaire
        ]);
    }
}
