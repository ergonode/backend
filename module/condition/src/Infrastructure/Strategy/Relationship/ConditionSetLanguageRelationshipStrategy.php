<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Strategy\Relationship;

use Ergonode\Condition\Domain\Query\ConditionSetQueryInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;
use Ergonode\SharedKernel\Domain\AggregateId;

class ConditionSetLanguageRelationshipStrategy implements RelationshipStrategyInterface
{
    private const ONE_MESSAGE = 'Language is used in one condition set';
    private const MULTIPLE_MESSAGE = 'Language is used in %count% condition sets';

    private LanguageQueryInterface $languageQuery;

    private ConditionSetQueryInterface $conditionSetQuery;

    public function __construct(LanguageQueryInterface $languageQuery, ConditionSetQueryInterface $conditionSetQuery)
    {
        $this->languageQuery = $languageQuery;
        $this->conditionSetQuery = $conditionSetQuery;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof LanguageId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        $relations = [];

        $language = $this->languageQuery->getLanguageById($id->getValue());
        if ($language) {
            $relations = $this->conditionSetQuery->findLanguageConditionRelations($language);
        }

        $message = count($relations) === 1 ? self::ONE_MESSAGE : self::MULTIPLE_MESSAGE;

        return new RelationshipGroup($message, $relations);
    }
}
