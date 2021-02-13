<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Renderer;

use Ergonode\Grid\GridInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Ergonode\Grid\ActionInterface;

class ActionRenderer
{
    private UrlGeneratorInterface $urlGenerator;

    private AuthorizationCheckerInterface $checker;

    public function __construct(UrlGeneratorInterface $urlGenerator, AuthorizationCheckerInterface $checker)
    {
        $this->urlGenerator = $urlGenerator;
        $this->checker = $checker;
    }

    public function render(GridInterface $grid, array $row): array
    {
        $actions = [];

        $mapFunction = static function ($value) {
            return sprintf('{%s}', $value);
        };

        $keys = array_map($mapFunction, array_keys($row));
        $values = array_values($row);

        foreach ($grid->getActions() as $name => $action) {
            if ($this->isEnabled($action, $row)) {
                $actions[$name] = $this->generateLink($action, $keys, $values);
            }
        }

        return $actions;
    }

    private function isEnabled(ActionInterface $action, array $row): bool
    {
        if ($action->getPrivilege() && !$this->checker->isGranted($action->getPrivilege())) {
            return false;
        }

        foreach ($action->getConditions() as $field => $value) {
            if ($value !== $row[$field]) {
                return false;
            }
        }

        return true;
    }

    private function generateLink(ActionInterface $action, array $keys, array $values): array
    {
        $parameters = $action->getParameters();

        foreach ($parameters as &$field) {
            $field = str_replace($keys, $values, $field);
        }

        return [
            'href' => $this->urlGenerator->generate(
                $action->getRoute(),
                $parameters,
                UrlGeneratorInterface::NETWORK_PATH
            ),
            'method' => $action->getMethod(),
        ];
    }
}