<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Grid\Renderer;

use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Column\Exception\UnsupportedColumnException;
use Ergonode\BatchAction\Infrastructure\Grid\Column\BatchActionMessageColumn;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\Renderer\ColumnRendererInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BatchActionMessageColumnRenderer implements ColumnRendererInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function supports(ColumnInterface $column): bool
    {
        return $column instanceof BatchActionMessageColumn;
    }

    /**
     * @throws UnsupportedColumnException
     */
    public function render(ColumnInterface $column, string $id, array $row): ?string
    {
        if (!$this->supports($column)) {
            throw new UnsupportedColumnException($column);
        }

        $result = null;
        if (null !== $row[$id]) {
            $messages = json_decode($row[$id], true, 512, JSON_THROW_ON_ERROR);
            $records = [];
            foreach ($messages as $message) {
                $records[] = $this->translate($message, $column->getLanguage());
            }
            $result = implode(', ', $records);
        }

        return $result;
    }

    private function translate(array $record, ?Language $language = null): string
    {
        return $this->translator->trans(
            $record['message'],
            $record['properties'],
            'batch-action',
            $language ? $language->getCode() : null,
        );
    }
}
