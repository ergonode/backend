<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Controller\Api\Log;

use Ergonode\Account\Infrastructure\Grid\LogGridBuilder;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Account\Domain\Query\LogGridQueryInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;

/**
 * @Route("/{language}/accounts/log", methods={"GET"})
 */
class AccountReadAction
{
    private LogGridQueryInterface $query;

    private LogGridBuilder $gridBuilder;

    private AuthenticatedUserProviderInterface $userProvider;

    private DbalDataSetFactory $factory;

    private GridRenderer $gridRenderer;

    public function __construct(
        LogGridQueryInterface $query,
        LogGridBuilder $gridBuilder,
        AuthenticatedUserProviderInterface $userProvider,
        DbalDataSetFactory $factory,
        GridRenderer $gridRenderer
    ) {
        $this->query = $query;
        $this->gridBuilder = $gridBuilder;
        $this->userProvider = $userProvider;
        $this->factory = $factory;
        $this->gridRenderer = $gridRenderer;
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
     *     default="en_GB",
     *     description="Language code"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns accounts log collection"
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     */
    public function __invoke(RequestGridConfiguration $configuration): Response
    {
        $language =  $this->userProvider->provide()->getLanguage();

        $grid = $this->gridBuilder->build($configuration, $language);
        $dataSet = $this->factory->create($this->query->getDataSet());
        $data = $this->gridRenderer->render($grid, $configuration, $dataSet);

        return new SuccessResponse($data);
    }
}
