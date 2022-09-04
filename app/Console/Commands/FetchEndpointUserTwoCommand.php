<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class FetchEndpointUserTwoCommand extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'endpoint-user-two:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Data From End Point Two ';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        fetchEndpointUserTwo();
    }
}
