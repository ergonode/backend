<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Controller\Api\Attribute;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Attribute\Application\Form\AttributeUpdateForm;
use Ergonode\Attribute\Application\Form\Model\UpdateAttributeFormModel;
use Ergonode\Attribute\Domain\Command\UpdateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;

/**
 * @Route(
 *     name="ergonode_attribute_change",
 *     path="/attributes/{attribute}",
 *     methods={"PUT"},
 *     requirements={"attribute" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class AttributeChangeAction
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @param MessageBusInterface  $messageBus
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        MessageBusInterface $messageBus,
        FormFactoryInterface $formFactory
    ) {
        $this->messageBus = $messageBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("ATTRIBUTE_UPDATE")
     *
     * @SWG\Tag(name="Attribute")
     * @SWG\Parameter(
     *     name="attribute",
     *     in="path",
     *     type="string",
     *     description="Attribute id",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add attribute",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/attribute")
     * )
     *  @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns attribute",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @ParamConverter(class="Ergonode\Attribute\Domain\Entity\AbstractAttribute")
     *
     * @param AbstractAttribute $attribute
     * @param Request           $request
     *
     * @return Response
     */
    public function __invoke(AbstractAttribute $attribute, Request $request): Response
    {
        try {
            $model = new UpdateAttributeFormModel(new AttributeType($attribute->getType()));
            $form = $this->formFactory->create(AttributeUpdateForm::class, $model, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UpdateAttributeFormModel $data */
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

                $command = new UpdateAttributeCommand(
                    $attribute->getId(),
                    new TranslatableString($data->label),
                    new TranslatableString($data->hint),
                    new TranslatableString($data->placeholder),
                    $data->groups,
                    (array) $data->parameters,
                    $options
                );
                $this->messageBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
