<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Controller\Api;

use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Grid\LanguageGrid;
use Ergonode\Grid\RequestGridConfiguration;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class LanguageController extends AbstractApiController
{
    /**
     * @var LanguageQueryInterface
     */
    private $query;
    /**
     * @var LanguageGrid
     */
    private $languageGrid;

    /**
     * LanguageController constructor.
     *
     * @param LanguageQueryInterface $query
     * @param LanguageGrid           $languageGrid
     */
    public function __construct(
        LanguageQueryInterface $query,
        LanguageGrid $languageGrid
    ) {
        $this->query = $query;
        $this->languageGrid = $languageGrid;
    }

    /**
     * @Route("/languages/{translation_language}", methods={"GET"})
     *
     * @SWG\Tag(name="Language")
     *
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="translation_language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="translation language id",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns language",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param string  $language
     * @param Request $request
     *
     * @return Response
     */
    public function getLanguage(string $language, Request $request): Response
    {
        $language = $this->query->getLanguage($language);

        return $this->createRestResponse(['language' => $language]);
    }

    /**
     * @Route("/languages", methods={"GET"})
     *
     * @SWG\Tag(name="Language")
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
     *     enum={"code","name","system"},
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
     *     name="show",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"COLUMN","DATA"},
     *     description="Specify what response should containts"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns import",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param Language $language
     * @param Request  $request
     *
     * @return Response
     */
    public function getLanguages(Language $language, Request $request): Response
    {
        $configuration = new RequestGridConfiguration($request);

        $dataSet = $this->query->getDataSet();
        $result = $this->renderGrid($this->languageGrid, $configuration, $dataSet, $language);

        return $this->createRestResponse($result);
    }
}
