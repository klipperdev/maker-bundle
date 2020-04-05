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
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Generic class maker.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MakeClass extends AbstractMaker
{
    /**
     * @var string
     */
    private $rootNamespace;

    public function __construct(string $rootNamespace = 'App')
    {
        $this->rootNamespace = $rootNamespace;
    }

    public static function getCommandName(): string
    {
        return 'make:class';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->setDescription('Creates a new generic class')
            ->addArgument('classname', InputArgument::OPTIONAL, sprintf('The FQCN of the generic class without "%s\\" (e.g. <fg=yellow>%s</>)', $this->rootNamespace, Str::asClassName(Str::getRandomTerm())))
            ->setHelp(file_get_contents(__DIR__.'/../Resources/help/MakeClass.txt'))
        ;
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $fullClassName = trim($input->getArgument('classname'), '\\');
        $classname = Str::getShortClassName($fullClassName);
        $namespace = Str::getNamespace($fullClassName);

        if (0 === strpos($namespace, $this->rootNamespace.'\\')) {
            $namespace = trim(substr($namespace, 3), '\\');
        }

        $choiceClassNameDetails = $generator->createClassNameDetails($classname, $namespace);

        $generator->generateClass(
            $choiceClassNameDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/class/GenericClass.tpl.php',
            GitUtil::getAuthorVars()
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);

        $io->text([
            'Next: Write your code in the class and start using it.',
        ]);
    }
}
