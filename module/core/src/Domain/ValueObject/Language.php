<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\ValueObject;

/**
 */
class Language
{
    public const PL = 'PL';
    public const EN = 'EN';
    public const DE = 'DE';
    public const ZH = 'ZH';
    public const FR = 'FR';
    public const RU = 'RU';
    public const IT = 'IT';
    public const ES = 'ES';
    public const UK = 'UK';
    public const RO = 'RO';
    public const NL = 'NL';
    public const HU = 'HU';
    public const TR = 'TR';
    public const EL = 'EL';
    public const CS = 'CS';
    public const PT = 'PT';
    public const SV = 'SV';
    public const SR = 'SR';
    public const BG = 'BG';
    public const HR = 'HR';
    public const DA = 'DA';
    public const SQ = 'SQ';
    public const FI = 'FI';
    public const NO = 'NO';
    public const SK = 'SK';
    public const LT = 'LT';
    public const BS = 'BS';
    public const SL = 'SL';
    public const LV = 'LV';
    public const MK = 'MK';
    public const ET = 'ET';
    public const KK = 'KK';
    public const HI = 'HI';
    public const AR = 'AR';
    public const JA = 'JA';

    public const AVAILABLE = [
        self::PL,
        self::EN,
        self::DE,
        self::ZH,
        self::FR,
        self::RU,
        self::IT,
        self::ES,
        self::UK,
        self::RO,
        self::NL,
        self::HU,
        self::TR,
        self::EL,
        self::CS,
        self::PT,
        self::SV,
        self::SR,
        self::BG,
        self::HR,
        self::DA,
        self::SQ,
        self::FI,
        self::NO,
        self::SK,
        self::LT,
        self::BS,
        self::SL,
        self::LV,
        self::MK,
        self::ET,
        self::KK,
        self::HI,
        self::AR,
        self::JA,
    ];

    /**
     * @var string
     */
    private $code;

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = strtoupper(trim($code));
        if (!self::isValid($this->code)) {
            throw new \InvalidArgumentException(\sprintf('Code "%s" is not valid language code', $code));
        }
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getCode();
    }


    /**
     * @param string $code
     *
     * @return Language
     */
    public static function fromString(string $code): self
    {
        return new self($code);
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public static function isValid(string $code): bool
    {
        return \in_array($code, self::AVAILABLE, true);
    }
}
