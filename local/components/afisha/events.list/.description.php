<?php
/**
 * @author Leonid Eremin <leosard@yandex.ru>
 */

defined('B_PROLOG_INCLUDED') || die;


use Bitrix\Main\Localization\Loc;


$arComponentDescription = array(
        'NAME' => Loc::getMessage('COMPONENT_NAME'),
        'DESCRIPTION' => Loc::getMessage('COMPONENT_DESC'),
        'PATH' => array(
                'ID' => 'afisha',
                'NAME' => Loc::getMessage('MODULE_NAME'),
                'CHILD' => array(
                        'ID' => 'afisha-events',
                        'NAME' => Loc::getMessage('SECTION_NAME'),
                ),
        ),
);