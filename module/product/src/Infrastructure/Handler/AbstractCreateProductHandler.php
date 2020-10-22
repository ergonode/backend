<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler;

use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Product\Domain\Entity\Attribute\CreatedBySystemAttribute;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Symfony\Component\Security\Core\Security;

abstract class AbstractCreateProductHandler
{
    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var TokenStorageInterface
     */
    protected TokenStorageInterface   $tokenStorage;

    /**
     * @var WorkflowProvider
     */
    protected WorkflowProvider $provider;

    /**
     * @var LanguageQueryInterface
     */
    protected LanguageQueryInterface $query;

    /**
     * @var Security
     */
    private Security $security;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param TokenStorageInterface      $tokenStorage
     * @param WorkflowProvider           $provider
     * @param LanguageQueryInterface     $query
     * @param Security                   $security
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        TokenStorageInterface $tokenStorage,
        WorkflowProvider $provider,
        LanguageQueryInterface $query,
        Security $security
    ) {
        $this->productRepository = $productRepository;
        $this->tokenStorage = $tokenStorage;
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
        /** @var User $user */
        $user = $this->security->getUser();
        if ($user) {
            $value = new StringValue(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));
            $attributes[CreatedBySystemAttribute::CODE] = $value;
        }

        return $attributes;
    }
}
