<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Controller\Api;

use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Core\Application\Form\LanguageCollectionForm;
use Ergonode\Core\Application\Model\LanguageCollectionFormModel;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Grid\LanguageGrid;
use Ergonode\Core\Persistence\Dbal\Repository\DbalLanguageRepository;
use Ergonode\Grid\RequestGridConfiguration;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
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
     * @var DbalLanguageRepository
     */
    private $repository;

    /**
     * LanguageController constructor.
     *
     * @param LanguageQueryInterface $query
     * @param LanguageGrid           $languageGrid
     * @param DbalLanguageRepository $repository
     */
    public function __construct(
        LanguageQueryInterface $query,
        LanguageGrid $languageGrid,
        DbalLanguageRepository $repository
    ) {
        $this->query = $query;
        $this->languageGrid = $languageGrid;
        $this->repository = $repository;
    }

    /**
     * @Route("/languages/{translationLanguage}", methods={"GET"})
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
     *     name="translationLanguage",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="translation language code",
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
     * @param string  $translationLanguage
     * @param Request $request
     *
     * @return Response
     */
    public function getLanguage(string $translationLanguage, Request $request): Response
    {
        $language = $this->query->getLanguage($translationLanguage);

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
     *     enum={"code","name","active"},
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

    /**
     * @Route("/languages", methods={"PUT"})
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
     *     name="body",
     *     in="body",
     *     description="Category body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/languages")
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Update language",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form validation error",
     * )
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function updateLanguage(Request $request): Response
    {
        try {
            $model = new LanguageCollectionFormModel();
            $form = $this->createForm(LanguageCollectionForm::class, $model, ['method' => 'PUT']);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var LanguageCollectionFormModel $data */
                $data = $form->getData();
                $languages = $data->collection->getValues();
                foreach ($languages as $language) {
                    $this->repository->save(Language::fromString($language->code), $language->active);
                }

                return $this->createRestResponse(['message' => "Updated"]);
            }

        } catch (InvalidPropertyPathException $exception) {
            return $this->createRestResponse(['code' => Response::HTTP_BAD_REQUEST, 'message' => 'Invalid JSON format'], [], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            return $this->createRestResponse([\get_class($exception), $exception->getMessage(), $exception->getTraceAsString()], [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->createRestResponse($form, [], Response::HTTP_BAD_REQUEST);
    }
}
