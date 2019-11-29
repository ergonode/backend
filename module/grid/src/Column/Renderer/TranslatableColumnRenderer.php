<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column\Renderer;

use Ergonode\Grid\Column\Exception\UnsupportedColumnException;
use Ergonode\Grid\Column\TranslatableColumn;
use Ergonode\Grid\ColumnInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class TranslatableColumnRenderer implements ColumnRendererInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
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
     * @param string                             $id
     * @param array                              $row
     *
     * @return string|null
     * @throws UnsupportedColumnException
     */
    public function render(ColumnInterface $column, string $id, array $row): ?string
    {
        if (!$this->supports($column)) {
            throw new UnsupportedColumnException($column);
        }

        $parameters = [];
        if ($column->getParameters()) {
            $parameters = \json_decode($row[$id] ?? '[]', true);
        }

        $domain = $column->getDomain();
        $locale = $column->getLanguage() ? $column->getLanguage()->getCode() : null;

        return $this->translator->trans($row[$id], $parameters, $domain, $locale);
    }
}
