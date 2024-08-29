<?php

namespace App\Console\Commands;

use App\Repositories\CustomersRepositoryInterface;
use App\Services\CustomersImportService;
use Illuminate\Console\Command;

class ImportCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import customers from an external API into the database';

    private $customersRepo;

    public function __construct(CustomersRepositoryInterface $customersRepo)
    {
        parent::__construct();
        $this->customersRepo = $customersRepo;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $result = CustomersImportService::create($this->customersRepo);

        if ($result) {
            $this->info('Customers import completed.');
        } else {
            $this->info('No new imports.');
        }

        return 0;
    }
}
