<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Service\Strategy\Configuration;

use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\Condition\Domain\Condition\RoleExactlyCondition;
use Ergonode\Condition\Domain\Service\ConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class RoleExactlyConditionConfigurationStrategy implements ConfigurationStrategyInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RoleQueryInterface
     */
    private $roleQuery;

    /**
     * @param TranslatorInterface $translator
     * @param RoleQueryInterface  $roleQuery
     */
    public function __construct(
        TranslatorInterface $translator,
        RoleQueryInterface $roleQuery
    ) {
        $this->translator = $translator;
        $this->roleQuery = $roleQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return RoleExactlyCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(Language $language): array
    {
        return [
            'type' => RoleExactlyCondition::TYPE,
            'name' => $this->translator->trans(RoleExactlyCondition::TYPE, [], 'condition', $language->getCode()),
            'phrase' => $this->translator->trans(RoleExactlyCondition::PHRASE, [], 'condition', $language->getCode()),
            'parameters' => [
                [
                    'name' => 'role',
                    'type' => 'SELECT',
                    'options' => $this->roleQuery->getDictionary(),
                ],
            ],
        ];
    }
}
