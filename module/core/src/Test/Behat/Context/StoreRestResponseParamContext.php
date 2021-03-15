<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\MinkExtension\Context\RawMinkContext;
use Behatch\Json\JsonInspector;
use Behatch\Json\Json;

class StoreRestResponseParamContext extends RawMinkContext
{
    private StorageContext $storageContext;

    private JsonInspector $inspector;

    public function __construct(string $evaluationMode = 'javascript')
    {
        $this->inspector = new JsonInspector($evaluationMode);
    }

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $environment = $scope->getEnvironment();

        /** @phpstan-ignore-next-line */
        $this->storageContext = $environment->getContext(StorageContext::class);
    }

    /**
     * @Then store response param :var as :key
     *
     * @throws \Exception
     */
    public function storeResponseParam(string $var, string $key): void
    {
        $content = $this->getMink()->getSession()->getPage()->getContent();
        $json = new Json($content);

        $this->storageContext->add($key, $this->inspector->evaluate($json, $var));
    }

    /**
     * @Then print store param :key
     *
     * @throws \Exception
     */
    public function printStoreParam(string $key): void
    {
        echo $this->storageContext->get($key);
        die;
    }
}
