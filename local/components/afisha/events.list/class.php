<?php
/**
 * @author Leonid Eremin <leosard@yandex.ru>
 */


use Bitrix\Main\Error as ErrorAlias;
use Bitrix\Main\ErrorableImplementation;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\Date;


class EventsListComponent extends CBitrixComponent
{
    use ErrorableImplementation;


    function __construct($component = null)
    {
        parent::__construct($component);
        $this->errorCollection = new ErrorCollection();
    }

    function onPrepareComponentParams($arParams): array
    {
        $arParams = parent::onPrepareComponentParams($arParams);
        $arParams['CITIES_IBLOCK_ID'] = (int)$arParams['CITIES_IBLOCK_ID'];
        $arParams['EVENTS_IBLOCK_ID'] = (int)$arParams['EVENTS_IBLOCK_ID'];
        $arParams['MEMBERS_IBLOCK_ID'] = (int)$arParams['MEMBERS_IBLOCK_ID'];
        $arParams['CITY_ID'] = (int)$arParams['CITY_ID'];
        return $arParams;
    }

    protected function checkRequiredParameters(): bool
    {
        if (empty($this->arParams['EVENTS_IBLOCK_ID'])) {
            $this->errorCollection[] = new ErrorAlias(Loc::getMessage('EVENTS_IBLOCK_ID_MUST_BE_SPECIFIED'));
            return false;
        }
        return true;
    }

    /**
     * @throws LoaderException
     */
    function executeComponent(): void
    {
        if ($this->startResultCache(
                false,
                array((new Date)->toString()),
                'afisha/events/list'
        )
        ) {
            if (!$this->includeModules() || !$this->checkRequiredParameters()) {
                $this->abortResultCache();
                $this->showErrors();
                return;
            }
            $this->arResult['CURRENT_DATE'] = (new Date)->toString();
            $this->arResult['EVENTS'] = $this->getActiveEvents();
            $this->setResultCacheKeys(array('CURRENT_DATE', 'EVENTS'));
            $this->endResultCache();
        }
        $this->includeComponentTemplate();
    }

    protected function getActiveEvents(): array
    {
        $result = array();
        $filter = array(
                'IBLOCK_ID' => $this->arParams['EVENTS_IBLOCK_ID'],
                'ACTIVE' => 'Y',
                '<=DATE_ACTIVE_FROM' => new Date(),
                '>=DATE_ACTIVE_TO' => new Date(),
        );
        if (!empty($this->arParams['CITY_ID'])) {
            $filter['PROPERTY_CITY.ID'] = $this->arParams['CITY_ID'];
        }
        $select = array(
                'ID',
                'IBLOCK_ID',
                'NAME',
                'MEMBER_NAME' => 'PROPERTY_MEMBERS.PROPERTY_NAME',
                'MEMBER_SECOND_NAME' => 'PROPERTY_MEMBERS.PROPERTY_SECOND_NAME',
                'CITY_ID' => 'PROPERTY_CITY.ID',
                'CITY_NAME' => 'PROPERTY_CITY.NAME',
        );
        $events = CIBlockElement::GetList(
                array(),
                $filter,
                false,
                false,
                $select,
        );
        $membersLists = array();
        while ($event = $events->Fetch()) {
            $membersLists[$event['ID']][] = $event['PROPERTY_MEMBERS_PROPERTY_NAME_VALUE'] . ' ' .
                    $event['PROPERTY_MEMBERS_PROPERTY_SECOND_NAME_VALUE'];
            $result[$event['ID']] = array(
                    'EVENT_NAME' => $event['NAME'],
                    'CITY_NAME' => $event['PROPERTY_CITY_NAME'],
                    'MEMBERS' => array(),
            );
        }
        foreach ($membersLists as $eventID => $members) {
            $result[$eventID]['MEMBERS'] = $members;
        }
        return $result;
    }

    /**
     * @throws LoaderException
     */
    protected function includeModules(): bool
    {
        if (!Loader::includeModule('iblock')) {
            $this->errorCollection[] = new ErrorAlias(Loc::getMessage('MODULE_IBLOCK_NOT_INSTALLED'));
            return false;
        }
        return true;
    }

    function showErrors(): void
    {
        foreach ($this->getErrors() as $error) {
            ShowError($error);
        }
    }
}
