<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\User;

class ResetPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-password {username} {new-password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password use username params: {username} {new-password}';

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
        try {
            DB::beginTransaction();

            $this->resetPassword();

            DB::commit();
        } catch(ValidationException $e) {
            $this->error('ERROR: ' . $e->getMessage());
            var_dump($e->validator->errors());

            DB::rollBack();
        }
    }

    private function resetPassword()
    {
        printf("Reset password...");
        $newPassword = Hash::make($this->argument('new-password'));

        $username = $this->argument('username');

        $user = User::where('username', $username)->first();

        if(!$user) {
            return $this->error('ERROR: ' . 'Username tidak di temukan.');
        }

        $user->password = $newPassword;
        $user->save();

        printf("\nReset password complete!");
    }
}
