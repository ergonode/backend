<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\DBALException;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Event\TemplateRemovedEvent;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Webmozart\Assert\Assert;

class DbalTemplateRepository implements TemplateRepositoryInterface
{
    private EventStoreManager $manager;

    public function __construct(EventStoreManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritDoc}
     *
     * @return Template|null
     *
     * @throws \ReflectionException
     */
    public function load(TemplateId $id): ?Template
    {
        /** @var Template $result */
        $result = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($result, Template::class);

        return $result;
    }

    /**
     * @throws DBALException
     */
    public function save(Template $template): void
    {
        $this->manager->save($template);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(Template $template): void
    {
        $template->apply(new TemplateRemovedEvent($template->getId()));
        $this->save($template);

        $this->manager->delete($template);
    }
}
