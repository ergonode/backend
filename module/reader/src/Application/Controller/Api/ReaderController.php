<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Application\Controller\Api;

use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Application\Exception\ExistingRelationshipsHttpException;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Grid\Response\GridResponse;
use Ergonode\Reader\Domain\Command\CreateReaderCommand;
use Ergonode\Reader\Domain\Entity\Reader;
use Ergonode\Reader\Domain\Entity\ReaderId;
use Ergonode\Reader\Domain\Query\ReaderQueryInterface;
use Ergonode\Reader\Domain\Repository\ReaderRepositoryInterface;
use Ergonode\Reader\Infrastructure\Grid\ReaderGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class ReaderController extends AbstractController
{
    /**
     * @var ReaderRepositoryInterface
     */
    private $repository;

    /**
     * @var ReaderQueryInterface
     */
    private $query;

    /**
     * @var ReaderGrid
     */
    private $grid;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var RelationshipsResolverInterface
     */
    private $relationshipsResolver;

    /**
     * @param ReaderRepositoryInterface      $repository
     * @param ReaderQueryInterface           $query
     * @param ReaderGrid                     $grid
     * @param MessageBusInterface            $messageBus
     * @param RelationshipsResolverInterface $relationshipsResolver
     */
    public function __construct(
        ReaderRepositoryInterface $repository,
        ReaderQueryInterface $query,
        ReaderGrid $grid,
        MessageBusInterface $messageBus,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->repository = $repository;
        $this->query = $query;
        $this->grid = $grid;
        $this->messageBus = $messageBus;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    /**
     * @Route("readers", methods={"GET"})
     *
     * @SWG\Tag(name="Reader")
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
     *     name="show",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"COLUMN","DATA"},
     *     description="Specify what response should containts"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns imported data collection",
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function getReaders(Language $language, RequestGridConfiguration $configuration): Response
    {
        return new GridResponse($this->grid, $configuration, $this->query->getDataSet(), $language);
    }

    /**
     * @Route("readers/{reader}", methods={"GET"}, requirements={"reader"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @SWG\Tag(name="Reader")
     * @SWG\Parameter(
     *     name="reader",
     *     in="path",
     *     type="string",
     *     description="Reader ID",
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
     *     description="Return reader"
     * )
     *
     * @param string $reader
     *
     * @return Response
     */
    public function getReader(string $reader): Response
    {
        $reader = $this->repository->load(new ReaderId($reader));

        return new SuccessResponse($reader);
    }

    /**
     * @Route("readers", methods={"POST"})
     *
     * @SWG\Tag(name="Reader")
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     type="string",
     *     description="Reader name",
     * )
     * @SWG\Parameter(
     *     name="type",
     *     in="formData",
     *     type="string",
     *     enum={"csv"},
     *     description="Reader Type",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns reader uuid",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/error_response")
     * )
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     *
     * @todo Refactoring needed
     * @todo Validation required
     */
    public function createReader(Request $request): Response
    {
        $name = $request->request->get('name');
        $type = $request->request->get('type');

        if ($name && $type) {
            $command = new CreateReaderCommand($name, $type);
            $this->messageBus->dispatch($command);
            $response = new CreatedResponse($command->getId());
        } else {
            throw new BadRequestHttpException('error');
        }

        return $response;
    }

    /**
     * @Route("/readers/{reader}", methods={"DELETE"}, requirements={"reader"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("READER_DELETE")
     *
     * @SWG\Tag(name="Reader")
     * @SWG\Parameter(
     *     name="reader",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Reader ID"
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     * @SWG\Response(
     *     response="409",
     *     description="Existing relationships"
     * )
     *
     * @ParamConverter(class="Ergonode\Reader\Domain\Entity\Reader")
     *
     * @param Reader $reader
     *
     * @return Response
     */
    public function deleteReader(Reader $reader): Response
    {
        $relationships = $this->relationshipsResolver->resolve($reader->getId());
        if (!$relationships->isEmpty()) {
            throw new ExistingRelationshipsHttpException($relationships);
        }

        $command = new DeleteReaderCommand($reader->getId());
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }
}
