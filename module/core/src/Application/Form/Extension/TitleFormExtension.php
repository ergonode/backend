<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Form\Extension;

use Limenius\Liform\Transformer\ExtensionInterface;
use Symfony\Component\Form\FormInterface;

class TitleFormExtension implements ExtensionInterface
{
    /**
     * @param array $schema
     *
     * @return array
     */
    public function apply(FormInterface $form, array $schema): array
    {
        if (!$form->getConfig()->hasOption('label')) {
            return $schema;
        }

        $title = $form->getConfig()->getOption('label');

        if ($title) {
            $schema['title'] = $title;
        } elseif (array_key_exists('title', $schema)) {
            unset($schema['title']);
        }

        return $schema;
    }
}
