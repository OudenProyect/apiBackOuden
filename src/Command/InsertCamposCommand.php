<?php

namespace App\Command;

use App\Entity\Feature;
use App\Repository\FeatureRepository;
use phpDocumentor\Reflection\Types\Parent_;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:insert-campos',
    description: 'Add a short description for your command',
)]
class InsertCamposCommand extends Command
{

    public function __construct(public FeatureRepository $repo)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Your name is ')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tipos = new Feature();
        $tipos->setName('Armarios empotrados');
        $this->repo->save($tipos, true);
        return Command::SUCCESS;
    }
}
