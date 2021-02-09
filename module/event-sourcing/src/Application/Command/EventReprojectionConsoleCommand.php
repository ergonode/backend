<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Application\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ergonode\EventSourcing\Infrastructure\Projector\ProjectorProcessor;
use Ergonode\EventSourcing\Infrastructure\Manager\AggregateQuery;
use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeCategoryAddedEvent;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeCategoriesChangedEvent;
use Symfony\Component\Console\Helper\ProgressBar;
use Ergonode\Category\Domain\Entity\CategoryTree;

class EventReprojectionConsoleCommand extends Command
{
    private const NAME = 'ergonode:es:reprojection';

    private ProjectorProcessor $processor;

    private AggregateQuery $query;

    public function __construct(ProjectorProcessor $processor, AggregateQuery $query)
    {
        parent::__construct(static::NAME);

        $this->processor = $processor;
        $this->query = $query;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $events = [
            CategoryTreeCategoryAddedEvent::class,
            CategoryTreeCategoriesChangedEvent::class,
        ];

        $aggregateIds = $this->query->findAllAggregateOfClass(CategoryTree::class);

        ProgressBar::setFormatDefinition(
            'custom',
            ' %current%/%max% [%bar%] [%memory%] %percent%% -- %message%'.PHP_EOL
        );
        $progressBar = new ProgressBar($output, count($aggregateIds));
        $progressBar->setFormat('custom');
        $progressBar->start();
        foreach ($aggregateIds as $aggregateId) {
            $this->processor->process($aggregateId, $events);
            $progressBar->setMessage(sprintf('Processing aggregate - %s', $aggregateId->getValue()));
            $progressBar->advance();
        }
        $progressBar->finish();

        return 0;
    }
}
