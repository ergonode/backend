<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Controller\Api\Language;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Core\Application\Form\LanguageCollectionForm;
use Ergonode\Core\Application\Model\LanguageCollectionFormModel;
use Ergonode\Core\Domain\Command\UpdateLanguageCommand;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Persistence\Dbal\Repository\DbalLanguageRepository;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/languages", methods={"PUT"})
 */
class LanguageChangeAction
{
    /**
     * @var DbalLanguageRepository
     */
    private $repository;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param DbalLanguageRepository $repository
     * @param MessageBusInterface    $messageBus
     * @param FormFactoryInterface   $formFactory
     */
    public function __construct(
        DbalLanguageRepository $repository,
        MessageBusInterface $messageBus,
        FormFactoryInterface $formFactory
    ) {
        $this->repository = $repository;
        $this->messageBus = $messageBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @SWG\Tag(name="Language")
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
     *     @SWG\Schema(ref="#/definitions/languages_req")
     * )
     * @SWG\Response(
     *     response=200,
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
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        try {
            $model = new LanguageCollectionFormModel();
            $form = $this->formFactory->create(LanguageCollectionForm::class, $model, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var LanguageCollectionFormModel $data */
                $data = $form->getData();
                $languages = $data->collection->getValues();
                foreach ($languages as $language) {
                    $command = new UpdateLanguageCommand(Language::fromString($language->code), $language->active);
                    $this->messageBus->dispatch($command);
                    $this->repository->save(Language::fromString($language->code), $language->active);
                }

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
