<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Configuration;

use Ergonode\Account\Domain\Query\UserQueryInterface;
use Ergonode\Condition\Domain\Condition\UserExactlyCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserExactlyConditionConditionConfigurationStrategy implements ConditionConfigurationStrategyInterface
{
    private TranslatorInterface $translator;

    private UserQueryInterface $userQuery;

    public function __construct(
        TranslatorInterface $translator,
        UserQueryInterface $userQuery
    ) {
        $this->translator = $translator;
        $this->userQuery = $userQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return UserExactlyCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(Language $language): array
    {
        return [
            'type' => UserExactlyCondition::TYPE,
            'name' => $this->translator->trans(UserExactlyCondition::TYPE, [], 'condition', $language->getCode()),
            'phrase' => $this->translator->trans(UserExactlyCondition::PHRASE, [], 'condition', $language->getCode()),
            'parameters' => [
                [
                    'name' => 'user',
                    'type' => 'SELECT',
                    'options' => $this->userQuery->getDictionary(),
                ],
            ],
        ];
    }
}
