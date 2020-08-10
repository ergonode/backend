<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaExtensionProvider;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Filter\NumericFilter;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;

/**
 */
class MultimediaGrid extends AbstractGrid
{
    /**
     * @var MultimediaExtensionProvider
     */
    private MultimediaExtensionProvider $provider;

    /**
     * @var MultimediaQueryInterface
     */
    private MultimediaQueryInterface $query;

    /**
     * @param MultimediaExtensionProvider $provider
     * @param MultimediaQueryInterface    $query
     */
    public function __construct(MultimediaExtensionProvider $provider, MultimediaQueryInterface $query)
    {
        $this->provider = $provider;
        $this->query = $query;
    }


    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
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
        $this->addColumn('type', new SelectColumn('type', 'Extension', new MultiSelectFilter($types)));
        $column = new NumericColumn('size', 'Size', new NumericFilter());
        $column->setSuffix('MB');
        $this->addColumn('size', $column);
        $this->addColumn('relations', new NumericColumn('relations', 'Relations', new NumericFilter()));
        $this->addColumn('created_at', new TextColumn('created_at', 'Creation date', new TextFilter()));

//        $links = [
//            'get' => [
//                'route' => 'ergonode_multimedia_read',
//                'parameters' => ['language' => $language->getCode(), 'multimedia' => '{id}'],
//            ],
//            'edit' => [
//                'route' => 'ergonode_multimedia_change',
//                'parameters' => ['language' => $language->getCode(), 'multimedia' => '{id}'],
//                'method' => Request::METHOD_PUT,
//            ],
//            'delete' => [
//                'route' => 'ergonode_multimedia_delete',
//                'parameters' => ['language' => $language->getCode(), 'multimedia' => '{id}'],
//                'method' => Request::METHOD_DELETE,
//            ],
//        ];
//        $this->addColumn('_links', new LinkColumn('hal', $links));
    }
}
