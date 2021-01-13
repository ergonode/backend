<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaExtensionProvider;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Filter\NumericFilter;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\Column\LinkColumn;

class MultimediaGrid extends AbstractGrid
{
    private MultimediaExtensionProvider $provider;

    private MultimediaQueryInterface $query;

    public function __construct(MultimediaExtensionProvider $provider, MultimediaQueryInterface $query)
    {
        $this->provider = $provider;
        $this->query = $query;
    }


    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $extensions = [];
        foreach ($this->provider->dictionary() as $extension) {
            $extensions[] = new LabelFilterOption($extension, $extension);
        }

        $types = [];
        foreach ($this->query->getTypes() as $type) {
            $types[] = new LabelFilterOption($type, $type);
        }

        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);

        $this->addColumn('image', new ImageColumn('image', 'Preview'));
        $this->addColumn('name', new TextColumn('name', 'File name', new TextFilter()));
        $this->addColumn('extension', new SelectColumn('extension', 'Extension', new MultiSelectFilter($extensions)));
        $this->addColumn('type', new SelectColumn('type', 'Type', new MultiSelectFilter($types)));
        $column = new NumericColumn('size', 'Size', new NumericFilter());
        $column->setSuffix('KB');
        $this->addColumn('size', $column);
        $this->addColumn('relations', new NumericColumn('relations', 'Relations', new NumericFilter()));
        $this->addColumn('created_at', new DateColumn('created_at', 'Created at', new DateFilter()));

        $links = [
            'get' => [
                'route' => 'ergonode_multimedia_read',
                'privilege' => 'MULTIMEDIA_GET',
                'parameters' => ['language' => $language->getCode(), 'multimedia' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_multimedia_edit',
                'privilege' => 'MULTIMEDIA_PUT',
                'parameters' => ['language' => $language->getCode(), 'multimedia' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'download' => [
                'route' => 'ergonode_multimedia_download',
                'privilege' => 'MULTIMEDIA_GET_DOWNLOAD',
                'parameters' => ['multimedia' => '{id}'],
                'method' => Request::METHOD_GET,
            ],
            'delete' => [
                'route' => 'ergonode_multimedia_delete',
                'privilege' => 'MULTIMEDIA_DELETE',
                'parameters' => ['language' => $language->getCode(), 'multimedia' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ];
        $this->addColumn('_links', new LinkColumn('hal', $links));
    }
}
