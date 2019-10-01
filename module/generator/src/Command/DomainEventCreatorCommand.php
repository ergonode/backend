<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Generator\Command;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Generator\Builder\Domain\Event\EntityEventBuilder;
use Ergonode\Generator\Generator\EntityGenerator;
use Ergonode\Generator\Persister\FilePersister;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class DomainEventCreatorCommand extends Command
{
    /**
     * @var EntityEventBuilder
     */
    private $builder;

    /**
     * @var FilePersister
     */
    private $persister;

    /**
     * @param EntityEventBuilder $builder
     * @param FilePersister      $persister
     */
    public function __construct(EntityEventBuilder $builder, FilePersister $persister)
    {
        $this->builder = $builder;
        $this->persister = $persister;

        parent::__construct();
    }

    /**
     */
    public function configure(): void
    {
        $this->setName('ergonode:generator:event');
        $this->setDescription('Test module generation');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name');
        $this->addArgument('entity', InputArgument::REQUIRED, 'Entity name');
        $this->addArgument('event', InputArgument::REQUIRED, 'Event name');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $module = $input->getArgument('module');
        $entity = $input->getArgument('entity');
        $event = $input->getArgument('event');

        $namespaces[] = sprintf('Ergonode\%s\Domain\Entity\%s', ucfirst($module), ucfirst($entity));
        $namespaces[] = sprintf('Ergonode\%s\Domain\Entity\%sId', ucfirst($module), ucfirst($entity));
        $namespaces[] = AbstractAggregateRoot::class;
        $namespaces[] = AbstractId::class;
        $namespaces[] = TranslatableString::class;


        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Add property (Y:n)? ', true, '/^(y|j)/i');
        $propertyNameQuestion = new Question('property name: ');
        $propertyClassQuestion = new Question('property class: ');
        $propertyClassQuestion->setAutocompleterValues($namespaces);

        $properties = [];
        while($helper->ask($input, $output, $question)) {
            $propertyName = $helper->ask($input, $output, $propertyNameQuestion);
            $propertyClass = $helper->ask($input, $output, $propertyClassQuestion);
            $properties[$propertyName] = $propertyClass;
        }

        $file = $this->builder->build($module, $event, $properties);
        $this->persister->persist($file, $module);
    }
}

