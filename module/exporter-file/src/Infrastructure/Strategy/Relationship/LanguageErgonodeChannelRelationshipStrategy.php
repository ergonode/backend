<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Strategy\Relationship;

use Ergonode\Channel\Domain\Query\ChannelQueryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;
use Ergonode\SharedKernel\Domain\AggregateId;

class LanguageErgonodeChannelRelationshipStrategy implements RelationshipStrategyInterface
{
    private const MESSAGE = 'Object has active relationships with channel %relations%';

    private LanguageQueryInterface $languageQuery;

    private ChannelQueryInterface $channelQuery;

    private ChannelRepositoryInterface $channelRepository;

    public function __construct(
        LanguageQueryInterface $languageQuery,
        ChannelQueryInterface $channelQuery,
        ChannelRepositoryInterface $channelRepository
    ) {
        $this->languageQuery = $languageQuery;
        $this->channelQuery = $channelQuery;
        $this->channelRepository = $channelRepository;
    }


    /**
     * {@inheritDoc}
     */
    public function supports(AggregateId $id): bool
    {
        return $id instanceof LanguageId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        $relation = [];
        $channelIds = $this->channelQuery->findChannelIdsByType(FileExportChannel::TYPE);

        $language = $this->languageQuery->getLanguageById($id->getValue());
        if ($language) {
            foreach ($channelIds as $channelId) {
                $channel = $this->channelRepository->load($channelId);
                if ($channel instanceof FileExportChannel) {
                    foreach ($channel->getLanguages() as $channelLanguage) {
                        if ($channelLanguage->isEqual($language)) {
                            $relation[] = $channelId;
                            break;
                        }
                    }
                }
            }
        }

        return new RelationshipGroup(self::MESSAGE, $relation);
    }
}
