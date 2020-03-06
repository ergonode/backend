<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Domain\Calculator;

use Ergonode\Completeness\Domain\Provider\TemplateElementCompletenessStrategyProvider;
use Ergonode\Completeness\Domain\ReadModel\CompletenessReadModel;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Editor\Domain\Entity\ProductDraft;

/**
 */
class CompletenessCalculator
{
    /**
     * @var TemplateElementCompletenessStrategyProvider
     */
    private TemplateElementCompletenessStrategyProvider $provider;

    /**
     * @param TemplateElementCompletenessStrategyProvider $provider
     */
    public function __construct(TemplateElementCompletenessStrategyProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param ProductDraft $draft
     * @param Template     $template
     * @param Language     $language
     *
     * @return CompletenessReadModel
     */
    public function calculate(ProductDraft $draft, Template $template, Language $language): CompletenessReadModel
    {
        $model = new CompletenessReadModel($language);

        foreach ($template->getElements() as $element) {
            $properties = $element->getProperties();
            $strategy = $this->provider->provide($properties->getVariant());
            $elementCompleteness = $strategy->getElementCompleteness($draft, $language, $properties);
            if ($elementCompleteness) {
                $model->addCompletenessElement($elementCompleteness);
            }
        }

        return $model;
    }
}
