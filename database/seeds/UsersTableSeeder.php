<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $items = factory(\App\Models\User::class)
            ->times(10)
            ->make()
            ->each(function ($item, $index) {
                $item->account_id = $index++;
                if ($index === 0) {
                    $item->nickname = '路易斯.爱德华';
                    $item->gender = 'male';
                }
            })
            ->toArray();

        \App\Models\User::query()->insert($items);
    }
}
