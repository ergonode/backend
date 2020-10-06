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
use Ergonode\ImporterErgonode\Domain\Command\UpdateErgonodeZipSourceCommand;
use Ergonode\ImporterErgonode\Domain\Entity\ErgonodeZipSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Symfony\Component\Form\FormInterface;

/**
 */
class ErgonodeZipUpdateSourceCommandBuilder implements UpdateSourceCommandBuilderInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return $type === ErgonodeZipSource::TYPE;
    }

    /**
     * @param SourceId                                        $id
     * @param FormInterface|ImporterErgonodeConfigurationForm $form
     *
     * @return DomainCommandInterface
     */
    public function build(SourceId $id, FormInterface $form): DomainCommandInterface
    {
        /** @var ImporterErgonodeConfigurationModel $data */
        $data = $form->getData();
        $name = $data->name;
        $import = (array) $data->import;

        return new UpdateErgonodeZipSourceCommand($id, $name, $import);
    }
}
