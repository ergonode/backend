<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Application\Model;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Application\Model\Type\AttributeModel;

/**
 */
class Shopware6ChannelFormModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $name = null;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    public ?string $host = null;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $clientId = null;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $clientKey = null;

    /**
     * @var Language|null
     *
     * @Assert\NotBlank()
     */
    public ?Language $defaultLanguage = null;

    /**
     * @var AttributeId|null
     *
     * @Assert\NotNull()
     */
    public ?AttributeId $attributeProductName = null;

    /**
     * @var AttributeId|null
     *
     * @Assert\NotNull()
     */
    public ?AttributeId $attributeProductActive = null;

    /**
     * @var AttributeId|null
     *
     * @Assert\NotNull()
     */
    public ?AttributeId $attributeProductStock = null;

    /**
     * @var AttributeId|null
     *
     * @Assert\NotNull()
     */
    public ?AttributeId $attributeProductPrice = null;

    /**
     * @var AttributeId|null
     *
     * @Assert\NotNull()
     */
    public ?AttributeId $attributeProductTax = null;

    /**
     * @var AttributeId|null
     */
    public ?AttributeId $attributeProductDescription = null;

    /**
     * @var CategoryTreeId|null
     */
    public ?CategoryTreeId $categoryTree = null;

    /**
     * @var AttributeModel[]
     *
     * @Assert\Valid()
     */
    public array $propertyGroup = [];

    /**
     * @var AttributeModel[]
     *
     * @Assert\Valid()
     */
    public array $customField = [];

    /**
     * @param Shopware6Channel|null $channel
     */
    public function __construct(Shopware6Channel $channel = null)
    {
        if ($channel) {
            $this->name = $channel->getName();
            $this->host = $channel->getHost();
            $this->clientId = $channel->getClientId();
            $this->clientKey = $channel->getClientKey();
            $this->defaultLanguage = $channel->getDefaultLanguage();
            $this->attributeProductName = $channel->getProductName();
            $this->attributeProductActive = $channel->getProductActive();
            $this->attributeProductStock = $channel->getProductStock();
            $this->attributeProductPrice = $channel->getProductPrice();
            $this->attributeProductTax = $channel->getProductTax();
            $this->attributeProductDescription = $channel->getProductDescription();
            $this->categoryTree = $channel->getCategoryTree();

            foreach ($channel->getPropertyGroup() as $attributeId) {
                $this->propertyGroup[] = new AttributeModel($attributeId->getValue());
            }

            foreach ($channel->getCustomField() as $attributeId) {
                $this->customField[] = new AttributeModel($attributeId->getValue());
            }
        }
    }
}
