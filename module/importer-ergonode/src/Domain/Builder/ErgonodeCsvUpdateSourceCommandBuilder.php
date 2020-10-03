<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Domain\Builder;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Importer\Application\Provider\UpdateSourceCommandBuilderInterface;
use Ergonode\ImporterErgonode\Application\Form\ImporterErgonodeConfigurationForm;
use Ergonode\ImporterErgonode\Application\Model\ImporterErgonodeConfigurationModel;
use Ergonode\ImporterErgonode\Domain\Command\UpdateErgonodeCsvSourceCommand;
use Ergonode\ImporterErgonode\Domain\Entity\ErgonodeCsvSource;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Symfony\Component\Form\FormInterface;

/**
 */
class ErgonodeCsvUpdateSourceCommandBuilder implements UpdateSourceCommandBuilderInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return $type === ErgonodeCsvSource::TYPE;
    }

    /**
     * @param SourceId                                        $id
     * @param FormInterface|ImporterErgonodeConfigurationForm $form
     *
     * @return DomainCommandInterface
     *
     */
    public function build(SourceId $id, FormInterface $form): DomainCommandInterface
    {
        /** @var ImporterErgonodeConfigurationModel $data */
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

        return new UpdateErgonodeCsvSourceCommand(
            $id,
            $name,
            $language,
            $host,
            $languages,
            $attributes,
            $import
        );
    }
}
