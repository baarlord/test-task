<?php
/**
 * @author Leonid Eremin <leosard@yandex.ru>
 */

defined('B_PROLOG_INCLUDED') || die;


use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;


/** @var array $arCurrentValues */
Loader::requireModule('iblock');
$cities = CIBlockElement::GetList(
        array(),
        array('IBLOCK_ID' => $arCurrentValues['CITIES_IBLOCK_ID']),
        false,
        false,
        array('ID', 'IBLOCK_ID', 'NAME')
);
$cityIDList = array(0 => Loc::getMessage('ALL_CITIES'));
while ($city = $cities->Fetch()) {
    $cityIDList[$city['ID']] = $city['NAME'];
}

$arComponentParameters = array(
        'PARAMETERS' => array(
                'CITIES_IBLOCK_ID' => array(
                        'NAME' => Loc::getMessage('CITIES_IBLOCK_ID'),
                        'REFRESH' => 'Y',
                ),
                'EVENTS_IBLOCK_ID' => array(
                        'NAME' => Loc::getMessage('EVENTS_IBLOCK_ID'),
                ),
                'MEMBERS_IBLOCK_ID' => array(
                        'NAME' => Loc::getMessage('MEMBERS_IBLOCK_ID'),
                ),
                'CITY_ID' => array(
                        'NAME' => Loc::getMessage('CITY_ID'),
                        'TYPE' => 'LIST',
                        'VALUES' => $cityIDList,
                        'DEFAULT' => 0,
                ),
                'CACHE_TIME' => array('DEFAULT' => 86400),
        ),
);
