<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AddAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:admin {--email=} {--username=} {--role=} {--phone=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $username = $this->option('username');
        $password = $this->option('password');
        $role = $this->option('role');
        $email = $this->option('email');
        $phone = $this->option('phone');

        if (!empty($username)) {
            $this->line('Create Admin with username='.$username);

            User::create([
                'username' => $username,
                'password' => Hash::make($password),
                'role' => !empty($role) ? $role : 'admin'
            ]);
        } else if (!empty($phone)) {
            $this->line('Create Admin with phone='.$phone);

            User::create([
                'phone' => $phone,
                'password' => Hash::make($password),
                'role' => !empty($role) ? $role : 'admin'
            ]);
        } else if (!empty($email)) {
            $this->line('Create Admin with email='.$email);

            User::create([
                'email' => $email,
                'password' => Hash::make($password),
                'role' => !empty($role) ? $role : 'admin'
            ]);
        }

        $this->line('Done');
    }
}
