<?php

namespace Sebbaum\Legal\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Sebbaum\Legal\Models\Lawyer;

class CreateLawyer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'legal:create-lawyer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Lawyer';

    /**
     * Create a new Lawyer.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('Create a new Lawyer account for editing legal documents.');

        $answers = [];
        $title = $this->ask('Title (optional)', false);
        $answers['title'] = $title === false ? '' : $title;
        $answers['firstname'] = $this->ask('First name');
        $answers['surname'] = $this->ask('Surename');
        $answers['email'] = $this->ask('Email');
        $answers['password'] = bcrypt($this->secret('Password'));

        Validator::make($answers,
            [
                'firstname' => 'required',
                'surname' => 'required',
                'email' => 'required|email|unique:lawyers',
                'password' => 'required'
            ]
        )->validate();

        Lawyer::create($answers);

        // TODO: use a one time password (check via middleware)

        // TODO: send an email

        return true;
    }
}
