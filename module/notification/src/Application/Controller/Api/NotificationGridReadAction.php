<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Application\Controller\Api;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Notification\Domain\Query\NotificationQueryInterface;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Notification\Infrastructure\Grid\NotificationGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile/notifications", methods={"GET"})
 */
class NotificationGridReadAction
{
    /**
     * @var NotificationGrid
     */
    private NotificationGrid $grid;

    /**
     * @var NotificationQueryInterface
     */
    private NotificationQueryInterface $query;

    /**
     * @var GridRenderer
     */
    private GridRenderer $gridRenderer;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private AuthenticatedUserProviderInterface $userProvider;

    /**
     * @param NotificationGrid                   $grid
     * @param NotificationQueryInterface         $query
     * @param GridRenderer                       $gridRenderer
     * @param AuthenticatedUserProviderInterface $userProvider
     */
    public function __construct(
        NotificationGrid $grid,
        NotificationQueryInterface $query,
        GridRenderer $gridRenderer,
        AuthenticatedUserProviderInterface $userProvider
    ) {
        $this->grid = $grid;
        $this->query = $query;
        $this->gridRenderer = $gridRenderer;
        $this->userProvider = $userProvider;
    }

    /**
     * @SWG\Tag(name="Profile")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
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
     *     description="Returns notifications",
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
        $dataSet = $this->query->getDataSet($user->getId(), $user->getLanguage());

        $data = $this->gridRenderer->render(
            $this->grid,
            $configuration,
            $dataSet,
            $user->getLanguage()
        );

        return new SuccessResponse($data);
    }
}
