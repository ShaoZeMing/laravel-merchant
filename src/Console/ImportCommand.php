<?php

namespace ShaoZeMing\Merchant\Console;

use ShaoZeMing\Merchant\Admin;
use Illuminate\Console\Command;

class ImportCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'merchant:import {extension?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a extension';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $extension = $this->argument('extension');

        if (empty($extension) || !array_has(Admin::$extensions, $extension)) {
            $extension = $this->choice('Please choose a extension to import', array_keys(Admin::$extensions));
        }

        $className = array_get(Admin::$extensions, $extension);

        if (!class_exists($className) || !method_exists($className, 'import')) {
            $this->error("Invalid Extension [$className]");

            return;
        }

        call_user_func([$className, 'import'], $this);

        $this->info("Extension [$className] imported");
    }
}
