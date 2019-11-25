<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Infrastructure\Grid;

use Ergonode\Core\Application\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class CommentGrid extends AbstractGrid
{
    /**
     * @var AuthenticatedUserProviderInterface
     */
    private $userProvider;

    /**
     * @param AuthenticatedUserProviderInterface $userProvider
     */
    public function __construct(AuthenticatedUserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $filters = $configuration->getFilters();
        $userId = $this->userProvider->provide()->getId();

        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);

        $this->addColumn('content', new TextColumn('content', 'Content', new TextFilter($filters->get('content'))));
        $this->addColumn('object_id', new TextColumn('object_id', 'Object', new TextFilter($filters->get('object_id'))));
        $this->addColumn('author', new TextColumn('author', 'Author', new TextFilter($filters->get('author'))));
        $this->addColumn('avatar_id', new ImageColumn('avatar_id'));
        $this->addColumn('created_at', new DateColumn('created_at', 'Avatar', new TextFilter($filters->get('created_at'))));
        $this->addColumn('edited_at', new DateColumn('edited_at', 'Avatar', new TextFilter($filters->get('edited_at'))));

        $links = [
            'get' => [
                'route' => 'ergonode_comment_read',
                'parameters' => ['language' => $language->getCode(), 'comment' => '{id}'],
            ],
            'edit' => [
                'show' => ['author_id' => $userId->getValue()],
                'route' => 'ergonode_comment_change',
                'parameters' => ['language' => $language->getCode(), 'comment' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'show' => ['author_id' => $userId->getValue()],
                'route' => 'ergonode_comment_delete',
                'parameters' => ['language' => $language->getCode(), 'comment' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ];
        $this->addColumn('_links', new LinkColumn('hal', $links));

        $this->orderBy('date', 'DESC');
        $this->setConfiguration(self::PARAMETER_ALLOW_COLUMN_RESIZE, true);
    }
}
