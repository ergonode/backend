<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Controller\Api\Multimedia;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Api\Application\Response\EmptyResponse;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Ergonode\Multimedia\Application\Form\MultimediaForm;
use Ergonode\Multimedia\Application\Model\MultimediaModel;
use Ergonode\Multimedia\Domain\Command\UpdateMultimediaCommand;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

/**
 * @Route(
 *     name="ergonode_multimedia_edit",
 *     path="/{language}/multimedia/{multimedia}",
 *     methods={"PUT"},
 *     requirements={"multimedia" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class UpdateMultimediaAction
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @param CommandBusInterface  $commandBus
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(CommandBusInterface $commandBus, FormFactoryInterface $formFactory)
    {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("MULTIMEDIA_UPDATE")
     *
     * @SWG\Tag(name="Multimedia")
     * @SWG\Parameter(
     *     name="multimedia",
     *     in="path",
     *     type="string",
     *     description="Multimedia id",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns multimedia",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param Multimedia $multimedia
     * @param Request    $request
     *
     * @return Response
     *
     * @ParamConverter(class="Ergonode\Multimedia\Domain\Entity\Multimedia")
     */
    public function __invoke(Multimedia $multimedia, Request $request): Response
    {
        try {
            $model = new MultimediaModel();
            $form = $this->formFactory->create(MultimediaForm::class, $model, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var MultimediaModel $data */
                $data = $form->getData();
                $command = new UpdateMultimediaCommand(
                    $multimedia->getId(),
                    $data->name,
                    new TranslatableString($data->alt)
                );

                $this->commandBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
