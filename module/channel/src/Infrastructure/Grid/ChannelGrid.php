<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Grid;

use Ergonode\Channel\Application\Provider\ChannelTypeDictionaryProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

class ChannelGrid extends AbstractGrid
{
    private ChannelTypeDictionaryProvider $channelTypeProvider;

    public function __construct(ChannelTypeDictionaryProvider $channelTypeProvider)
    {
        $this->channelTypeProvider = $channelTypeProvider;
    }

    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $types = [];
        foreach ($this->channelTypeProvider->provide($language) as $key => $value) {
            $types[] = new LabelFilterOption($key, $value);
        }

        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);

        $name = new TextColumn('name', 'Name', new TextFilter());
        $this->addColumn('name', $name);

        $column = new SelectColumn('type', 'Type', new MultiSelectFilter($types));
        $this->addColumn('type', $column);

        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_channel_read',
                'privilege' => 'CHANNEL_READ',
                'parameters' => ['language' => $language->getCode(), 'channel' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_channel_change',
                'privilege' => 'CHANNEL_UPDATE',
                'parameters' => ['language' => $language->getCode(), 'channel' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_channel_delete',
                'privilege' => 'CHANNEL_DELETE',
                'parameters' => ['language' => $language->getCode(), 'channel' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
    }
}
