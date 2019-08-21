<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Application\Controller\Api;

use Ergonode\Core\Application\Exception\FormValidationHttpException;
use Ergonode\Core\Application\Response\SuccessResponse;
use Ergonode\TranslationDeepl\Application\Form\TranslationDeeplForm;
use Ergonode\TranslationDeepl\Application\Model\Form\TranslationDeeplFormModel;
use Ergonode\TranslationDeepl\Infrastructure\Provider\TranslationProviderInterface;
use Ergonode\TranslationDeepl\Infrastructure\Provider\UsageDeeplProviderInterface;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class TranslationDeeplController extends AbstractController
{
    /**
     * @var TranslationProviderInterface
     */
    private $translationProvider;
    /**
     * @var UsageDeeplProviderInterface
     */
    private $usageProvider;

    /**
     * TranslationDeeplController constructor.
     *
     * @param TranslationProviderInterface $translationProvider
     * @param UsageDeeplProviderInterface  $usageProvider
     */
    public function __construct(TranslationProviderInterface $translationProvider, UsageDeeplProviderInterface $usageProvider)
    {
        $this->translationProvider = $translationProvider;
        $this->usageProvider = $usageProvider;
    }

    /**
     * @Route("/translation/deepl", methods={"GET"})
     *
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
    public function getTranslation(Request $request): Response
    {
        try {
            $model = new TranslationDeeplFormModel();
            $form = $this->createForm(TranslationDeeplForm::class, $model, ['method' => Request::METHOD_GET]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var TranslationDeeplFormModel $data */
                $data = $form->getData();
                $translatedContent = $this->translationProvider->provide($data->content, $data->sourceLanguage, $data->targetLanguage);

                return new SuccessResponse(['content' => $translatedContent]);
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/translation/usage", methods={"GET"})
     *
     * @SWG\Tag(name="Translation Deepl")
     * @SWG\Response(
     *     response=200,
     *     description="Returns usage",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @return Response
     */
    public function getUsage(): Response
    {
        $usage = $this->usageProvider->provide();

        return new SuccessResponse([
            'current' => $usage->getCharacterCount(),
            'limit' => $usage->getCharacterLimit(),
        ]);
    }
}
