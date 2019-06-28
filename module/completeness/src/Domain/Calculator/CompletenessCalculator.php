<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
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
    private $provider;

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
            $model->addField($this->provider->provide($properties->getVariant())->calculate($draft, $language, $properties));
        }

        return $model;
    }
}
