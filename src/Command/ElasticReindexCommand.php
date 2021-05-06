<?php

namespace App\Command;

use App\Elasticsearch\PlantIndexer;
use App\Elasticsearch\IndexBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ElasticReindexCommand extends Command
{
    protected static $defaultName = 'elastic:reindex';

    private $indexBuilder;
    private $plantIndexer;

    public function __construct(IndexBuilder $indexBuilder, PlantIndexer $plantIndexer)
    {
        $this->indexBuilder = $indexBuilder;
        $this->plantIndexer = $plantIndexer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Rebuild the Index and populate it.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $index = $this->indexBuilder->create();

        $io->success('Index created!');

        $this->plantIndexer->indexAllDocuments($index->getName());

        $io->success('Index populated and ready!');
        return Command::SUCCESS;
    }
}