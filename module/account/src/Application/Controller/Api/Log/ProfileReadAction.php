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
use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{language}/profile/log", methods={"GET"})
 */
class ProfileReadAction
{
    /**
     * @var LogQueryInterface
     */
    private LogQueryInterface $query;

    /**
     * @var LogGrid
     */
    private LogGrid $grid;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private AuthenticatedUserProviderInterface $userProvider;

    /**
     * @var GridRenderer
     */
    private GridRenderer $gridRenderer;

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
        $this->query = $query;
        $this->grid = $grid;
        $this->userProvider = $userProvider;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     *
     * @SWG\Tag(name="Profile")
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
     *     default="en",
     *     description="Language code"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns User Log collection",
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
        $user = $this->userProvider->provide();

        $data = $this->gridRenderer->render(
            $this->grid,
            $configuration,
            $this->query->getDataSet($user->getId()),
            $user->getLanguage()
        );

        return new SuccessResponse($data);
    }
}
