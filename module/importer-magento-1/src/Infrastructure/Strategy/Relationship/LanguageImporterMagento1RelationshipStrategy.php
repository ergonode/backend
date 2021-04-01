<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Importer\Domain\Query\SourceQueryInterface;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;
use Ergonode\SharedKernel\Domain\AggregateId;

class LanguageImporterMagento1RelationshipStrategy implements RelationshipStrategyInterface
{
    private const MESSAGE = 'Object has active relationships with importer magento1 %relations%';

    private LanguageQueryInterface $languageQuery;

    private SourceQueryInterface $sourceQuery;

    private SourceRepositoryInterface $sourceRepository;

    public function __construct(
        LanguageQueryInterface $languageQuery,
        SourceQueryInterface $sourceQuery,
        SourceRepositoryInterface $sourceRepository
    ) {
        $this->languageQuery = $languageQuery;
        $this->sourceQuery = $sourceQuery;
        $this->sourceRepository = $sourceRepository;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof LanguageId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        $relation = [];
        $sourceIds = $this->sourceQuery->findSourceIdsByType(Magento1CsvSource::TYPE);

        $language = $this->languageQuery->getLanguageById($id->getValue());
        if ($language) {
            foreach ($sourceIds as $sourceId) {
                $source = $this->sourceRepository->load($sourceId);
                if ($source instanceof Magento1CsvSource) {
                    if ($source->getDefaultLanguage()->isEqual($language)) {
                        $relation[] = $sourceId;
                        continue;
                    }
                    foreach ($source->getLanguages() as $sourceLanguage) {
                        if ($sourceLanguage->isEqual($language)) {
                            $relation[] = $sourceId;
                            break;
                        }
                    }
                }
            }
        }

        return new RelationshipGroup(self::MESSAGE, $relation);
    }
}
