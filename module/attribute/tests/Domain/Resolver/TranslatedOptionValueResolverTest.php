<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Attribute\Tests\Domain\Resolver;

use Ergonode\Attribute\Domain\Resolver\TranslatedOptionValueResolver;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\AbstractOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

/***
 */
class TranslatedOptionValueResolverTest extends TestCase
{
    /**
     * @param AbstractOption $option
     * @param string         $result
     * @param Language       $language
     *
     * @dataProvider dataProvider
     */
    public function testResolvingSupportedOption(AbstractOption $option, string $result, Language $language): void
    {
        $resolver = new TranslatedOptionValueResolver();
        $this->assertEquals($result, $resolver->resolve($option, $language));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testResolvingNotSupportedOption(): void
    {
        /** @var AbstractOption $option */
        $option = $this->createMock(AbstractOption::class);
        $resolver = new TranslatedOptionValueResolver();
        $resolver->resolve($option, new Language(Language::EN));
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'option' => new StringOption('Any value'),
                'result' => 'Any value',
                'language' => new Language(Language::EN),
            ],
            [
                'option' => new MultilingualOption(new TranslatableString(['EN' => 'Any value'])),
                'result' => 'Any value',
                'language' => new Language(Language::EN),
            ]
        ];
    }
}
