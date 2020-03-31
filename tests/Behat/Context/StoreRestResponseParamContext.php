<?php

namespace App\Tests\Behat\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use StorageContext;
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
     * @param string $key
     * @param string $var
     *
     * @Then store response param :key as :var
     *
     * @throws \Exception
     */
    public function storeResponseParam(string $key, string $var): void
    {
        $content = $this->getMink()->getSession()->getPage()->getContent();
        $json = new Json($content);

        $this->storageContext->add($var, $this->inspector->evaluate($json, $key));
    }
}
