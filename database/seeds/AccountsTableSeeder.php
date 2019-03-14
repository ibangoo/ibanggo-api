<?php

use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = app(Faker\Generator::class);

        $items = factory(\App\Models\Account::class)
            ->times(10)
            ->make()
            ->each(function ($item, $index) use ($faker) {
                if ($index === 0) {
                    $item->area_code = '0086';
                    $item->phone = 15089940544;
                    $item->name = '卢艺源';
                    $item->email = 'sebastiankennedy@foxmail.com';
                    $item->status = 'enable';
                    $item->login_times = $faker->numberBetween(1, 99);
                    $item->last_login_ip = $faker->ipv4;
                }
            })
            ->makeVisible(['password', 'pay_password'])
            ->toArray();

        \App\Models\Account::query()->insert($items);
    }
}
