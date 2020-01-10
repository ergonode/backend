<?php

namespace App\Tests\Behat\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use StorageContext;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

/**
 */
class StoreRestResponseParamContext extends RawMinkContext
{
    /**
     * @var StorageContext
     */
    private $storageContext;

    /**
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * @param StorageContext   $storageContext
     * @param DecoderInterface $decoder
     */
    public function __construct(StorageContext $storageContext, DecoderInterface $decoder)
    {
        $this->storageContext = $storageContext;
        $this->decoder = $decoder;
    }

    /**
     * @param string $key
     * @param string $var
     *
     * @Then store response param :key as :var
     */
    public function storeResponseParam(string $key, string $var): void
    {
        $content = $this->getMink()->getSession()->getPage()->getContent();
        $json = $this->decoder->decode($content, 'json');
        $this->storageContext->add($var, $json[$key]);
    }
}
