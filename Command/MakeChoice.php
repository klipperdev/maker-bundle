<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\MakerBundle\Command;

use Klipper\Bundle\MakerBundle\Util\GitUtil;
use Klipper\Component\Choice\ChoiceInterface;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Choice maker.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MakeChoice extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:choice';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->setDescription('Creates a new choice class')
            ->addArgument('name', InputArgument::OPTIONAL, sprintf('The name of the choice class (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm())))
            ->addOption('namespace', '-s', InputOption::VALUE_OPTIONAL, 'The namespace of choice without "App\\"', 'Choice')
            ->addOption('translation-domain', '-t', InputOption::VALUE_OPTIONAL, 'The translation domain of the choice', 'choices')
            ->setHelp(file_get_contents(__DIR__.'/../Resources/help/MakeChoice.txt'))
        ;
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
        $dependencies->addClassDependency(
            ChoiceInterface::class,
            'klipper/choice'
        );
    }

    /**
     * @throws \Exception
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $translationDomain = $input->getOption('translation-domain');

        $choiceClassNameDetails = $generator->createClassNameDetails(
            $input->getArgument('name'),
            $input->getOption('namespace')
        );

        $generator->generateClass(
            $choiceClassNameDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/choice/Choice.tpl.php',
            array_merge(
                [
                    'translation_domain' => 'false' === strtolower($translationDomain) ? false : $translationDomain,
                ],
                GitUtil::getAuthorVars()
            )
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);

        $io->text([
            'Next: Add choices map to your choice and start using it.',
        ]);
    }
}
