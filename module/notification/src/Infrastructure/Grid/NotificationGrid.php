<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class NotificationGrid extends AbstractGrid
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
        $filters = $configuration->getFilters();

        $id = new TextColumn('id', $this->trans('Id'), new TextFilter($filters->get('id')));
        $id->setVisible(false);
        $this->addColumn('id', $id);

        $column = new TextColumn('message', $this->trans('Message'), new TextFilter($filters->get('message')));
        $this->addColumn('message', $column);

        $column = new DateColumn('created_at', $this->trans('Created at'), new TextFilter($filters->get('created_at')));
        $this->addColumn('created_at', $column);

        $column = new DateColumn('read_at', $this->trans('Read at'), new TextFilter($filters->get('read_at')));
        $this->addColumn('read_at', $column);

        $column = new TextColumn('author', $this->trans('Author'), new TextFilter($filters->get('author')));
        $this->addColumn('author', $column);

        $column = new ImageColumn('avatar_id', $this->trans('Avatar'));
        $this->addColumn('avatar_id', $column);
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
