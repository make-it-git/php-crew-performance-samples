<?php

namespace App\Command;

use App\Service\NoStrictTypesService;
use App\Service\StrictTypesService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BenchmarkTypesCommand extends Command
{
    protected static $defaultName = 'app:benchmark-types';

    private $noStrictService;
    private $strictService;

    public function __construct(NoStrictTypesService $noStrictService, StrictTypesService $strictService)
    {
        parent::__construct();
        $this->noStrictService = $noStrictService;
        $this->strictService = $strictService;
    }

    protected function configure()
    {
        $this->setDescription('Benchmark strict vs non-strict types performance');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Starting benchmark...');
        
        // Run non-strict benchmark
        $noStrictResult = $this->noStrictService->benchmark();
        $output->writeln($noStrictResult['message']);
        
        // Run strict benchmark
        $strictResult = $this->strictService->benchmark();
        $output->writeln($strictResult['message']);
        
        // Calculate performance difference
        $difference = (($noStrictResult['average_time'] - $strictResult['average_time']) / $strictResult['average_time']) * 100;
        $output->writeln(sprintf(
            'Performance difference: %.2f%% (strict types are %s)',
            abs($difference),
            $difference > 0 ? 'faster' : 'slower'
        ));
        
        return Command::SUCCESS;
    }
} 