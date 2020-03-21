<?php

use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for($x=0; $x<20; $x++)
        {
            \App\Components\Activities\Models\Activity::query()->create([
                'user_id' => 1,
                'context' => \App\Components\Activities\Models\Activity::CONTEXT_ADMIN,
                'details' => $faker->text,
            ]);
        }
    }
}
