<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Configuration;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\Core\Infrastructure\Provider\LanguageProviderInterface;

/**
 */
class Magento1SourceConfiguration
{
    /**
     * @var LanguageProviderInterface
     */
    private LanguageProviderInterface $languageProvider;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param LanguageProviderInterface $languageProvider
     * @param TranslatorInterface       $translator
     */
    public function __construct(LanguageProviderInterface $languageProvider, TranslatorInterface $translator)
    {
        $this->languageProvider = $languageProvider;
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return Magento1CsvSource::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(Language $language): array
    {
        $languages = $this->languageProvider->getActiveLanguages($language);

        return [
            'type' => Magento1CsvSource::TYPE,
            'languages' => [
                [
                    'type' => 'COLLECTION',
                    'elements' => [
                        'store' => [
                            'name' => $this->translator->trans('store', [], 'importer', $language->getCode()),
                            'type' => 'TEXT'
                        ],
                        'language' => [
                            'name' => $this->translator->trans('language', [], 'importer', $language->getCode()),
                            'type' => 'SELECT',
                            'options' => $languages,
                        ],
                    ],
                ],
            ],
        ];
    }
}