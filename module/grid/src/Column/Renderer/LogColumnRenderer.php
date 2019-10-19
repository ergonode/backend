<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column\Renderer;

use Ergonode\Account\Infrastructure\Grid\Column\LogColumn;
use Ergonode\Grid\Column\Exception\UnsupportedColumnException;
use Ergonode\Grid\ColumnInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class LogColumnRenderer implements ColumnRendererInterface
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
        return $column instanceof LogColumn;
    }

    /**
     * {@inheritDoc}
     *
     * @throws UnsupportedColumnException
     */
    public function render(ColumnInterface $column, string $id, array $row): string
    {
        if (!$this->supports($column)) {
            throw new UnsupportedColumnException($column);
        }

        $parameters = [];
        foreach (json_decode($row[$column->getParameterField()], true) as $key => $parameter) {
            if (is_string($parameter)) {
                $parameters[sprintf('%%%s%%', $key)] = $parameter;
            }
        }

        return $this->translator->trans($row[$id], $parameters, 'log', strtolower($column->getLanguage()->getCode()));
    }
}
