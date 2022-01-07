<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Handler\Event\Application;

use Ergonode\Completeness\Infrastructure\Persistence\Manager\CompletenessManager;
use Ergonode\Core\Application\Event\LanguageTreeUpdatedEvent;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\LanguageNode;
use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;

class LanguageTreeUpdatedEventHandler
{
    private LanguageQueryInterface $languageQuery;

    private CompletenessManager $completenessManager;

    public function __construct(LanguageQueryInterface $languageQuery, CompletenessManager $completenessManager)
    {
        $this->languageQuery = $languageQuery;
        $this->completenessManager = $completenessManager;
    }

    public function __invoke(LanguageTreeUpdatedEvent $event): void
    {
        $languageIds = $this->createArray($event->getTree()->getLanguages());
        $languages = $this->languageQuery->getLanguagesByIds($languageIds);

        foreach ($languages as $language) {
            $this->completenessManager->recalculateLanguage($language);
        }
    }

    /**
     * @return LanguageId[]
     */
    private function createArray(LanguageNode $languages): array
    {
        $children = [$languages->getLanguageId()];

        foreach ($languages->getChildren() as $child) {
            $children = array_merge($children, $this->createArray($child));
        }

        return $children;
    }
}
