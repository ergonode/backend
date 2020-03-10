<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Generator\Command;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Generator\Generator\EntityGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 */
class DomainEntityCreatorCommand extends Command
{
    /**
     * @var EntityGenerator
     */
    private EntityGenerator $generator;

    /**
     * @param EntityGenerator $generator
     */
    public function __construct(EntityGenerator $generator)
    {
        $this->generator = $generator;

        parent::__construct();
    }

    /**
     */
    public function configure(): void
    {
        $this->setName('ergonode:generator:entity');
        $this->setDescription('Generate domain entity class in module with all required related classes');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
        $this->addArgument('entity', InputArgument::REQUIRED, 'Entity name');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $module = $input->getArgument('module');
        $entity = $input->getArgument('entity');

        $namespaces[] = sprintf('Ergonode\%s\Domain\Entity\%sId', ucfirst($module), ucfirst($entity));
        $namespaces[] = TranslatableString::class;

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Add property (Y:n)? ', true, '/^(y|j)/i');
        $propertyNameQuestion = new Question('property name: ');
        $propertyClassQuestion = new Question('property class: ');
        $propertyClassQuestion->setAutocompleterValues($namespaces);

        $properties = [];
        while ($helper->ask($input, $output, $question)) {
            $propertyName = $helper->ask($input, $output, $propertyNameQuestion);
            $propertyClass = $helper->ask($input, $output, $propertyClassQuestion);
            $properties[$propertyName] = $propertyClass;
        }

        $this->generator->generate($module, $entity, $properties);
    }
}
