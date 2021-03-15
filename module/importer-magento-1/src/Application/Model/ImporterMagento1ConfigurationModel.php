<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Application\Model;

use Ergonode\ImporterMagento1\Application\Model\Type\LanguageMapModel;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\ImporterMagento1\Application\Model\Type\StoreViewModel;
use Ergonode\ImporterMagento1\Application\Model\Type\AttributeMapModel;

class ImporterMagento1ConfigurationModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $name = null;

    /**
     * @Assert\Url()
     */
    public ?string $host = null;

    /**
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

    public function __construct(Magento1CsvSource $source = null)
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

            foreach (Magento1CsvSource::STEPS as $step) {
                if ($source->import($step)) {
                    $this->import[] = $step;
                }
            }
        }
    }
}
