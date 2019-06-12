<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Controller\Api;

use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Importer\Application\Form\UploadForm;
use Ergonode\Importer\Application\Model\Form\UploadModel;
use Ergonode\Importer\Application\Service\Upload\UploadServiceInterface;
use Ergonode\Importer\Domain\Command\CreateFileImportCommand;
use Ergonode\Importer\Domain\Entity\AbstractImport;
use Ergonode\Importer\Domain\Query\ImportQueryInterface;
use Ergonode\Importer\Infrastructure\Grid\ImportGrid;
use Ergonode\Reader\Domain\Entity\ReaderId;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 */
class ImporterController extends AbstractApiController
{
    /**
     * @var ImportGrid
     */
    private $importGrid;

    /**
     * @var ImportQueryInterface
     */
    private $importQuery;

    /**
     * @var UploadServiceInterface
     */
    private $uploadService;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param ImportGrid             $importGrid
     * @param ImportQueryInterface   $importQuery
     * @param UploadServiceInterface $uploadService
     * @param MessageBusInterface    $messageBus
     */
    public function __construct(
        ImportGrid $importGrid,
        ImportQueryInterface $importQuery,
        UploadServiceInterface $uploadService,
        MessageBusInterface $messageBus
    ) {
        $this->importGrid = $importGrid;
        $this->importQuery = $importQuery;
        $this->uploadService = $uploadService;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("imports", methods={"GET"})
     *
     * @SWG\Tag(name="Importer")
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
    public function getImports(Language $language, Request $request): Response
    {
        $pagination = new RequestGridConfiguration($request);

        $result = $this->importGrid->render($this->importQuery->getDataSet(), $pagination, $language);

        return $this->createRestResponse($result);
    }

    /**
     * @Route("imports/{import}", methods={"GET"}, requirements={"import"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @SWG\Tag(name="Importer")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="import",
     *     in="path",
     *     type="string",
     *     description="Import id",
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
     * @param AbstractImport $import
     *
     * @ParamConverter(class="Ergonode\Importer\Domain\Entity\AbstractImport")
     *
     * @return Response
     */
    public function getImport(AbstractImport $import): Response
    {
        return $this->createRestResponse($import);
    }

    /**
     * @Route("imports/upload", methods={"POST"})
     *
     * @SWG\Tag(name="Importer")
     * @SWG\Parameter(
     *     name="upload",
     *     in="formData",
     *     type="file",
     *     description="The field used to upload file and create import",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="transformer",
     *     in="formData",
     *     type="string",
     *     description="Transformer generator id",
     * )
     * @SWG\Parameter(
     *     name="reader",
     *     in="formData",
     *     type="string",
     *     description="Reader Id",
     * )
     * @SWG\Parameter(
     *     name="action",
     *     in="formData",
     *     type="string",
     *     enum={"ATTRIBUTE", "PRODUCT", "CATEGORY", "VALUE", "ATTRIBUTE_VALUE", "IMAGE", "OPTION"},
     *     description="Processor action",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns import uuid",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Unsupported file type or file size",
     * )
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function upload(Request $request): Response
    {
        $uploadModel = new UploadModel();

        $form = $this->createForm(UploadForm::class, $uploadModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadModel $data */
            $data = $form->getData();
            $file = $this->uploadService->upload($uploadModel->upload);
            $action = $data->action;
            $command = new CreateFileImportCommand(
                $uploadModel->upload->getClientOriginalName(),
                $file->getFilename(),
                new ReaderId($data->reader),
                new TransformerId($data->transformer),
                $action
            );
            $this->messageBus->dispatch($command);

            $response = $this->createRestResponse(['id' => $command->getId()->getValue()]);
        } else {
            $response = $this->createRestResponse($form, [], Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }
}
