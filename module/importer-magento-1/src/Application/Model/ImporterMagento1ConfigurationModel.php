<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Model;

use Ergonode\ImporterMagento1\Application\Model\Type\ImportStepModel;
use Ergonode\ImporterMagento1\Application\Model\Type\LanguageMapModel;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class ImporterMagento1ConfigurationModel
{
    /**
     * @var string|null
     *
     * @Assert\Length(min=2)
     */
    public ?string $name = null;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    public ?string $host = null;

    /**
     * @var Language|null
     */
    public ?Language $defaultLanguage = null;

    /**
     * @var LanguageMapModel[]
     *
     * @Assert\Valid()
     */
    public array $languages = [];

    /**
     * @var ImportStepModel
     */
    public ImportStepModel $import;

    /**
     * @param Magento1CsvSource|null $source
     */
    public function __construct(Magento1CsvSource $source = null)
    {
        $this->import = new ImportStepModel();

        if ($source) {
            $this->defaultLanguage = $source->getDefaultLanguage();
            $this->host = $source->getHost();
            $this->name = $source->getName();
            foreach ($source->getLanguages() as $key => $language) {
                $this->languages[] = new LanguageMapModel($key, $language);
            }
            $this->import->templates = $source->import(Magento1CsvSource::TEMPLATES);
            $this->import->attributes = $source->import(Magento1CsvSource::ATTRIBUTES);
            $this->import->products = $source->import(Magento1CsvSource::PRODUCTS);
            $this->import->multimedia = $source->import(Magento1CsvSource::MULTIMEDIA);
            $this->import->categories = $source->import(Magento1CsvSource::CATEGORIES);
        }
    }
}
