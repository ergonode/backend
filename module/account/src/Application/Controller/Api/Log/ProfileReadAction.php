<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Controller\Api\Log;

use Ergonode\Account\Domain\Query\LogQueryInterface;
use Ergonode\Account\Infrastructure\Grid\LogGrid;
use Ergonode\Core\Application\Provider\TokenStorageProviderInterface;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Grid\Response\GridResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile/log", methods={"GET"})
 */
class ProfileReadAction
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
     * @var TokenStorageProviderInterface
     */
    private $tokenStorageProvider;

    /**
     * @param LogQueryInterface             $query
     * @param LogGrid                       $grid
     * @param TokenStorageProviderInterface $tokenStorageProvider
     */
    public function __construct(
        LogQueryInterface $query,
        LogGrid $grid,
        TokenStorageProviderInterface $tokenStorageProvider
    ) {
        $this->query = $query;
        $this->grid = $grid;
        $this->tokenStorageProvider = $tokenStorageProvider;
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
     *     enum={"recorded_at", "author", "author_id", "event"},
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
     *     name="show",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"COLUMN","DATA"},
     *     description="Specify what response should containts"
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
        $user = $this->tokenStorageProvider->getUser();

        return new GridResponse(
            $this->grid,
            $configuration,
            $this->query->getDataSet($user->getId()),
            $user->getLanguage()
        );
    }
}
