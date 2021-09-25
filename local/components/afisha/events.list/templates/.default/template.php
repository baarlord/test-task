<?php
/**
 * @author Leonid Eremin <leosard@yandex.ru>
 */

defined('B_PROLOG_INCLUDED') || die;


use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\Extension;


/** @var array $arResult */
Extension::load('ui.bootstrap4');
?>
    <h1><?= Loc::getMessage('TITLE', array('#DATE#' => $arResult['CURRENT_DATE'])); ?></h1>
<?php
if (empty($arResult['EVENTS'])): ?>
    <p><?= Loc::getMessage('EMPTY_RESULT') ?></p>
<?php
else: ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th><?= Loc::getMessage('EVENT_NAME') ?></th>
                <th><?= Loc::getMessage('CITY_NAME') ?></th>
                <th><?= Loc::getMessage('MEMBERS') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($arResult['EVENTS'] as $event): ?>
                <tr>
                    <td><?= $event['EVENT_NAME'] ?></td>
                    <td><?= $event['CITY_NAME'] ?></td>
                    <td><?= implode(', ', $event['MEMBERS']) ?></td>
                </tr>
            <?php
            endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
endif; ?>