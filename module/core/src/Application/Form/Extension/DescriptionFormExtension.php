<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Form\Extension;

use Limenius\Liform\Transformer\ExtensionInterface;
use Symfony\Component\Form\FormInterface;

class DescriptionFormExtension implements ExtensionInterface
{
    /**
     * @param array $schema
     *
     * @return array
     */
    public function apply(FormInterface $form, array $schema): array
    {
        if (!$form->getConfig()->hasOption('help')) {
            return $schema;
        }

        $description = $form->getConfig()->getOption('help');

        if ($description) {
            $schema['description'] = $description;
        }

        return $schema;
    }
}
