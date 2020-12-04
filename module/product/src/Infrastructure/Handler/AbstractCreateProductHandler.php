<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler;

use Ergonode\Account\Application\Security\Security;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Product\Domain\Entity\Attribute\CreatedBySystemAttribute;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;

abstract class AbstractCreateProductHandler
{
    protected ProductRepositoryInterface $productRepository;

    protected WorkflowProvider $provider;

    protected LanguageQueryInterface $query;

    private Security $security;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        WorkflowProvider $provider,
        LanguageQueryInterface $query,
        Security $security
    ) {
        $this->productRepository = $productRepository;
        $this->provider = $provider;
        $this->query = $query;
        $this->security = $security;
    }

    /**
     * @param array $attributes
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function addStatusAttribute(array $attributes): array
    {
        $workflow = $this->provider->provide();
        $result = [];
        $status = $workflow->getDefaultStatus()->getValue();
        foreach ($this->query->getActive() as $language) {
            $result[$language->getCode()] = $status;
        }
        $attributes[StatusSystemAttribute::CODE] = new TranslatableStringValue(new TranslatableString($result));

        return $attributes;
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function addAudit(array $attributes): array
    {
        $user = $this->security->getUser();
        if ($user) {
            $value = new StringValue(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));
            $attributes[CreatedBySystemAttribute::CODE] = $value;
        }

        return $attributes;
    }
}
