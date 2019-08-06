<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);


namespace Ergonode\TranslationDeepl\Application\Controller\Api;


use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\TranslationDeepl\Application\Form\TranslationDeeplForm;
use Ergonode\TranslationDeepl\Application\Model\Form\TranslationDeeplFormModel;
use Ergonode\TranslationDeepl\Domain\Entity\TranslationDeeplId;
use Ergonode\TranslationDeepl\Domain\Repository\TranslationDeeplRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;

class TranslationDeeplController extends AbstractApiController
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;
    /**
     * @var TranslationDeeplRepositoryInterface
     */
    private $repository;

    public function __construct(MessageBusInterface $messageBus, TranslationDeeplRepositoryInterface $repository)
    {

        $this->messageBus = $messageBus;
        $this->repository = $repository;
    }

    public function getTranslation(Request $request): Response
    {
        try{
            $model = new TranslationDeeplFormModel;
            $form = $this->createForm(TranslationDeeplForm::class, $model);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var TranslationDeeplFormModel $data */
                $data = $form->getData();

            }
        } catch (InvalidPropertyPathException $exception) {
            return $this->createRestResponse(['code' => Response::HTTP_BAD_REQUEST, 'message' => 'Invalid JSON format'], [], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            return $this->createRestResponse([$exception->getMessage(), explode(PHP_EOL, $exception->getTraceAsString())], [], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
        $translation = $this->repository->load(new TranslationDeeplId($translation));
        return $this->createRestResponse($translation);

    }

    public function getStatus()
    {
    }
}
