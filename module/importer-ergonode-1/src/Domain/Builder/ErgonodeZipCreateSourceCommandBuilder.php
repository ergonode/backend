<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Domain\Builder;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Importer\Application\Provider\CreateSourceCommandBuilderInterface;
use Ergonode\ImporterErgonode\Application\Model\ImporterErgonodeConfigurationModel;
use Ergonode\ImporterErgonode\Domain\Command\CreateErgonodeZipSourceCommand;
use Ergonode\ImporterErgonode\Domain\Entity\ErgonodeZipSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Symfony\Component\Form\FormInterface;

final class ErgonodeZipCreateSourceCommandBuilder implements CreateSourceCommandBuilderInterface
{
    public function supported(string $type): bool
    {
        return $type === ErgonodeZipSource::TYPE;
    }

    /**
     * @throws \Exception
     */
    public function build(FormInterface $form): DomainCommandInterface
    {
        /** @var ImporterErgonodeConfigurationModel $data */
        $data = $form->getData();
        $name = $data->name;
        $import = (array) $data->import;

        return new CreateErgonodeZipSourceCommand(SourceId::generate(), $name, $import);
    }
}
