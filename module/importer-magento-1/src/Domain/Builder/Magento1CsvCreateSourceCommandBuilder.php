<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Domain\Builder;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Symfony\Component\Form\FormInterface;
use Ergonode\ImporterMagento1\Application\Model\ImporterMagento1ConfigurationModel;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Application\Form\ImporterMagento1ConfigurationForm;
use Ergonode\Importer\Application\Provider\CreateSourceCommandBuilderInterface;
use Ergonode\ImporterMagento1\Domain\Command\CreateMagento1CsvSourceCommand;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class Magento1CsvCreateSourceCommandBuilder implements CreateSourceCommandBuilderInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return $type === Magento1CsvSource::TYPE;
    }

    /**
     * @param FormInterface|ImporterMagento1ConfigurationForm $form
     *
     * @return DomainCommandInterface
     *
     * @throws \Exception
     */
    public function build(FormInterface $form): DomainCommandInterface
    {
        /** @var ImporterMagento1ConfigurationModel $data */
        $data = $form->getData();
        $languages = [];
        foreach ($data->mapping->languages as $language) {
            $languages[$language->store] = $language->language->getCode();
        }
        $language = new Language($data->mapping->defaultLanguage->getCode());
        $name = $data->name;
        $host = $data->host;
        $attributes = $data->attributes;

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
