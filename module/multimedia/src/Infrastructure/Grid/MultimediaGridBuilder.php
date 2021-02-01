<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
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
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;

class MultimediaGridBuilder implements GridBuilderInterface
{
    private MultimediaExtensionProvider $provider;

    private MultimediaQueryInterface $query;

    public function __construct(MultimediaExtensionProvider $provider, MultimediaQueryInterface $query)
    {
        $this->provider = $provider;
        $this->query = $query;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $types = $this->getTypes();
        $extensions = $this->getExtension();

        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $grid->addColumn('id', $id);

        $grid->addColumn('image', new ImageColumn('image', 'Preview'));
        $grid->addColumn('name', new TextColumn('name', 'File name', new TextFilter()));
        $grid->addColumn('extension', new SelectColumn('extension', 'Extension', new MultiSelectFilter($extensions)));
        $grid->addColumn('type', new SelectColumn('type', 'Type', new MultiSelectFilter($types)));
        $column = new NumericColumn('size', 'Size', new NumericFilter());
        $column->setSuffix('KB');
        $grid->addColumn('size', $column);
        $grid->addColumn('relations', new NumericColumn('relations', 'Relations', new NumericFilter()));
        $grid->addColumn('created_at', new DateColumn('created_at', 'Created at', new DateFilter()));

        $links = [
            'get' => [
                'route' => 'ergonode_multimedia_read',
                'privilege' => 'MULTIMEDIA_READ',
                'parameters' => ['language' => $language->getCode(), 'multimedia' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_multimedia_edit',
                'privilege' => 'MULTIMEDIA_UPDATE',
                'parameters' => ['language' => $language->getCode(), 'multimedia' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'download' => [
                'route' => 'ergonode_multimedia_download',
                'privilege' => 'MULTIMEDIA_READ',
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
        $grid->addColumn('_links', new LinkColumn('hal', $links));

        return $grid;
    }

    private function getExtension(): array
    {
        $result = [];
        foreach ($this->provider->dictionary() as $extension) {
            $result[] = new LabelFilterOption($extension, $extension);
        }

        return $result;
    }

    private function getTypes(): array
    {
        $result = [];
        foreach ($this->query->getTypes() as $type) {
            $result[] = new LabelFilterOption($type, $type);
        }

        return $result;
    }
}
