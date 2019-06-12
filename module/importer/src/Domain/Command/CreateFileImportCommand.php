<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command;

use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Reader\Domain\Entity\ReaderId;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateFileImportCommand
{
    /**
     * @var ImportId
     *
     * @JMS\Type("Ergonode\Importer\Domain\Entity\ImportId")
     */
    private $id;

    /**
     * @var TransformerId
     *
     * @JMS\Type("Ergonode\Transformer\Domain\Entity\TransformerId")
     */
    private $transformerId;

    /**
     * @var ReaderId
     *
     * @JMS\Type("Ergonode\Importer\Domain\Entity\ReaderId")
     */
    private $readerId;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $filename;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $action;

    /**
     * @param string        $name
     * @param string        $fileName
     * @param ReaderId      $readerId
     * @param TransformerId $transformerId
     * @param string        $action
     *
     * @throws \Exception
     */
    public function __construct(string $name, string $fileName, ReaderId $readerId, TransformerId $transformerId, string $action)
    {
        $this->id = ImportId::generate();
        $this->name = $name;
        $this->filename = $fileName;
        $this->readerId = $readerId;
        $this->transformerId = $transformerId;
        $this->action = $action;
    }

    /**
     * @return ImportId
     */
    public function getId(): ImportId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return TransformerId
     */
    public function getTransformerId(): TransformerId
    {
        return $this->transformerId;
    }

    /**
     * @return ReaderId
     */
    public function getReaderId(): ReaderId
    {
        return $this->readerId;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
