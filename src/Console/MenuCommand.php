<?php

namespace ShaoZeMing\Merchant\Console;

use ShaoZeMing\Merchant\Facades\Merchant;
use Illuminate\Console\Command;

class MenuCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'merchant:menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the merchant menu.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $menu = Merchant::menu();

        echo json_encode($menu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), "\r\n";
    }
}
