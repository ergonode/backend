<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Infrastructure\Grid;

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
class NoteGrid extends AbstractGrid
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

        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);

        $this->addColumn('content', new TextColumn('content', $this->trans('Content'), new TextFilter($filters->get('content'))));
        $this->addColumn('author', new TextColumn('author', $this->trans('Author'), new TextFilter($filters->get('author'))));
        $this->addColumn('avatar_id', new ImageColumn('avatar_id', $this->trans('Avatar')));
        $this->addColumn('created_at', new DateColumn('created_at', $this->trans('Avatar'), new TextFilter($filters->get('created_at'))));
        $this->addColumn('edited_at', new DateColumn('edited_at', $this->trans('Avatar'), new TextFilter($filters->get('edited_at'))));

//        $this->addColumn('_links', new LinkColumn('hal', [
//            'get' => [
//                'route' => 'ergonode_category_read',
//                'parameters' => ['language' => $language->getCode(), 'category' => '{id}'],
//            ],
//            'edit' => [
//                'route' => 'ergonode_category_change',
//                'parameters' => ['language' => $language->getCode(), 'category' => '{id}'],
//                'method' => Request::METHOD_PUT,
//            ],
//            'delete' => [
//                'route' => 'ergonode_category_delete',
//                'parameters' => ['language' => $language->getCode(), 'category' => '{id}'],
//                'method' => Request::METHOD_DELETE,
//            ],
//        ]));

        $this->orderBy('date', 'DESC');
        $this->setConfiguration(self::PARAMETER_ALLOW_COLUMN_RESIZE, true);
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
