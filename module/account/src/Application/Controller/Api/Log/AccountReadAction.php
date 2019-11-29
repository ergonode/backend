<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Controller\Api\Log;

use Ergonode\Account\Domain\Query\LogQueryInterface;
use Ergonode\Account\Infrastructure\Grid\LogGrid;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Application\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{language}/accounts/log", methods={"GET"})
 */
class AccountReadAction
{
    /**
     * @var LogQueryInterface
     */
    private $query;

    /**
     * @var LogGrid
     */
    private $grid;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private $userProvider;

    /**
     * @var GridRenderer
     */
    private $gridRenderer;

    /**
     * @param GridRenderer                       $gridRenderer
     * @param LogQueryInterface                  $query
     * @param LogGrid                            $grid
     * @param AuthenticatedUserProviderInterface $userProvider
     */
    public function __construct(
        GridRenderer $gridRenderer,
        LogQueryInterface $query,
        LogGrid $grid,
        AuthenticatedUserProviderInterface $userProvider
    ) {
        $this->gridRenderer = $gridRenderer;
        $this->query = $query;
        $this->grid = $grid;
        $this->userProvider = $userProvider;
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="50",
     *     description="Number of returned lines"
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="0",
     *     description="Number of start line"
     * )
     * @SWG\Parameter(
     *     name="field",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"recorded_at", "author", "author_id", "event"},
     *     description="Order field"
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC","DESC"},
     *     description="Order"
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
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language code"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns accounts log collection"
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function __invoke(RequestGridConfiguration $configuration): Response
    {
        $data = $this->gridRenderer->render(
            $this->grid,
            $configuration,
            $this->query->getDataSet(),
            $this->userProvider->provide()->getLanguage()
        );

        return new SuccessResponse($data);
    }
}
