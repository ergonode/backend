<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Application\Model;

use Ergonode\ImporterErgonode\Application\Model\Type\AttributeMapModel;
use Ergonode\ImporterErgonode\Application\Model\Type\LanguageMapModel;
use Ergonode\ImporterErgonode\Application\Model\Type\StoreViewModel;
use Ergonode\ImporterErgonode\Domain\Entity\ErgonodeCsvSource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class ImporterErgonodeConfigurationModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $name = null;

    /**
     * @var string|null
     *
     * @Assert\Url()
     */
    public ?string $host = null;

    /**
     * @var StoreViewModel
     *
     * @Assert\Valid()
     */
    public StoreViewModel $mapping;

    /**
     * @var AttributeMapModel[]
     *
     * @Assert\Valid()
     */
    public array $attributes = [];

    /**
     * @var array
     */
    public array $import = [];

    /**
     * @param ErgonodeCsvSource|null $source
     */
    public function __construct(?ErgonodeCsvSource $source = null)
    {
        $this->mapping = new StoreViewModel();

        if ($source) {
            $this->mapping->defaultLanguage = $source->getDefaultLanguage();
            $this->host = $source->getHost();
            $this->name = $source->getName();
            foreach ($source->getLanguages() as $key => $language) {
                $this->mapping->languages[] = new LanguageMapModel($key, $language);
            }

            foreach ($source->getAttributes() as $code => $attributeId) {
                $this->attributes[] = new AttributeMapModel($code, $attributeId->getValue());
            }

            foreach (ErgonodeCsvSource::STEPS as $step) {
                if ($source->import($step)) {
                    $this->import[] = $step;
                }
            }
        }
    }
}
