<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Comment\Domain\Query\CommentGridQueryInterface;
use Ergonode\Comment\Infrastructure\Grid\CommentGridBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Grid\Factory\DbalDataSetFactory;

/**
 * @Route("/comments", methods={"GET"}, name="ergonode_comment_grid")
 */
class CommentGridAction
{
    private CommentGridBuilder $gridBuilder;

    private CommentGridQueryInterface $query;

    private DbalDataSetFactory $factory;

    private GridRenderer $renderer;

    public function __construct(
        CommentGridBuilder $gridBuilder,
        CommentGridQueryInterface $query,
        DbalDataSetFactory $factory,
        GridRenderer $renderer
    ) {
        $this->gridBuilder = $gridBuilder;
        $this->query = $query;
        $this->factory = $factory;
        $this->renderer = $renderer;
    }

    /**
     * @IsGranted("COMMENT_GET_GRID")
     *
     * @SWG\Tag(name="Comment")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="50",
     *     description="Number of returned lines",
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="0",
     *     description="Number of start line",
     * )
     * @SWG\Parameter(
     *     name="field",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"id", "label","code", "hint"},
     *     description="Order field",
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC","DESC"},
     *     description="Order",
     * )
     * @SWG\Parameter(
     *     name="filter",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Filter"
     * )
     * @SWG\Parameter(
     *     name="view",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"grid","list"},
     *     description="Specify respons format"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns attribute collection",
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     */
    public function __invoke(Language $language, RequestGridConfiguration $configuration): Response
    {
        $grid = $this->gridBuilder->build($configuration, $language);
        $dataSet = $this->factory->create($this->query->getDataSet($language));
        $data = $this->renderer->render($grid, $configuration, $dataSet);

        return new SuccessResponse($data);
    }
}
