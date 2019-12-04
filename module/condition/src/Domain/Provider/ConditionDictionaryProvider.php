<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Provider;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class ConditionDictionaryProvider
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var array
     */
    private $groups;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->groups = [];
    }

    /**
     * @param string $group
     * @param array  $classes
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
     * @param Language    $language
     * @param string|null $requestedGroup
     *
     * @return array
     */
    public function getDictionary(Language $language, string $requestedGroup = null): array
    {
        $result = [];
        if ($requestedGroup) {
            $result = array_merge($result, $this->getGroup($language, $requestedGroup));
        } else {
            foreach (array_keys($this->groups) as $group) {
                $result = array_merge($result, $this->getGroup($language, $group));
            }
        }

        return $result;
    }

    /**
     * @param Language $language
     * @param string   $group
     *
     * @return array
     */
    private function getGroup(Language $language, string $group): array
    {
        $result = [];
        if (array_key_exists($group, $this->groups)) {
            foreach ($this->groups[$group] as $type => $class) {
                $result[$type] = $this->translator->trans($type, [], 'condition', $language->getCode());
            }
        }

        return $result;
    }
}
