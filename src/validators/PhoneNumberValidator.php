<?php

namespace nadzif\base\validators;

use yii\helpers\ArrayHelper;

/**
 * Description of PhoneNumberValidator
 *
 * @author Mohammad Nadzif <demihuman@live.com>
 */
class PhoneNumberValidator extends \yii\validators\StringValidator
{
    /** minimum length for phoneNumber */
    public $min = 8;

    /** maximum length for phoneNumber */
    public $max = 13;

    /**
     * accepted prefixes for this validation.
     * if prefixes set to an empty array, validation will allow all prefix and make validation true
     */
    public $prefixes = [
        [
            'size' => 4,
            'data' => [
                '6221',
                '6222',
                '6224',
                '6231',
                '6261',
            ]
        ],
        [
            'size' => 5,
            'data' => [
                '62231',
                '62232',
                '62233',
                '62234',
                '62251',
                '62252',
                '62253',
                '62254',
                '62257',
                '62260',
                '62261',
                '62262',
                '62263',
                '62264',
                '62265',
                '62266',
                '62267',
                '62271',
                '62272',
                '62273',
                '62274',
                '62275',
                '62276',
                '62280',
                '62281',
                '62282',
                '62283',
                '62284',
                '62285',
                '62286',
                '62287',
                '62289',
                '62291',
                '62292',
                '62293',
                '62294',
                '62295',
                '62296',
                '62297',
                '62298',
                '62299',
                '62321',
                '62322',
                '62323',
                '62324',
                '62325',
                '62327',
                '62328',
                '62331',
                '62332',
                '62333',
                '62334',
                '62335',
                '62336',
                '62338',
                '62341',
                '62342',
                '62343',
                '62351',
                '62352',
                '62353',
                '62354',
                '62355',
                '62356',
                '62357',
                '62358',
                '62361',
                '62362',
                '62363',
                '62364',
                '62365',
                '62366',
                '62368',
                '62370',
                '62371',
                '62372',
                '62373',
                '62374',
                '62376',
                '62380',
                '62381',
                '62382',
                '62383',
                '62384',
                '62385',
                '62386',
                '62387',
                '62388',
                '62389',
                '62401',
                '62402',
                '62403',
                '62404',
                '62405',
                '62408',
                '62410',
                '62411',
                '62413',
                '62414',
                '62417',
                '62418',
                '62419',
                '62420',
                '62421',
                '62422',
                '62423',
                '62426',
                '62427',
                '62428',
                '62430',
                '62431',
                '62432',
                '62434',
                '62435',
                '62438',
                '62443',
                '62445',
                '62450',
                '62451',
                '62452',
                '62453',
                '62454',
                '62455',
                '62457',
                '62458',
                '62461',
                '62462',
                '62463',
                '62464',
                '62465',
                '62471',
                '62472',
                '62473',
                '62474',
                '62475',
                '62481',
                '62482',
                '62484',
                '62485',
                '62511',
                '62512',
                '62513',
                '62517',
                '62518',
                '62522',
                '62525',
                '62526',
                '62527',
                '62528',
                '62531',
                '62532',
                '62534',
                '62536',
                '62537',
                '62538',
                '62539',
                '62541',
                '62542',
                '62543',
                '62545',
                '62548',
                '62549',
                '62551',
                '62552',
                '62553',
                '62554',
                '62556',
                '62561',
                '62562',
                '62563',
                '62564',
                '62565',
                '62567',
                '62568',
                '62620',
                '62621',
                '62622',
                '62623',
                '62624',
                '62625',
                '62626',
                '62627',
                '62628',
                '62629',
                '62630',
                '62631',
                '62632',
                '62633',
                '62634',
                '62635',
                '62636',
                '62639',
                '62641',
                '62642',
                '62643',
                '62644',
                '62645',
                '62646',
                '62650',
                '62651',
                '62652',
                '62653',
                '62654',
                '62655',
                '62656',
                '62657',
                '62658',
                '62659',
                '62702',
                '62711',
                '62712',
                '62713',
                '62714',
                '62715',
                '62716',
                '62717',
                '62718',
                '62719',
                '62721',
                '62722',
                '62723',
                '62724',
                '62725',
                '62726',
                '62727',
                '62728',
                '62729',
                '62730',
                '62731',
                '62732',
                '62733',
                '62734',
                '62735',
                '62736',
                '62737',
                '62738',
                '62739',
                '62740',
                '62741',
                '62742',
                '62743',
                '62744',
                '62745',
                '62746',
                '62747',
                '62748',
                '62751',
                '62752',
                '62753',
                '62754',
                '62755',
                '62756',
                '62757',
                '62760',
                '62761',
                '62762',
                '62763',
                '62764',
                '62765',
                '62766',
                '62767',
                '62768',
                '62769',
                '62770',
                '62771',
                '62772',
                '62773',
                '62776',
                '62777',
                '62778',
                '62779',
                '62901',
                '62902',
                '62910',
                '62911',
                '62913',
                '62914',
                '62915',
                '62916',
                '62917',
                '62918',
                '62921',
                '62922',
                '62923',
                '62924',
                '62927',
                '62929',
                '62931',
                '62951',
                '62952',
                '62955',
                '62956',
                '62957',
                '62966',
                '62967',
                '62969',
                '62971',
                '62975',
                '62980',
                '62981',
                '62983',
                '62984',
                '62985',
                '62986',
                //BEGIN MOBILE PROVIDER
                '62811',
                '62812',
                '62813',
                '62814',
                '62815',
                '62816',
                '62817',
                '62818',
                '62819',
                '62821',
                '62822',
                '62823',
                '62828',
                '62831',
                '62838',
                '62851',
                '62852',
                '62853',
                '62855',
                '62856',
                '62857',
                '62858',
                '62859',
                '62877',
                '62878',
                '62879',
                '62881',
                '62882',
                '62883',
                '62884',
                '62887',
                '62888',
                '62889',
                '62895',
                '62896',
                '62897',
                '62898',
                '62899',
            ]

        ],
        [
            'size' => 6,
            'data' => ['628681']
        ]
    ];

