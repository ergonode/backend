<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\ValueObject\Language;

class LanguageTest extends TestCase
{
    /**
     * @dataProvider validLanguage
     */
    public function testValidLanguageCreation(string $code): void
    {
        $language = new Language($code);
        self::assertSame($code, $language->getCode());
    }

    /**
     * @dataProvider invalidLanguage
     */
    public function testInvalidLanguageCreation(string $hex): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Language($hex);
    }

    public function testLanguageEquality(): void
    {
        $language1 = new Language('en_GB');
        $language2 = new Language('en_GB');
        $language3 = new Language('ru_RU');

        self::assertTrue($language1->isEqual($language2));
        self::assertTrue($language2->isEqual($language1));
        self::assertFalse($language1->isEqual($language3));
        self::assertFalse($language2->isEqual($language3));
        self::assertFalse($language3->isEqual($language1));
        self::assertFalse($language3->isEqual($language2));
    }

    public function testString(): void
    {
        $code = 'en_GB';
        $language = Language::fromString($code);

        self::assertEquals($language->__toString(), $code);
    }

    /**
     * @return array
     */
    public function validLanguage(): array
    {
        return [
            ['af_ZA'],
            ['ar_AE'],
            ['ar_BH'],
            ['ar_DZ'],
            ['ar_EG'],
            ['ar_IQ'],
            ['ar_JO'],
            ['ar_KW'],
            ['ar_LB'],
            ['ar_LY'],
            ['ar_MA'],
            ['ar_OM'],
            ['ar_QA'],
            ['ar_SA'],
            ['ar_SY'],
            ['ar_TN'],
            ['ar_YE'],
            ['az_AZ'],
            ['az_AZ'],
            ['be_BY'],
            ['bg_BG'],
            ['bs_BA'],
            ['ca_ES'],
            ['cs_CZ'],
            ['cy_GB'],
            ['da_DK'],
            ['de_AT'],
            ['de_CH'],
            ['de_DE'],
            ['de_LI'],
            ['de_LU'],
            ['dv_MV'],
            ['el_GR'],
            ['en_AU'],
            ['en_BZ'],
            ['en_CA'],
            ['en_CB'],
            ['en_GB'],
            ['en_IE'],
            ['en_JM'],
            ['en_NZ'],
            ['en_PH'],
            ['en_TT'],
            ['en_US'],
            ['en_ZA'],
            ['en_ZW'],
            ['es_AR'],
            ['es_BO'],
            ['es_CL'],
            ['es_CO'],
            ['es_CR'],
            ['es_DO'],
            ['es_EC'],
            ['es_ES'],
            ['es_ES'],
            ['es_GT'],
            ['es_HN'],
            ['es_MX'],
            ['es_NI'],
            ['es_PA'],
            ['es_PE'],
            ['es_PR'],
            ['es_PY'],
            ['es_SV'],
            ['es_UY'],
            ['es_VE'],
            ['et_EE'],
            ['eu_ES'],
            ['fa_IR'],
            ['fi_FI'],
            ['fo_FO'],
            ['fr_BE'],
            ['fr_CA'],
            ['fr_CH'],
            ['fr_FR'],
            ['fr_LU'],
            ['fr_MC'],
            ['gl_ES'],
            ['gu_IN'],
            ['he_IL'],
            ['hi_IN'],
            ['hr_BA'],
            ['hr_HR'],
            ['hu_HU'],
            ['hy_AM'],
            ['id_ID'],
            ['is_IS'],
            ['it_CH'],
            ['it_IT'],
            ['ja_JP'],
            ['ka_GE'],
            ['kk_KZ'],
            ['kn_IN'],
            ['ko_KR'],
            ['ky_KG'],
            ['lt_LT'],
            ['lv_LV'],
            ['mi_NZ'],
            ['mk_MK'],
            ['mn_MN'],
            ['mr_IN'],
            ['ms_BN'],
            ['ms_MY'],
            ['mt_MT'],
            ['nb_NO'],
            ['nl_BE'],
            ['nl_NL'],
            ['nn_NO'],
            ['ns_ZA'],
            ['pa_IN'],
            ['pl_PL'],
            ['ps_AR'],
            ['pt_BR'],
            ['pt_PT'],
            ['qu_BO'],
            ['qu_EC'],
            ['qu_PE'],
            ['ro_RO'],
            ['ru_RU'],
            ['sa_IN'],
            ['se_FI'],
            ['se_NO'],
            ['se_SE'],
            ['se_SE'],
            ['se_SE'],
            ['sk_SK'],
            ['sl_SI'],
            ['sq_AL'],
            ['sr_BA'],
            ['sr_BA'],
            ['sr_SP'],
            ['sr_SP'],
            ['sv_FI'],
            ['sv_SE'],
            ['sw_KE'],
            ['ta_IN'],
            ['te_IN'],
            ['th_TH'],
            ['tl_PH'],
            ['tn_ZA'],
            ['tr_TR'],
            ['tt_RU'],
            ['uk_UA'],
            ['ur_PK'],
            ['uz_UZ'],
            ['uz_UZ'],
            ['vi_VN'],
            ['xh_ZA'],
            ['zh_CN'],
            ['zh_HK'],
            ['zh_MO'],
            ['zh_SG'],
            ['zh_TW'],
            ['zu_ZA'],
        ];
    }

    /**
     * @return array
     */
    public function invalidLanguage(): array
    {
        return [
            ['pl-pl'],
            ['PLPL'],
            [''],
            ['XX-UU'],
            ['ENGLISH'],
            ['any incorrect phrase'],
        ];
    }
}
