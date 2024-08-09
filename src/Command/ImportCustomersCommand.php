<?php

namespace App\Command;

use App\Service\CustomerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCustomersCommand extends Command
{
    protected static $defaultName = 'app:import-customers';

    private $customerService;

    public function __construct(CustomerService $CustomerSrv)
    {
        parent::__construct();
        $this->customerService = $CustomerSrv;
    }

    protected function configure()
    {
        $this->setDescription('Import customers from a third-party API');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $this->customerService->importCustomers();
        $output->writeln('Customers imported successfully!');

        return Command::SUCCESS;
    }
}

?>