    public $countryCode          = '62';
    public $convertCountryFormat = false;

    /** variable for message whether phone number is not listed in any provider */
    public $notListed;
    public $wrongFormat;

    public function init()
    {
        parent::init();

        /** set default message for not listed provider */
        if ($this->notListed == null) {
            $this->notListed = \Yii::t('app', '{attribute} not listed in any registered provider.');
        }

        /** set default message for not listed provider */
        if ($this->wrongFormat == null) {
            $this->wrongFormat = \Yii::t('app', 'Invalid {attribute} format.');
        }
    }

    public function validateAttribute($model, $attribute)
    {
        $value = trim($model->$attribute, "+- \t\n\r\0\0B");
        if ($this->prefixes != []) {
            $hasProvider      = false;
            $countryFormatted = preg_replace('/^0?/', $this->countryCode, $value);
            foreach ($this->prefixes as $prefixes) {
                $attributePrefix = substr($countryFormatted, 0, $prefixes['size']);
                if (ArrayHelper::isIn($attributePrefix, $prefixes['data'])) {
                    $hasProvider = true;
                    break;
                }
            }

            /** set error while there's phoneNumber has no provider */
            if (!$hasProvider) {
                $this->addError($model, $attribute, $this->notListed);
            }

            if (!preg_match('/^[0-9]{'.$this->min.','.$this->max.'}+$/', $countryFormatted)) {
                $this->addError($model, $attribute, $this->wrongFormat);
            }
        }

        parent::validateAttribute($model, $attribute);
    }

    protected function validateValue($value)
    {
        if ($this->prefixes != []) {
            $hasProvider      = false;
            $countryFormatted = preg_replace('/^0?/', $this->countryCode, $value);
            foreach ($this->prefixes as $prefixes) {
                $attributePrefix = substr($countryFormatted, 0, $prefixes['size']);
                if (ArrayHelper::isIn($attributePrefix, $prefixes['data'])) {
                    $hasProvider = true;
                    break;
                }
            }

            /** set error while there's phoneNumber has no provider */
            if (!$hasProvider) {
                return [$this->notListed, []];
            }

            if (!preg_match('/^[0-9]{'.$this->min.','.$this->max.'}+$/', $countryFormatted)) {
                return [$this->wrongFormat, []];
            }
        }

        return parent::validateValue($value);
    }
}