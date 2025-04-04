<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ArrayBenchmarkService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:array-benchmark',
    description: 'Benchmark SPL ArrayObject vs native arrays'
)]
class ArrayBenchmarkCommand extends Command
{
    public function __construct(
        private readonly ArrayBenchmarkService $benchmarkService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $results = $this->benchmarkService->benchmark();

        $output->writeln('Array Benchmark Results:');
        $output->writeln('------------------------');
        $output->writeln($results['native_array']['message']);
        $output->writeln($results['spl_array']['message']);
        $output->writeln($results['comparison']['message']);

        return Command::SUCCESS;
    }
} 