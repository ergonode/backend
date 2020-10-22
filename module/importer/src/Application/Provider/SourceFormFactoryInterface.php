<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Provider;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Symfony\Component\Form\FormInterface;

interface SourceFormFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;

    /**
     * @param AbstractSource|null $source
     *
     * @return FormInterface
     */
    public function create(AbstractSource $source = null): FormInterface;
}
