<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class LanguageTest extends TestCase
{
    /**
     * @dataProvider validLanguage
     *
     * @param string $code
     */
    public function testValidLanguageCreation(string $code): void
    {
        $language = new Language($code);
        $this->assertSame($code, $language->getCode());
    }

    /**
     * @dataProvider invalidLanguage
     *
     * @param string $hex
     *
     */
    public function testInvalidLanguageCreation(string $hex): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Language($hex);
    }

    /**
     */
    public function testLanguageEquality(): void
    {
        $language1 = new Language('en');
        $language2 = new Language('en');
        $language3 = new Language('ru');

        $this->assertTrue($language1->isEqual($language2));
        $this->assertTrue($language2->isEqual($language1));
        $this->assertFalse($language1->isEqual($language3));
        $this->assertFalse($language2->isEqual($language3));
        $this->assertFalse($language3->isEqual($language1));
        $this->assertFalse($language3->isEqual($language2));
    }

    /**
     * @return array
     */
    public function validLanguage(): array
    {
        return [
            ['af'],
            ['af_ZA'],
            ['ar'],
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
            ['az'],
            ['az_AZ'],
            ['az_AZ'],
            ['be'],
            ['be_BY'],
            ['bg'],
            ['bg_BG'],
            ['bs_BA'],
            ['ca'],
            ['ca_ES'],
            ['cs'],
            ['cs_CZ'],
            ['cy'],
            ['cy_GB'],
            ['da'],
            ['da_DK'],
            ['de'],
            ['de_AT'],
            ['de_CH'],
            ['de_DE'],
            ['de_LI'],
            ['de_LU'],
            ['dv'],
            ['dv_MV'],
            ['el'],
            ['el_GR'],
            ['en'],
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
            ['eo'],
            ['es'],
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
            ['et'],
            ['et_EE'],
            ['eu'],
            ['eu_ES'],
            ['fa'],
            ['fa_IR'],
            ['fi'],
            ['fi_FI'],
            ['fo'],
            ['fo_FO'],
            ['fr'],
            ['fr_BE'],
            ['fr_CA'],
            ['fr_CH'],
            ['fr_FR'],
            ['fr_LU'],
            ['fr_MC'],
            ['gl'],
            ['gl_ES'],
            ['gu'],
            ['gu_IN'],
            ['he'],
            ['he_IL'],
            ['hi'],
            ['hi_IN'],
            ['hr'],
            ['hr_BA'],
            ['hr_HR'],
            ['hu'],
            ['hu_HU'],
            ['hy'],
            ['hy_AM'],
            ['id'],
            ['id_ID'],
            ['is'],
            ['is_IS'],
            ['it'],
            ['it_CH'],
            ['it_IT'],
            ['ja'],
            ['ja_JP'],
            ['ka'],
            ['ka_GE'],
            ['kk'],
            ['kk_KZ'],
            ['kn'],
            ['kn_IN'],
            ['ko'],
            ['ko_KR'],
            ['ky'],
            ['ky_KG'],
            ['lt'],
            ['lt_LT'],
            ['lv'],
            ['lv_LV'],
            ['mi'],
            ['mi_NZ'],
            ['mk'],
            ['mk_MK'],
            ['mn'],
            ['mn_MN'],
            ['mr'],
            ['mr_IN'],
            ['ms'],
            ['ms_BN'],
            ['ms_MY'],
            ['mt'],
            ['mt_MT'],
            ['nb'],
            ['nb_NO'],
            ['nl'],
            ['nl_BE'],
            ['nl_NL'],
            ['nn_NO'],
            ['ns'],
            ['ns_ZA'],
            ['pa'],
            ['pa_IN'],
            ['pl'],
            ['pl_PL'],
            ['ps'],
            ['ps_AR'],
            ['pt'],
            ['pt_BR'],
            ['pt_PT'],
            ['qu'],
            ['qu_BO'],
            ['qu_EC'],
            ['qu_PE'],
            ['ro'],
            ['ro_RO'],
            ['ru'],
            ['ru_RU'],
            ['sa'],
            ['sa_IN'],
            ['se'],
            ['se_FI'],
            ['se_NO'],
            ['se_SE'],
            ['se_SE'],
            ['se_SE'],
            ['sk'],
            ['sk_SK'],
            ['sl'],
            ['sl_SI'],
            ['sq'],
            ['sq_AL'],
            ['sr_BA'],
            ['sr_BA'],
            ['sr_SP'],
            ['sr_SP'],
            ['sv'],
            ['sv_FI'],
            ['sv_SE'],
            ['sw'],
            ['sw_KE'],
            ['ta'],
            ['ta_IN'],
            ['te'],
            ['te_IN'],
            ['th'],
            ['th_TH'],
            ['tl'],
            ['tl_PH'],
            ['tn'],
            ['tn_ZA'],
            ['tr'],
            ['tr_TR'],
            ['tt'],
            ['tt_RU'],
            ['ts'],
            ['uk'],
            ['uk_UA'],
            ['ur'],
            ['ur_PK'],
            ['uz'],
            ['uz_UZ'],
            ['uz_UZ'],
            ['vi'],
            ['vi_VN'],
            ['xh'],
            ['xh_ZA'],
            ['zh'],
            ['zh_CN'],
            ['zh_HK'],
            ['zh_MO'],
            ['zh_SG'],
            ['zh_TW'],
            ['zu'],
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
