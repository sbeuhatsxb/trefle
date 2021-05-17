<?php

namespace App\Command;

use App\Elasticsearch\PlantIndexer;
use App\Elasticsearch\IndexBuilder;
use Elastica\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Request;

class TestCommand extends Command
{
    protected static $defaultName = 'test:test';
    protected static $defaultDescription = 'Add a short description for your command';
    private $client;

    public function __construct(IndexBuilder $indexBuilder, PlantIndexer $plantIndexer, Client $client)
    {
        $this->client = $client;

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

        $this->client->connect();

        $path = '_aliases?pretty';

        $response = $this->client->request($path, Request::METHOD_GET);
        $responseArray = $response->getData();
        var_dump($responseArray);

        var_dump($this->client->hasConnection());
        var_dump($this->client->getConnection()->getHost());
        var_dump($this->client->getConnection()->getUsername());
        var_dump($this->client->getConnection()->getPort());


        return Command::SUCCESS;
    }
}
