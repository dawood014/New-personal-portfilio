<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
Artisan::command('portfolio:admin {email}', function (string $email) {
 $name = $this->ask('Administrator name', 'Portfolio Admin');
 $password = $this->secret('Choose a strong password');
 if (!$password || strlen($password)<12) { $this->error('Password must be at least 12 characters.'); return; }
 $user = User::updateOrCreate(['email'=>$email],['name'=>$name,'password'=>Hash::make($password),'is_admin'=>true]);
 $this->info('Administrator created/updated: '.$user->email);
})->purpose('Create or update the portfolio administrator');
