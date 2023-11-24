<?php

namespace App\Infrastructure\Input;

use App\Application\ConsoleFilterMapper;
use App\Application\ListFilmsService;
use App\Infrastructure\Output\FilmFakeRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListFilmsCommand extends Command
{
    protected function configure() : void
    {
        $this->addOption('year',null, InputArgument::OPTIONAL, 'Año de publicación', null)
            ->addOption('title', null, InputArgument::OPTIONAL)
            ->addOption('rating', null, InputArgument::OPTIONAL);

        $this->setName('app:films');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $listingService = new ListFilmsService(new FilmFakeRepository());

        $arguments = [
            'year'      => $input->getOption('year'),
            'title'     => $input->getOption('title'),
            'rating'    => $input->getOption('rating')
        ];

        $films = $listingService(
            new ConsoleFilterMapper($arguments)
        )->get();

        $maxTitle = array_reduce(
            $films,
            function ($carry, $val) {
                $len = strlen($val->title->value);
                return ($len > $carry) ? $len : $carry;
            },
            0
        );

        $output->writeln(str_pad('Título', $maxTitle, ' ')."\t| Año  | Valoración");
        $output->writeln(str_pad('', $maxTitle + 23, '-'));
        foreach ($films as $film) {
            $title = str_pad($film->title->value, $maxTitle, ' ');
            $output->writeln("{$title}\t| {$film->year->value} | {$film->rating->value}");
        }

        return 0;
    }
}