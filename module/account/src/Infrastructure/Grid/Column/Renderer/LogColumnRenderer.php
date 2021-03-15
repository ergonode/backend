<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Grid\Column\Renderer;

use Ergonode\Account\Infrastructure\Grid\Column\LogColumn;
use Ergonode\Grid\Column\Exception\UnsupportedColumnException;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Grid\Column\Renderer\ColumnRendererInterface;

class LogColumnRenderer implements ColumnRendererInterface
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
        return $column instanceof LogColumn;
    }

    /**
     * {@inheritDoc}
     *
     * @throws UnsupportedColumnException
     */
    public function render(ColumnInterface $column, string $id, array $row): string
    {
        if (!$column instanceof LogColumn) {
            throw new UnsupportedColumnException($column);
        }

        $parameters = [];
        foreach (json_decode($row[$column->getParameterField()], true) as $key => $parameter) {
            if (is_string($parameter)) {
                $parameters[sprintf('%%%s%%', $key)] = $parameter;
            }
            if (is_array($parameter) && array_key_exists('type', $parameter) && array_key_exists('value', $parameter)) {
                if ($parameter['type'] === StringValue::TYPE) {
                    $parameters[sprintf('%%%s%%', $key)] = $parameter['value'];
                }
                if ($parameter['type'] === StringCollectionValue::TYPE) {
                    $parameters[sprintf('%%%s%%', $key)] = implode(', ', $parameter['value']);
                }
                if ($parameter['type'] === TranslatableStringValue::TYPE) {
                    $parameters[sprintf('%%%s%%', $key)] = implode(', ', $parameter['value']);
                }
            }
        }

        return $this->translator->trans($row[$id], $parameters, 'log', strtolower($column->getLanguage()->getCode()));
    }
}
