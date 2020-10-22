<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Core\Application\Model\Type\LanguageConfigurationFormTypeModel;
use Symfony\Component\Validator\Constraints as Assert;

class LanguageCollectionFormModel
{
    /**
     * @var ArrayCollection|LanguageConfigurationFormTypeModel[]
     *
     * @Assert\All({
     *      @Assert\Regex("/^[a-z]{2}_[A-Z]{2}$/")
     * })
     */
    public $collection;

    public function __construct()
    {
        $this->collection = new ArrayCollection();
    }
}
