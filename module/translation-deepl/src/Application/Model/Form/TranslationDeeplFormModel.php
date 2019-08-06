<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Application\Model\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\TranslationDeepl\Application\Model\Form\Type\TranslationDeeplConfigurationTypeModel;
use Symfony\Component\Validator\Constraints as Assert;


class TranslationDeeplFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Text to translation is required")
     * @Assert\Length(min="3", max="4000")
     */
    public $text;


    /**
     * @var ArrayCollection|TranslationDeeplConfigurationTypeModel[]
     *
     * @Assert\Valid()
     * @Assert\Collection()
     */
    public $configuration;

    public function __construct()
    {
        $this->configuration = new ArrayCollection();
    }
}
