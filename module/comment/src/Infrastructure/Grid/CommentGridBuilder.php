<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Infrastructure\Grid;

use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\Column\IdColumn;

class CommentGridBuilder implements GridBuilderInterface
{
    private AuthenticatedUserProviderInterface $userProvider;

    public function __construct(AuthenticatedUserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $userId = $this->userProvider->provide()->getId();
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

        $grid = new Grid();

        $grid->addColumn('id', new IdColumn('id'));
        $userIdColumn = new TextColumn('user_id', 'User Id', new TextFilter());
        $userIdColumn->setVisible(false);
        $grid
            ->addColumn('user_id', $userIdColumn)
            ->addColumn('content', new TextColumn('content', 'Content', new TextFilter()))
            ->addColumn('object_id', new TextColumn('object_id', 'Object', new TextFilter()))
            ->addColumn('author', new TextColumn('author', 'Author', new TextFilter()))
            ->addColumn('created_at', new DateColumn('created_at', 'Crated at', new DateFilter()))
            ->addColumn('edited_at', new DateColumn('edited_at', 'Edited at', new DateFilter()))
            ->addColumn('avatar_filename', new ImageColumn('avatar_filename'))
            ->addColumn('_links', new LinkColumn('hal', $links))
            ->orderBy('created_at', 'DESC');

        return $grid;
    }
}
