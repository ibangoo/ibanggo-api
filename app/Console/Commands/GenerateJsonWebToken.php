<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateJsonWebToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:jwt-token';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成测试专用 Json Web Token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->ask('请输入用户 id');

        if (!(int)$userId) {
            return $this->error('请输入合法的用户 id');
        }

        $user = User::query()->first();
        if (!$user) {
            return $this->error('用户不存在');
        }

        $minutes = 60 * 24 * 365;
        $this->info(auth()->guard()->setTTL($minutes)->fromUser($user));
    }
}
