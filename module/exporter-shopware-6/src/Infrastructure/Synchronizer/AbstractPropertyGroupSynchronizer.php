<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Query\Shopware6PropertyGroupQueryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupClient;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupOptionClient;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Webmozart\Assert\Assert;

/**
 */
abstract class AbstractPropertyGroupSynchronizer extends AbstractPropertyOptionSynchronizer
{
    /**
     * @var Shopware6PropertyGroupQueryInterface
     */
    private Shopware6PropertyGroupQueryInterface $propertyGroupQuery;

    /**
     * @param Shopware6PropertyGroupQueryInterface      $propertyGroupQuery
     * @param AttributeRepositoryInterface              $attributeRepository
     * @param Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository
     * @param Shopware6PropertyGroupClient              $propertyGroupClient
     * @param OptionQueryInterface                      $optionQuery
     * @param OptionRepositoryInterface                 $optionRepository
     * @param Shopware6PropertyGroupOptionClient        $propertyGroupOptionClient
     */
    public function __construct(
        Shopware6PropertyGroupQueryInterface $propertyGroupQuery,
        AttributeRepositoryInterface $attributeRepository,
        Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository,
        Shopware6PropertyGroupClient $propertyGroupClient,
        OptionQueryInterface $optionQuery,
        OptionRepositoryInterface $optionRepository,
        Shopware6PropertyGroupOptionClient $propertyGroupOptionClient
    ) {
        parent::__construct(
            $attributeRepository,
            $propertyGroupRepository,
            $propertyGroupClient,
            $optionQuery,
            $optionRepository,
            $propertyGroupOptionClient
        );
        $this->propertyGroupQuery = $propertyGroupQuery;
    }


    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @param ExportId         $id
     * @param Shopware6Channel $channel
     */
    public function synchronize(ExportId $id, Shopware6Channel $channel): void
    {
        $this->synchronizeShopware($channel);
        $this->synchronizeProperty($channel);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isSupported(string $type): bool
    {
        return $this->getType() === $type;
    }

    /**
     * @param Shopware6Channel  $channel
     * @param AbstractAttribute $attribute
     */
    abstract protected function process(Shopware6Channel $channel, AbstractAttribute $attribute): void;

    /**
     * @param Shopware6Channel $channel
     */
    private function synchronizeProperty(Shopware6Channel $channel): void
    {
        $attributes = $channel->getPropertyGroup();
        foreach ($attributes as $attributeId) {
            $attribute = $this->attributeRepository->load($attributeId);
            Assert::notNull($attribute);
            if ($this->isSupported($attribute->getType())) {
                $this->process($channel, $attribute);
            }
        }
    }

    /**
     * @param Shopware6Channel $channel
     */
    private function synchronizeShopware(Shopware6Channel $channel): void
    {
        $start = new \DateTimeImmutable();
        $propertyGroupList = $this->propertyGroupClient->load($channel);
        foreach ($propertyGroupList as $property) {
            $attributeId = $this->propertyGroupQuery->loadByShopwareId(
                $channel->getId(),
                $property->getId()
            );
            if ($attributeId) {
                $attribute = $this->attributeRepository->load($attributeId);
                Assert::notNull($attribute);
                $this->propertyGroupRepository->save(
                    $channel->getId(),
                    $attributeId,
                    $property->getId(),
                    $attribute->getType()
                );
            }
        }
        $this->propertyGroupQuery->cleanData($channel->getId(), $start, $this->getType());
    }
}
