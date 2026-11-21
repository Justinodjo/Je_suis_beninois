<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    protected $signature = 'app:create-admin {email} {name=Administrateur} {--password=}';

    protected $description = 'Crée (ou met à jour) un compte administrateur sans toucher aux autres données';

    public function handle(): int
    {
        $password = $this->option('password') ?: $this->secret('Mot de passe (12 caractères minimum)');

        $validator = Validator::make(
            ['email' => $this->argument('email'), 'password' => $password],
            ['email' => 'required|email', 'password' => 'required|string|min:12']
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        // Le cast 'hashed' du modèle User s'occupe du hachage.
        $user = User::updateOrCreate(
            ['email' => $this->argument('email')],
            [
                'name'             => $this->argument('name'),
                'password'         => $password,
                'role'             => 'admin',
                'statut'           => 'actif',
                'date_inscription' => now(),
            ]
        );

        $this->info("Administrateur prêt : {$user->email}");

        return self::SUCCESS;
    }
}