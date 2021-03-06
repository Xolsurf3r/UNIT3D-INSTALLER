<?php

namespace App\Installer\Prerequisites;

use App\Installer\BaseInstaller;

class Prerequisites extends BaseInstaller
{
    public function handle()
    {
        $software = $this->config->os('software');

        $this->io->writeln("<fg=red>!! WARNING !!</> We are preparing to install software on your server. Please review and confirm!\n");
        $this->seperator();

        foreach ($software as $pkg => $desc) {
            $this->io->writeln("* <fg=blue>'$pkg':</> <fg=yellow>$desc</>");
        }
        $this->seperator();

        if (!$this->io->confirm('Do you wish to continue?', false)) {
            $this->throwError('Aborted ...');
        };

        $this->process(['curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -']);

        $pkgs = implode(' ', array_keys($software));
        $this->install($pkgs);

        $this->process(['npm install -g laravel-echo-server']);
        $this->process(['ufw allow 6001']);

        $this->io->writeln('');
    }
}