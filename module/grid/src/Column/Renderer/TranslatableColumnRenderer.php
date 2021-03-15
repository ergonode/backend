<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column\Renderer;

use Ergonode\Grid\Column\Exception\UnsupportedColumnException;
use Ergonode\Grid\Column\TranslatableColumn;
use Ergonode\Grid\ColumnInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslatableColumnRenderer implements ColumnRendererInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ColumnInterface $column): bool
    {
        return $column instanceof TranslatableColumn;
    }

    /**
     * @param ColumnInterface|TranslatableColumn $column
     * @param array                              $row
     *
     *
     * @throws UnsupportedColumnException
     */
    public function render(ColumnInterface $column, string $id, array $row): ?string
    {
        if (!$column instanceof TranslatableColumn) {
            throw new UnsupportedColumnException($column);
        }

        $parameters = [];
        if ($column->getParameters()) {
            $parameters = \json_decode($row[$column->getParameters()] ?? '[]', true);
        }

        $parameters = $parameters ?: [];

        $domain = $column->getDomain();
        $translatedParameters = [];
        foreach ($parameters as $key => $parameter) {
            $translatedParameters[$key] = $this->translator->trans($parameter, [], $domain);
        }

        return $this->translator->trans($row[$id], $translatedParameters, $domain);
    }
}
