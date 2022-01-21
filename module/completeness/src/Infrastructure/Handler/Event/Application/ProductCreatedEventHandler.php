<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Handler\Event\Application;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Product\Application\Event\ProductCreatedEvent;
use Ergonode\Completeness\Infrastructure\Persistence\Manager\CompletenessManager;

class ProductCreatedEventHandler
{
    private CompletenessManager $manager;

    private LanguageQueryInterface $languageQuery;

    public function __construct(CompletenessManager $manager, LanguageQueryInterface $languageQuery)
    {
        $this->manager = $manager;
        $this->languageQuery = $languageQuery;
    }

    public function __invoke(ProductCreatedEvent $event): void
    {
        $completeness = [];

        $languages = $this->languageQuery->getActive();
        foreach ($languages as $language) {
            $completeness[$language->getCode()] = 0;
        }

        $this->manager->addProduct($event->getProduct()->getId(), $completeness);
    }
}
