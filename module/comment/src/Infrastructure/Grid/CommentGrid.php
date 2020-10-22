<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Infrastructure\Grid;

use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

class CommentGrid extends AbstractGrid
{
    private AuthenticatedUserProviderInterface $userProvider;

    public function __construct(AuthenticatedUserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $userId = $this->userProvider->provide()->getId();

        $commentIdColumn = new TextColumn('id', 'Id');
        $commentIdColumn->setVisible(false);
        $this->addColumn('id', $commentIdColumn);
        $userIdColumn = new TextColumn('user_id', 'User Id', new TextFilter());
        $userIdColumn->setVisible(false);
        $this->addColumn('user_id', $userIdColumn);
        $this->addColumn('content', new TextColumn('content', 'Content', new TextFilter()));
        $this->addColumn('object_id', new TextColumn('object_id', 'Object', new TextFilter()));
        $this->addColumn('author', new TextColumn('author', 'Author', new TextFilter()));
        $this->addColumn('created_at', new DateColumn('created_at', 'Crated at', new DateFilter()));
        $this->addColumn('edited_at', new DateColumn('edited_at', 'Edited at', new DateFilter()));
        $this->addColumn('avatar_filename', new ImageColumn('avatar_filename'));

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

        $this->orderBy('created_at', 'DESC');
    }
}
