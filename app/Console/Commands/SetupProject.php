<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class SetupProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup-project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Запуск настройки проекта...');

        $commands = [
            'composer install',
            'cp .env.example .env',
            'php artisan key:generate',
            'php artisan migrate --seed',
            'npm install && npm run dev',
        ];

        foreach ($commands as $command) {
            $this->info("Выполняется: $command");
            $process = Process::fromShellCommandline($command);
            $process->setTimeout(600); // 10 минут
            $process->run(fn ($type, $buffer) => $this->output->write($buffer));
        }

        $this->info('✅ Проект успешно настроен!');
        return Command::SUCCESS;
    }
}
