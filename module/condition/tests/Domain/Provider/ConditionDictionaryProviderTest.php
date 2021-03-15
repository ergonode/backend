<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Domain\Provider;

use Ergonode\Condition\Domain\Condition\AttributeExistsCondition;
use Ergonode\Condition\Domain\Condition\RoleExactlyCondition;
use Ergonode\Condition\Domain\Provider\ConditionDictionaryProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConditionDictionaryProviderTest extends TestCase
{
    public function testProviderCreation(): void
    {
        $translation = 'condition_translation';

        /** @var TranslatorInterface | MockObject $translator */
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->method('trans')->willReturn($translation);
        $group1 = 'group1';
        $group2 = 'group2';
        $classes1 = [AttributeExistsCondition::class];
        $classes2 = [RoleExactlyCondition::class];

        /** @var Language | MockObject $language */
        $language = $this->createMock(Language::class);

        $provider = new ConditionDictionaryProvider($translator);

        $provider->set($group1, $classes1);
        $provider->set($group2, $classes2);
        $this->assertSame(
            [AttributeExistsCondition::TYPE => $translation, RoleExactlyCondition::TYPE => $translation],
            $provider->getDictionary($language)
        );
        $this->assertSame(
            [AttributeExistsCondition::TYPE => $translation],
            $provider->getDictionary($language, $group1)
        );
    }
}
