<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class AttributeGroupGrid extends AbstractGrid
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

        $this->addColumn('id', new TextColumn('id', $this->trans('Id'), new TextFilter($filters->get('id'))));
        $this->addColumn('code', new TextColumn('code', $this->trans('Code'), new TextFilter($filters->get('code'))));
        $this->addColumn('name', new TextColumn('name', $this->trans('Name'), new TextFilter($filters->get('name'))));
        $this->addColumn('elements_count', new IntegerColumn('elements_count', $this->trans('Elements Count'), new TextFilter($filters->get('elements_count'))));

        $links = [
            'get' => [
                'privilege' => 'ATTRIBUTE_GROUP_READ',
                'route' => 'ergonode_attribute_group_read',
                'parameters' => ['language' => $language->getCode(), 'group' => '{id}'],
            ],
            'edit' => [
                'privilege' => 'ATTRIBUTE_GROUP_UPDATE',
                'route' => 'ergonode_attribute_group_change',
                'parameters' => ['language' => $language->getCode(), 'group' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'privilege' => 'ATTRIBUTE_GROUP_DELETE',
                'route' => 'ergonode_attribute_group_delete',
                'parameters' => ['language' => $language->getCode(), 'group' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ];
        $this->addColumn('_links', new LinkColumn('hal', $links));
    }

    /**
     * @param string $id
     * @param array $parameters
     *
     * @return string
     */
    private function trans(string $id, array $parameters = []): string
    {
        return $this->translator->trans($id, $parameters, 'grid');
    }
}
