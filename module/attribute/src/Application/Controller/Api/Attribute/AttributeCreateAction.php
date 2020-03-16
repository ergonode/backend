<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Controller\Api\Attribute;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Attribute\Application\Form\AttributeCreateForm;
use Ergonode\Attribute\Application\Form\Model\CreateAttributeFormModel;
use Ergonode\Attribute\Domain\Command\CreateAttributeCommand;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;

/**
 * @Route("/attributes", methods={"POST"})
 */
class AttributeCreateAction
{
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @param FormFactoryInterface $formFactory
     * @param MessageBusInterface  $messageBus
     */
    public function __construct(FormFactoryInterface $formFactory, MessageBusInterface $messageBus)
    {
        $this->formFactory = $formFactory;
        $this->messageBus = $messageBus;
    }

    /**
     * @IsGranted("ATTRIBUTE_CREATE")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add attribute",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/attribute")
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
     *     response=201,
     *     description="Returns attribute ID",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
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
            $model = new CreateAttributeFormModel();
            $form = $this->formFactory->create(AttributeCreateForm::class, $model);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var CreateAttributeFormModel $data */
                $data = $form->getData();

                $options = [];
                foreach ($data->options as $model) {
                    if (is_array($model->value)) {
                        $options[$model->key] = new MultilingualOption(new TranslatableString($model->value));
                    } elseif (is_string($model->value)) {
                        $options[$model->key] = new StringOption($model->value);
                    } else {
                        $options[$model->key] = null;
                    }
                }

                $command = new CreateAttributeCommand(
                    $data->type,
                    $data->code,
                    new TranslatableString($data->label),
                    new TranslatableString($data->hint),
                    new TranslatableString($data->placeholder),
                    $data->multilingual,
                    $data->groups,
                    (array) $data->parameters,
                    $options
                );
                $this->messageBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
