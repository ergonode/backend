<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Domain\Builder;

use Ergonode\Importer\Application\Provider\UpdateSourceCommandBuilderInterface;
use Ergonode\Importer\Domain\Command\UpdateSourceCommandInterface;
use Ergonode\ImporterErgonode1\Application\Model\ImporterErgonodeConfigurationModel;
use Ergonode\ImporterErgonode1\Domain\Command\UpdateErgonodeZipSourceCommand;
use Ergonode\ImporterErgonode1\Domain\Entity\ErgonodeZipSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Symfony\Component\Form\FormInterface;

class ErgonodeZipUpdateSourceCommandBuilder implements UpdateSourceCommandBuilderInterface
{
    public function supported(string $type): bool
    {
        return $type === ErgonodeZipSource::TYPE;
    }

    public function build(SourceId $id, FormInterface $form): UpdateSourceCommandInterface
    {
        /** @var ImporterErgonodeConfigurationModel $data */
        $data = $form->getData();
        $name = $data->name;
        $import = (array) $data->import;

        return new UpdateErgonodeZipSourceCommand($id, $name, $import);
    }
}
