<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Provider;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use Symfony\Component\Form\FormInterface;

/**
 */
interface UpdateExportProfileCommandBuilderInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;

    /**
     * @param ExportProfileId $exportProfileId
     * @param FormInterface   $form
     *
     * @return DomainCommandInterface
     */
    public function build(ExportProfileId $exportProfileId, FormInterface $form): DomainCommandInterface;
}
