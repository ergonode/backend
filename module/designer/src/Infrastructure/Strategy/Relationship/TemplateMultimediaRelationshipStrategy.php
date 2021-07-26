<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;

class TemplateMultimediaRelationshipStrategy implements RelationshipStrategyInterface
{
    private const ONE_MESSAGE = 'Multimedia have a relation with a template';
    private const MULTIPLE_MESSAGE = 'Multimedia have %count% relations with some templates';

    private TemplateQueryInterface $templateQuery;

    public function __construct(TemplateQueryInterface $templateQuery)
    {
        $this->templateQuery = $templateQuery;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof MultimediaId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, MultimediaId::class);

        $relations = [];

        $list = $this->templateQuery->getMultimediaRelation($id);
        foreach (array_keys($list) as $templateId) {
            $relations[] = new TemplateId($templateId);
        }

        $message = count($relations) === 1 ? self::ONE_MESSAGE : self::MULTIPLE_MESSAGE;

        return new RelationshipGroup($message, $relations);
    }
}
