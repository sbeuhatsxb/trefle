<?php

namespace App\Command;

use App\Elasticsearch\PlantIndexer;
use App\Elasticsearch\IndexBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ElasticReindexCommand extends Command
{
    protected static $defaultName = 'elastic:reindex';
    protected static $defaultDescription = 'Add a short description for your command';
    private $indexBuilder;
    private $plantIndexer;

    public function __construct(IndexBuilder $indexBuilder, PlantIndexer $plantIndexer)
    {
        $this->indexBuilder = $indexBuilder;
        $this->plantIndexer = $plantIndexer;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $index = $this->indexBuilder->create();


        $io->success('Index created!');
        $io->success($index->getName());
        $io->success($this->plantIndexer->indexAllDocuments($index->getName()));

        $this->plantIndexer->indexAllDocuments($index->getName());

        $io->success('Index populated and ready!');
        $io->success($index->getMapping());

        return Command::SUCCESS;
    }
}
