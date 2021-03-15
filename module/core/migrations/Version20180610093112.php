<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

final class Version20180610093112 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');

        $this->addSql(
            'CREATE TABLE language (
                      id UUID NOT NULL, 
                      iso VARCHAR(5) NOT NULL, 
                      system BOOLEAN NOT NULL DEFAULT false, 
                      PRIMARY KEY(id)
              )'
        );
        $this->addSql(
            'CREATE TABLE translation
                    (
                        translation_id UUID NOT NULL,
                        language VARCHAR(5) NOT NULL,
                        PRIMARY KEY(translation_id, language)
                    )'
        );

        foreach ($this->getLanguages() as $iso) {
            $this->addSql(
                'INSERT INTO language (id, iso) VALUES (?, ?)',
                [Uuid::uuid4()->toString(), $iso]
            );
        }

        $this->addSql('UPDATE language SET system = true WHERE iso in (\'en_GB\', \'pl_PL\')');

        $this->addSql('ALTER TABLE language rename column system to active');

        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');

        $this->addSql(
            'CREATE TABLE unit (
                    id UUID NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    symbol VARCHAR(16) NOT NULL,
                    PRIMARY KEY(id))'
        );

        $this->addSql('INSERT INTO privileges_group (area) VALUES (?)', ['Settings']);
        $this->createPrivileges([
            'SETTINGS_CREATE' => 'Settings',
            'SETTINGS_READ' => 'Settings',
            'SETTINGS_UPDATE' => 'Settings',
            'SETTINGS_DELETE' => 'Settings',
        ]);

        $this->createEventStoreEvents([
            'Ergonode\Core\Domain\Event\UnitSymbolChangedEvent'
            => 'Unit symbol changed',
            'Ergonode\Core\Domain\Event\UnitNameChangedEvent'
            => 'Unit name changed',
            'Ergonode\Core\Domain\Event\UnitDeletedEvent'
            => 'Unit deleted',
            'Ergonode\Core\Domain\Event\UnitCreatedEvent'
            => 'Unit created',
        ]);

        $this->addSql('
            CREATE TABLE IF NOT EXISTS language_tree (
                id UUID NOT NULL,
                parent_id UUID DEFAULT NULL,
                lft INT NOT NULL,
                rgt INT NOT NULL,
                code VARCHAR(5) NOT NULL,
                PRIMARY KEY(id)
            )
        ');

        $this->addSql(
            'INSERT INTO language_tree (id, lft, rgt, code)
                    SELECT id, 1, 4, iso FROM "language" WHERE iso=\'en_GB\''
        );

        $this->addSql(
            'INSERT INTO language_tree (id, lft, rgt, code, parent_id)
                    SELECT child.id, 2, 3, child.iso, parent.id
                    FROM "language" child, "language" parent
                    WHERE child.iso=\'pl_PL\' AND parent.iso=\'en_GB\''
        );
    }

    /**
     * @return array
     */
    private function getLanguages(): array
    {
        return [
            'af_ZA' => 'af_ZA',
            'ar_AE' => 'ar_AE',
            'ar_BH' => 'ar_BH',
            'ar_DZ' => 'ar_DZ',
            'ar_EG' => 'ar_EG',
            'ar_IQ' => 'ar_IQ',
            'ar_JO' => 'ar_JO',
            'ar_KW' => 'ar_KW',
            'ar_LB' => 'ar_LB',
            'ar_LY' => 'ar_LY',
            'ar_MA' => 'ar_MA',
            'ar_OM' => 'ar_OM',
            'ar_QA' => 'ar_QA',
            'ar_SA' => 'ar_SA',
            'ar_SY' => 'ar_SY',
            'ar_TN' => 'ar_TN',
            'ar_YE' => 'ar_YE',
            'az_AZ' => 'az_AZ',
            'be_BY' => 'be_BY',
            'bg_BG' => 'bg_BG',
            'bs_BA' => 'bs_BA',
            'ca_ES' => 'ca_ES',
            'cs_CZ' => 'cs_CZ',
            'cy_GB' => 'cy_GB',
            'da_DK' => 'da_DK',
            'de_AT' => 'de_AT',
            'de_CH' => 'de_CH',
            'de_DE' => 'de_DE',
            'de_LI' => 'de_LI',
            'de_LU' => 'de_LU',
            'dv_MV' => 'dv_MV',
            'el_GR' => 'el_GR',
            'en_AU' => 'en_AU',
            'en_BZ' => 'en_BZ',
            'en_CA' => 'en_CA',
            'en_CB' => 'en_CB',
            'en_GB' => 'en_GB',
            'en_GH' => 'en_GH',
            'en_IE' => 'en_IE',
            'en_IL' => 'en_IL',
            'en_JM' => 'en_JM',
            'en_NG' => 'en_NG',
            'en_NZ' => 'en_NZ',
            'en_PH' => 'en_PH',
            'en_TT' => 'en_TT',
            'en_US' => 'en_US',
            'en_ZA' => 'en_ZA',
            'en_ZW' => 'en_ZW',
            'es_AR' => 'es_AR',
            'es_BO' => 'es_BO',
            'es_CL' => 'es_CL',
            'es_CO' => 'es_CO',
            'es_CR' => 'es_CR',
            'es_DO' => 'es_DO',
            'es_EC' => 'es_EC',
            'es_ES' => 'es_ES',
            'es_GT' => 'es_GT',
            'es_HN' => 'es_HN',
            'es_MX' => 'es_MX',
            'es_NI' => 'es_NI',
            'es_PA' => 'es_PA',
            'es_PE' => 'es_PE',
            'es_PR' => 'es_PR',
            'es_PY' => 'es_PY',
            'es_SV' => 'es_SV',
            'es_UY' => 'es_UY',
            'es_VE' => 'es_VE',
            'et_EE' => 'et_EE',
            'eu_ES' => 'eu_ES',
            'fa_IR' => 'fa_IR',
            'fi_FI' => 'fi_FI',
            'fo_FO' => 'fo_FO',
            'fr_BE' => 'fr_BE',
            'fr_CA' => 'fr_CA',
            'fr_CH' => 'fr_CH',
            'fr_FR' => 'fr_FR',
            'fr_LU' => 'fr_LU',
            'fr_MA' => 'fr_MA',
            'fr_MC' => 'fr_MC',
            'gl_ES' => 'gl_ES',
            'ga_GB' => 'ga_GB',
            'gd_GB' => 'gd_GB',
            'gu_IN' => 'gu_IN',
            'he_IL' => 'he_IL',
            'hi_IN' => 'hi_IN',
            'hr_BA' => 'hr_BA',
            'hr_HR' => 'hr_HR',
            'hu_HU' => 'hu_HU',
            'hy_AM' => 'hy_AM',
            'id_ID' => 'id_ID',
            'is_IS' => 'is_IS',
            'it_CH' => 'it_CH',
            'it_IT' => 'it_IT',
            'ja_JP' => 'ja_JP',
            'ka_GE' => 'ka_GE',
            'kk_KZ' => 'kk_KZ',
            'kn_IN' => 'kn_IN',
            'ko_KR' => 'ko_KR',
            'ky_KG' => 'ky_KG',
            'lt_LT' => 'lt_LT',
            'lv_LV' => 'lv_LV',
            'mi_NZ' => 'mi_NZ',
            'mk_MK' => 'mk_MK',
            'mn_MN' => 'mn_MN',
            'mr_IN' => 'mr_IN',
            'ms_BN' => 'ms_BN',
            'ms_MY' => 'ms_MY',
            'mt_MT' => 'mt_MT',
            'nb_NO' => 'nb_NO',
            'nl_BE' => 'nl_BE',
            'nl_NL' => 'nl_NL',
            'nn_NO' => 'nn_NO',
            'ns_ZA' => 'ns_ZA',
            'pa_IN' => 'pa_IN',
            'pl_PL' => 'pl_PL',
            'ps_AR' => 'ps_AR',
            'pt_AO' => 'pt_AO',
            'pt_BR' => 'pt_BR',
            'pt_PT' => 'pt_PT',
            'qu_BO' => 'qu_BO',
            'qu_EC' => 'qu_EC',
            'qu_PE' => 'qu_PE',
            'ro_RO' => 'ro_RO',
            'ru_RU' => 'ru_RU',
            'sa_IN' => 'sa_IN',
            'se_FI' => 'se_FI',
            'se_NO' => 'se_NO',
            'se_SE' => 'se_SE',
            'sk_SK' => 'sk_SK',
            'sl_SI' => 'sl_SI',
            'sq_AL' => 'sq_AL',
            'sr_BA' => 'sr_BA',
            'sr_SP' => 'sr_SP',
            'sv_FI' => 'sv_FI',
            'sv_SE' => 'sv_SE',
            'sw_KE' => 'sw_KE',
            'ta_IN' => 'ta_IN',
            'te_IN' => 'te_IN',
            'th_TH' => 'th_TH',
            'tl_PH' => 'tl_PH',
            'tn_ZA' => 'tn_ZA',
            'tr_TR' => 'tr_TR',
            'tt_RU' => 'tt_RU',
            'ts_ZA' => 'ts_ZA',
            'uk_UA' => 'uk_UA',
            'ur_PK' => 'ur_PK',
            'uz_UZ' => 'uz_UZ',
            'vi_VN' => 'vi_VN',
            'xh_ZA' => 'xh_ZA',
            'zh_CN' => 'zh_CN',
            'zh_HK' => 'zh_HK',
            'zh_MO' => 'zh_MO',
            'zh_SG' => 'zh_SG',
            'zh_TW' => 'zh_TW',
            'zu_ZA' => 'zu_ZA',
        ];
    }

    /**
     * @param array $collection
     *
     * @throws \Exception
     */
    private function createEventStoreEvents(array $collection): void
    {
        foreach ($collection as $class => $translation) {
            $this->addSql(
                'INSERT INTO event_store_event (id, event_class, translation_key) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $class, $translation]
            );
        }
    }

    /**
     * @param array $collection
     *
     * @throws \Exception
     */
    private function createPrivileges(array $collection): void
    {
        foreach ($collection as $code => $area) {
            $this->addSql(
                'INSERT INTO privileges (id, code, area) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $code,  $area, ]
            );
        }
    }
}
