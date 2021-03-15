<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Domain\Builder;

use Ergonode\Importer\Application\Provider\CreateSourceCommandBuilderInterface;
use Ergonode\Importer\Domain\Command\CreateSourceCommandInterface;
use Ergonode\ImporterErgonode1\Application\Model\ImporterErgonodeConfigurationModel;
use Ergonode\ImporterErgonode1\Domain\Command\CreateErgonodeZipSourceCommand;
use Ergonode\ImporterErgonode1\Domain\Entity\ErgonodeZipSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Symfony\Component\Form\FormInterface;

class ErgonodeZipCreateSourceCommandBuilder implements CreateSourceCommandBuilderInterface
{
    public function supported(string $type): bool
    {
        return $type === ErgonodeZipSource::TYPE;
    }

    /**
     * @throws \Exception
     */
    public function build(FormInterface $form): CreateSourceCommandInterface
    {
        /** @var ImporterErgonodeConfigurationModel $data */
        $data = $form->getData();
        $name = $data->name;
        $import = (array) $data->import;

        return new CreateErgonodeZipSourceCommand(SourceId::generate(), $name, $import);
    }
}
