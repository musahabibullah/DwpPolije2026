<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class SetupDwp extends Command
{
    protected $signature = 'setup:dwp {--force : Force fresh installation}';
    protected $description = 'Setup DWP dengan menjalankan composer, npm, migrasi, seeder, dan setup Filament Shield';

    // ASCII art yang lebih kecil dan profesional
    private $headerArt = "
  __                                     _          __     
 /\ \                                  /' \       /'__`\   
 \_\ \  __  __  __  _____       __  __/\_, \     /\ \/\ \  
 /'_` \/\ \/\ \/\ \/\ '__`\    /\ \/\ \/_/\ \    \ \ \ \ \ 
/\ \L\ \ \ \_/ \_/ \ \ \L\ \   \ \ \_/ | \ \ \  __\ \ \_\ \
\ \___,_\ \___x___/'\ \ ,__/    \ \___/   \ \_\/\_\\ \____/
 \/__,_ /\/__//__/   \ \ \/      \/__/     \/_/\/_/ \/___/ 
                      \ \_\                                
                       \/_/                                                                                                                                
    ";

    // Color codes for terminal output
    private $colors = [
        'reset' => "\033[0m",
        'black' => "\033[30m",
        'red' => "\033[31m",
        'green' => "\033[32m",
        'yellow' => "\033[33m",
        'blue' => "\033[34m",
        'magenta' => "\033[35m",
        'cyan' => "\033[36m",
        'white' => "\033[37m",
        'bold' => "\033[1m",
        'dim' => "\033[2m",
    ];

    public function handle()
    {
        $this->displayHeader();

        // Check if force option is used
        $force = $this->option('force');

        $this->simpleTask('composer update', function () {
            return $this->runProcess('composer update');
        });

        $this->simpleTask('npm install', function () {
            return $this->runProcess('npm install');
        });

        $this->simpleTask('npm build', function () {
            return $this->runProcessWithOutput('npm run build');
        });

        // Database setup
        $databaseExists = false;
        try {
            $databaseExists = Schema::hasTable('migrations');
        } catch (\Exception $e) {
            // Database not found or empty
        }

        if ($force || $databaseExists) {
            // Jika --force digunakan ATAU database sudah ada, maka jalankan migrate:fresh
            $this->simpleTask('Database migrate:fresh', function () {
                $this->call('migrate:fresh', ['--force' => true]);
                return true;
            });
        } else {
            // Jika database kosong/baru, jalankan migrate biasa
            $this->simpleTask('Database migrate', function () {
                $this->call('migrate', ['--force' => true]);
                return true;
            });
        }

        // Run seeders
        $seeders = ['JurusanSeeder', 'JabatanSeeder', 'PenerimaSeeder', 'BendaharaSeeder', 'UserSeeder', 'PinisepuhSeeder'];
        foreach ($seeders as $seeder) {
            $this->simpleTask("Seed: $seeder", function () use ($seeder) {
                $this->call('db:seed', ['--class' => $seeder, '--force' => true]);
                return true;
            });
        }

        // Setup Filament Shield
        $this->simpleTask('Shield install', function () {
            $this->call('shield:install');
            return true;
        });

        $this->simpleTask('Shield generate', function () {
            $this->call('shield:generate', ['--all' => true]);
            return true;
        });

        // Completion message
        $this->displaySuccessMessage();

        return 0;
    }

    private function displayHeader()
    {
        $this->line($this->colors['red'] . $this->headerArt . $this->colors['reset']);
    }

    private function simpleTask($title, $callback)
    {
        $this->output->write(" " . $this->colors['blue'] . "•" . $this->colors['reset'] . " {$title} ");
        
        // Execute the callback
        $result = $callback();

        // Show the result
        if ($result) {
            $this->output->writeln($this->colors['green'] . "✓" . $this->colors['reset']);
        } else {
            $this->output->writeln($this->colors['red'] . "✗" . $this->colors['reset']);
        }

        return $result;
    }

    private function displaySuccessMessage()
    {
        $this->line('');
        $this->line($this->colors['green'] . " ✓ Installation complete" . $this->colors['reset']);
        $this->line($this->colors['dim'] . " Run 'php artisan serve' to start the application" . $this->colors['reset']);
        $this->line('');
    }

    private function runProcess($command)
    {
        $process = proc_open($command, [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"],
        ], $pipes);

        if (is_resource($process)) {
            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            $result = proc_close($process);

            return $result === 0;
        }

        return false;
    }

    private function runProcessWithOutput($command)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Untuk Windows
            $command = 'cmd /c ' . $command;
        }

        $descriptorspec = [
            0 => ["pipe", "r"],  // stdin
            1 => ["pipe", "w"],  // stdout
            2 => ["pipe", "w"]   // stderr
        ];

        $process = proc_open($command, $descriptorspec, $pipes, null, null, ['bypass_shell' => false]);
        
        if (is_resource($process)) {
            // Tutup stdin
            fclose($pipes[0]);

            // Baca output secara tidak-blocking
            stream_set_blocking($pipes[1], 0);
            stream_set_blocking($pipes[2], 0);

            // Tunggu proses selesai
            $status = proc_get_status($process);
            while ($status['running']) {
                usleep(100000); // Tunggu 0.1 detik
                $status = proc_get_status($process);
            }

            // Tutup pipe lainnya
            fclose($pipes[1]);
            fclose($pipes[2]);

            // Dapatkan exit code
            $exit_code = proc_close($process);
            
            // Jika exit code adalah 0, berarti berhasil
            return $exit_code === 0 || $status['exitcode'] === 0;
        }

        return false;
    }
}