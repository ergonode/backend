<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Configuration;

use Ergonode\Condition\Domain\Condition\ProductHasTemplateCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class ProductHasTemplateConditionConfigurationStrategy implements ConditionConfigurationStrategyInterface
{
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var TemplateQueryInterface
     */
    private TemplateQueryInterface $templateQuery;

    /**
     * @param TranslatorInterface    $translator
     * @param TemplateQueryInterface $templateQuery
     */
    public function __construct(TranslatorInterface $translator, TemplateQueryInterface $templateQuery)
    {
        $this->translator = $translator;
        $this->templateQuery = $templateQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return ProductHasTemplateCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(Language $language): array
    {
        $templates = $this->templateQuery->getDictionary($language);
        asort($templates);

        return [
            'type' => ProductHasTemplateCondition::TYPE,
            'name' => $this
                ->translator
                ->trans(ProductHasTemplateCondition::TYPE, [], 'condition', $language->getCode()),
            'phrase' => $this
                ->translator
                ->trans(ProductHasTemplateCondition::PHRASE, [], 'condition', $language->getCode()),
            'parameters' => [
                [
                    'name' => 'operator',
                    'type' => 'SELECT',
                    'options' => [
                        ProductHasTemplateCondition::HAS =>
                            $this->translator->trans(
                                ProductHasTemplateCondition::HAS,
                                [],
                                'condition',
                                $language->getCode()
                            ),
                        ProductHasTemplateCondition::NOT_HAS =>
                            $this->translator->trans(
                                ProductHasTemplateCondition::NOT_HAS,
                                [],
                                'condition',
                                $language->getCode()
                            ),
                    ],
                ],
                [
                    'name' => 'template_id',
                    'type' => 'SELECT',
                    'options' => $templates,
                ],
            ],
        ];
    }
}
