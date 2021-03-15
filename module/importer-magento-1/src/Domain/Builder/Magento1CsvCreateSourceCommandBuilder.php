<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Domain\Builder;

use Ergonode\Importer\Domain\Command\CreateSourceCommandInterface;
use Symfony\Component\Form\FormInterface;
use Ergonode\ImporterMagento1\Application\Model\ImporterMagento1ConfigurationModel;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\Importer\Application\Provider\CreateSourceCommandBuilderInterface;
use Ergonode\ImporterMagento1\Domain\Command\CreateMagento1CsvSourceCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class Magento1CsvCreateSourceCommandBuilder implements CreateSourceCommandBuilderInterface
{
    public function supported(string $type): bool
    {
        return $type === Magento1CsvSource::TYPE;
    }

    /**
     * @throws \Exception
     */
    public function build(FormInterface $form): CreateSourceCommandInterface
    {
        /** @var ImporterMagento1ConfigurationModel $data */
        $data = $form->getData();
        $languages = [];
        foreach ($data->mapping->languages as $language) {
            $languages[$language->store] = $language->language;
        }
        $language = $data->mapping->defaultLanguage;
        $name = $data->name;
        $host = $data->host;
        $attributes = [];
        foreach ($data->attributes as $attribute) {
            $attributes[$attribute->code] = new AttributeId($attribute->attribute);
        }

        $import = (array) $data->import;

        return new CreateMagento1CsvSourceCommand(
            SourceId::generate(),
            $name,
            $language,
            $host,
            $languages,
            $attributes,
            $import
        );
    }
}
