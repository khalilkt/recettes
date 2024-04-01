<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Hash;


class CreateSuperuser extends Command
{
    protected $signature = 'user:create-superuser';
    protected $description = 'Create a superuser';


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
        $name = $this->ask('Enter the name:');
        $email = $this->ask('Enter the email:');
        $password = $this->secret('Enter the password:');
        // sys_types_user_id is the foreign key of the sys_types_users table

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'phone' => null,
            'sys_types_user_id' => 1 ,
            'password' => Hash::make($password),
        ]);

        $user->save();

        $this->info('Superuser created successfully!');
    }
}
