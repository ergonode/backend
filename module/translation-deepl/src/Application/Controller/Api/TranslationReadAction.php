<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\TranslationDeepl\Application\Form\TranslationDeeplForm;
use Ergonode\TranslationDeepl\Application\Model\Form\TranslationDeeplFormModel;
use Ergonode\TranslationDeepl\Infrastructure\Provider\TranslationProviderInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/translation/deepl", methods={"GET"})
 */
class TranslationReadAction
{
    /**
     * @var TranslationProviderInterface
     */
    private TranslationProviderInterface $translationProvider;

    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @param TranslationProviderInterface $translationProvider
     * @param FormFactoryInterface         $formFactory
     */
    public function __construct(
        TranslationProviderInterface $translationProvider,
        FormFactoryInterface $formFactory
    ) {
        $this->translationProvider = $translationProvider;
        $this->formFactory = $formFactory;
    }

    /**
     * @SWG\Tag(name="Translation Deepl")
     * @SWG\Parameter(
     *     name="content",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="Content which would be translated"
     * )
     * @SWG\Parameter(
     *     name="source_language",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="Source language"
     * )
     * @SWG\Parameter(
     *     name="target_language",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="Target language"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns translated content"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        try {
            $model = new TranslationDeeplFormModel();
            $form = $this
                ->formFactory
                ->create(TranslationDeeplForm::class, $model, ['method' => Request::METHOD_GET]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var TranslationDeeplFormModel $data */
                $data = $form->getData();
                $translatedContent =
                    $this->translationProvider->provide($data->content, $data->sourceLanguage, $data->targetLanguage);

                return new SuccessResponse(['content' => $translatedContent]);
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
