<?php

namespace AppBundle\Command;

use AppBundle\Deputy\DistrictLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportDistrictsCommand extends Command
{
    protected static $defaultName = 'app:deputy-districts:import';

    private $loader;

    /**
     * @var SymfonyStyle
     */
    private $io;

    public function __construct(DistrictLoader $loader)
    {
        $this->loader = $loader;

        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'CSV file of all districts to load');
        $this->addArgument('districtsFile', InputArgument::REQUIRED, 'GeoJSON file of french districts to load');
        $this->addArgument('countriesFile', InputArgument::REQUIRED, 'GeoJSON file of countries to load');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!is_readable($file = $input->getArgument('file'))) {
            $this->io->error("$file is not a file");

            return 1;
        }
        if (!is_readable($districtsFile = $input->getArgument('districtsFile'))) {
            $this->io->error("$districtsFile is not a file");

            return 1;
        }
        if (!is_readable($countriesFile = $input->getArgument('countriesFile'))) {
            $this->io->error("$countriesFile is not a file");

            return 1;
        }

        $this->io->text('Start importing districts');

        $this->loader->load(realpath($file), realpath($districtsFile), realpath($countriesFile));

        $this->io->success('Done');
    }
}
