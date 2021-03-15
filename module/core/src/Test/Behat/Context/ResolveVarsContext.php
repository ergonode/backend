<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\BeforeStepScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\StepNode;
use Behat\Gherkin\Node\TableNode;
use ReflectionClass;
use ReflectionException;

class ResolveVarsContext implements Context
{
    private StorageContext $storageContext;

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $environment = $scope->getEnvironment();

        /** @phpstan-ignore-next-line */
        $this->storageContext = $environment->getContext(StorageContext::class);
    }

    /**
     * @throws ReflectionException
     *
     * @BeforeStep
     */
    public function resolveVarsContext(BeforeStepScope $event): void
    {
        $step = $event->getStep();
        $args = $step->getArguments();

        $newArguments = [];
        foreach ($args as $key => $argument) {
            $newArguments[$key] = $argument;
            switch (true) {
                case $argument instanceof TableNode:
                    $newArguments[$key] = $this->resolveTableNode($argument);
                    break;
                case $argument instanceof PyStringNode:
                    $newArguments[$key] = $this->resolvePyStringNode($argument);
                    break;
            }
        }

        $this->setArguments($step, $newArguments) ;

        $text = $this->resolveText($event->getStep()->getText());
        $this->setText($step, $text);
    }

    private function resolveTableNode(TableNode $tableNode): TableNode
    {
        $table = $tableNode->getTable();

        foreach ($table as $lineNo => $lineValues) {
            $newValues = [];
            foreach ($lineValues as $lineValue) {
                $newValues[] = $this->resolveText($lineValue);
            }
            $table[$lineNo] = $newValues;
        }

        return new TableNode($table);
    }

    private function resolvePyStringNode(PyStringNode $stringNode): PyStringNode
    {
        $newStringNode = $this->resolveText($stringNode->getRaw());

        return new PyStringNode(explode("\n", $newStringNode), $stringNode->getLine());
    }

    /**
     * @param array $value
     *
     * @throws ReflectionException
     */
    private function setArguments(StepNode $stepNode, array $value): void
    {
        $reflection = new ReflectionClass($stepNode);
        $argProp    = $reflection->getProperty('arguments');
        $argProp->setAccessible(true);
        $argProp->setValue($stepNode, $value);
    }

    private function resolveText(string $text): string
    {
        return $this->storageContext->replaceVars($text);
    }

    /**
     * @throws ReflectionException
     */
    private function setText(StepNode $stepNode, string $value): void
    {
        $reflection = new ReflectionClass($stepNode);
        $argProp    = $reflection->getProperty('text');
        $argProp->setAccessible(true);
        $argProp->setValue($stepNode, $value);
    }
}
