<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use Behatch\Json\JsonInspector;
use Behatch\Json\Json;

/**
 */
class StoreRestResponseParamContext extends RawMinkContext
{
    /**
     * @var StorageContext
     */
    private StorageContext $storageContext;

    /**
     * @var JsonInspector
     */
    private JsonInspector $inspector;

    /**
     * @param StorageContext $storageContext
     * @param string         $evaluationMode
     */
    public function __construct(StorageContext $storageContext, string $evaluationMode = 'javascript')
    {
        $this->storageContext = $storageContext;
        $this->inspector = new JsonInspector($evaluationMode);
    }

    /**
     * @param string $var
     * @param string $key
     *
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
     * @param string $key
     *
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
