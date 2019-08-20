<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Grid;

use Ergonode\Account\Infrastructure\Grid\Column\LogColumn;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class LogGrid extends AbstractGrid
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
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $id = new IntegerColumn('id', $this->trans('Id'));
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('recorded_at', new TextColumn('recorded_at', $this->trans('Time')));
        $this->addColumn('author', new TextColumn('author', $this->trans('Author')));
        $this->addColumn('event', new LogColumn('event', 'payload', $this->trans('Message'), $language, $this->translator));

        $this->orderBy('recorded_at', 'DESC');
    }

    /**
     * @param string $id
     * @param array  $parameters
     *
     * @return string
     */
    private function trans(string $id, array $parameters = []): string
    {
        return $this->translator->trans($id, $parameters, 'grid');
    }
}
