<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Provider;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConditionDictionaryProvider
{
    private TranslatorInterface $translator;

    /**
     * @var array
     */
    private array $groups;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->groups = [];
    }

    /**
     * @param array $classes
     *
     * @throws \ReflectionException
     */
    public function set(string $group, array $classes = []): void
    {
        $conditions = [];
        foreach ($classes as $class) {
            $reflectionClass = new \ReflectionClass($class);
            $constraints = $reflectionClass->getConstants();
            if (array_key_exists('TYPE', $constraints)) {
                $conditions[$constraints['TYPE']] = $class;
            }
        }

        $this->groups[$group] = $conditions;
    }

    /**
     * @return array
     */
    public function getDictionary(Language $language, string $requestedGroup = null): array
    {
        if ($requestedGroup) {
            return $this->getGroup($language, $requestedGroup);
        }

        $result = [];
        foreach (array_keys($this->groups) as $group) {
            $result[] = $this->getGroup($language, $group);
        }

        return array_merge(...$result);
    }

    /**
     * @return array
     */
    private function getGroup(Language $language, string $group): array
    {
        $result = [];
        if (array_key_exists($group, $this->groups)) {
            foreach (array_keys($this->groups[$group]) as $type) {
                $result[$type] = $this->translator->trans($type, [], 'condition', $language->getCode());
            }
        }

        return $result;
    }
}
