<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Controller\Api;

use Ergonode\Account\Application\Form\Model\RoleFormModel;
use Ergonode\Account\Application\Form\RoleForm;
use Ergonode\Account\Domain\Command\Role\CreateRoleCommand;
use Ergonode\Account\Domain\Command\Role\DeleteRoleCommand;
use Ergonode\Account\Domain\Command\Role\UpdateRoleCommand;
use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Ergonode\Account\Infrastructure\Grid\RoleGrid;
use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\RequestGridConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class RoleController extends AbstractApiController
{
    /**
     * @var RoleRepositoryInterface
     */
    private $repository;

    /**
     * @var RoleQueryInterface
     */
    private $query;

    /**
     * @var RoleGrid
     */
    private $grid;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param RoleRepositoryInterface $repository
     * @param RoleQueryInterface      $query
     * @param RoleGrid                $grid
     * @param MessageBusInterface     $messageBus
     */
    public function __construct(RoleRepositoryInterface $repository, RoleQueryInterface $query, RoleGrid $grid, MessageBusInterface $messageBus)
    {
        $this->repository = $repository;
        $this->query = $query;
        $this->grid = $grid;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/roles", methods={"GET"})
     *
     * @IsGranted("USER_ROLE_READ")
     *
     * @SWG\Tag(name="Account")
     *
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
     *     description="Returns Roles collection",
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
    public function getRoles(Language $language, Request $request): Response
    {
        $configuration = new RequestGridConfiguration($request);

        $result = $this->renderGrid($this->grid, $configuration, $this->query->getDataSet(), $language);

        return $this->createRestResponse($result);
    }

    /**
     * @Route("/roles/{role}", methods={"GET"}, requirements={"role"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("USER_ROLE_READ")
     *
     * @SWG\Tag(name="Account")
     *
     * @SWG\Parameter(
     *     name="role",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Role Id",
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
     *     description="Returns role data",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param string $role
     *
     * @return Response
     */
    public function getRole(string $role): Response
    {
        $id = new RoleId($role);
        $role = $this->repository->load($id);

        if ($role !== null) {
            return $this->createRestResponse($role);
        }

        throw new NotFoundHttpException('Role data not found');
    }

    /**
     * @Route("/roles", methods={"POST"})
     *
     * @IsGranted("USER_ROLE_CREATE")
     *
     * @SWG\Tag(name="Account")
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
     *     description="Add attribute",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/role")
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns role data",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createRole(Request $request): Response
    {
        try {
            $model = new RoleFormModel();
            $form = $this->createForm(RoleForm::class, $model);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var RoleFormModel $data */
                $data = $form->getData();
                $command = new CreateRoleCommand($data->name, $data->description, $data->privileges);
                $this->messageBus->dispatch($command);

                return $this->createRestResponse(['id' => $command->getId()], [], Response::HTTP_CREATED);
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        return $this->createRestResponse($form, [], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/roles/{role}", methods={"PUT"}, requirements={"role"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("USER_ROLE_UPDATE")
     *
     * @SWG\Tag(name="Account")
     *
     * @SWG\Parameter(
     *     name="role",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Role Id",
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
     *     name="body",
     *     in="body",
     *     description="Add attribute",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/role")
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns role data",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param string  $role
     * @param Request $request
     *
     * @return Response
     */
    public function updateRole(string $role, Request $request): Response
    {
        try {
            $roleId = new RoleId($role);

            $model = new RoleFormModel();
            $form = $this->createForm(RoleForm::class, $model, ['method' => Request::METHOD_PUT]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var RoleFormModel $data */
                $data = $form->getData();
                $command = new UpdateRoleCommand($roleId, $data->name, $data->description, $data->privileges);
                $this->messageBus->dispatch($command);

                return $this->createRestResponse(['id' => $command->getId()], [], Response::HTTP_CREATED);
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        return $this->createRestResponse($form, [], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/roles/{role}", methods={"DELETE"}, requirements={"role"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("USER_ROLE_DELETE")
     *
     * @SWG\Tag(name="Account")
     *
     * @SWG\Parameter(
     *     name="role",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Role Id",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns role data",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @SWG\Response(
     *     response=422,
     *     description="Can't delete Role",
     * )
     *
     * @param string $role
     *
     * @return Response
     */
    public function deleteRole(string $role): Response
    {
        try {
            $roleId = new RoleId($role);
            $roleUsersCount = $this->query->getRoleUsersCount($roleId);
            if (0 === $roleUsersCount) {
                $command = new DeleteRoleCommand($roleId);
                $this->messageBus->dispatch($command);

                return $this->createRestResponse(['id' => $command->getId()], [], Response::HTTP_ACCEPTED);
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new UnprocessableEntityHttpException('Can\'t delete role, %s user are assigned to it');
    }
}
