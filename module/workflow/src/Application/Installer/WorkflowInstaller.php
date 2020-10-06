<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Installer;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Application\Installer\InstallerInterface;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Command\Workflow\CreateWorkflowCommand;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Workflow\Domain\Command\Status\CreateStatusCommand;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Command\Workflow\AddWorkflowTransitionCommand;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;

/**
 */
class WorkflowInstaller implements InstallerInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param AttributeRepositoryInterface $repository
     * @param CommandBusInterface          $commandBus
     */
    public function __construct(AttributeRepositoryInterface $repository, CommandBusInterface $commandBus)
    {
        $this->repository = $repository;
        $this->commandBus = $commandBus;
    }

    /**
     * @throws \Exception
     */
    public function install(): void
    {
        $status['new'] = $this->getStatus('new', '#33373E', ['en_GB' => 'New']);
        $status['draft'] = $this->getStatus('draft', '#FFC108', ['en_GB' => 'Draft']);
        $status['to approve'] = $this->getStatus('to approve', '#AA00FF', ['en_GB' => 'To approve']);
        $status['ready'] = $this->getStatus('ready to publish', '#43A047', ['en_GB' => 'Ready to publish']);
        $status['to correct'] = $this->getStatus('to correct', '#C62828', ['en_GB' => 'To correct']);
        $status['published'] = $this->getStatus('published', '#2096F3', ['en_GB' => 'Published']);

        foreach ($status as $command) {
            $this->commandBus->dispatch($command);
        }

        $id = WorkflowId::generate();

        $command = new CreateWorkflowCommand(
            $id,
            'default',
            [
                $status['new']->getId(),
                $status['draft']->getId(),
                $status['to approve']->getId(),
                $status['ready']->getId(),
                $status['to correct']->getId(),
                $status['published']->getId(),
            ]
        );
        $this->commandBus->dispatch($command);

        $commands[] = $this->getTransition($id, $status['new']->getId(), $status['draft']->getId());
        $commands[] = $this->getTransition($id, $status['draft']->getId(), $status['to approve']->getId());
        $commands[] = $this->getTransition($id, $status['to approve']->getId(), $status['ready']->getId());
        $commands[] = $this->getTransition($id, $status['ready']->getId(), $status['published']->getId());
        $commands[] = $this->getTransition($id, $status['to correct']->getId(), $status['ready']->getId());

        foreach ($commands as $command) {
            $this->commandBus->dispatch($command);
        }
        $this->installWorkflowSystemAttribute();
    }

    /**
     * @param string $code
     * @param string $color
     * @param array  $name
     *
     * @return DomainCommandInterface
     *
     * @throws \Exception
     */
    private function getStatus(string $code, string $color, array $name): DomainCommandInterface
    {
        return new CreateStatusCommand(
            new StatusCode($code),
            new Color($color),
            new TranslatableString($name),
            new TranslatableString()
        );
    }

    /**
     * @param WorkflowId $id
     * @param StatusId   $from
     * @param StatusId   $to
     *
     * @return DomainCommandInterface
     *
     * @throws \Exception
     */
    private function getTransition(WorkflowId $id, StatusId $from, StatusId $to): DomainCommandInterface
    {
        return new AddWorkflowTransitionCommand(
            $id,
            $from,
            $to,
        );
    }

    /**
     * @throws \Exception
     */
    private function installWorkflowSystemAttribute(): void
    {
        $attribute = new StatusSystemAttribute(
            new TranslatableString(['en_GB' => 'Status']),
            new TranslatableString(),
            new TranslatableString(),
        );

        $this->repository->save($attribute);
    }
}
