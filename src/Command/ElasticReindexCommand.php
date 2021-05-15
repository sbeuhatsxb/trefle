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

        $io->info('Indexing : '.$index->getName().' on '.$index->getClient()->getConfig('host'));
        $io->info('Is client connected :'.$index->getClient()->hasConnection());
        $io->info('Version :'.$index->getClient()->getVersion());
        $io->info('Username :'.$index->getClient()->getConnection()->getUsername());
        $io->info('Path :'.$index->getClient()->getConnection()->getPath());
        $io->info('Host :'.$index->getClient()->getConnection()->getHost());
        $io->info('Path :'.$index->getClient()->getStatus()->indexExists('plantapi'));
        $io->info('Path :'.$index->getClient()->getStatus()->getResponse()->getData());


        if($index->exists()){
            $this->plantIndexer->indexAllDocuments($index->getName());
            $io->success('Index populated and ready!');
        } else {
            $io->error('Index was not found');
        }


        return Command::SUCCESS;
    }
}
