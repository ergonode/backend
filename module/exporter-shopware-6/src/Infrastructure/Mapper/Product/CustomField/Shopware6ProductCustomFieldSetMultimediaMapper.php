<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\CustomField;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractImageAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6ProductMediaClient;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterMapperException;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\AbstractShopware6ProductCustomFieldSetMapper;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

class Shopware6ProductCustomFieldSetMultimediaMapper extends AbstractShopware6ProductCustomFieldSetMapper
{
    private MultimediaRepositoryInterface $multimediaRepository;

    private Shopware6ProductMediaClient $mediaClient;

    public function __construct(
        AttributeRepositoryInterface $repository,
        AttributeTranslationInheritanceCalculator $calculator,
        MultimediaRepositoryInterface $multimediaRepository,
        Shopware6ProductMediaClient $mediaClient
    ) {
        parent::__construct($repository, $calculator);
        $this->multimediaRepository = $multimediaRepository;
        $this->mediaClient = $mediaClient;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return AbstractImageAttribute::TYPE;
    }

    /**
     * {@inheritDoc}
     *
     * @throws Shopware6ExporterMapperException
     */
    protected function getValue(Shopware6Channel $channel, AbstractAttribute $attribute, $calculateValue): string
    {
        $multimediaId = new MultimediaId($calculateValue);

        return $this->getShopware6MultimediaId($channel, $multimediaId);
    }

    /**
     * @throws Shopware6ExporterMapperException
     */
    private function getShopware6MultimediaId(Shopware6Channel $channel, MultimediaId $multimediaId): string
    {
        $multimedia = $this->multimediaRepository->load($multimediaId);
        if ($multimedia) {
            return $this->mediaClient->findOrCreateMedia($channel, $multimedia);
        }
        throw new Shopware6ExporterMapperException('Multimedia not found');
    }
}
