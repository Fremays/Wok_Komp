<?
// N. Для Action в параметрах добавить новое поле и подставлять вместо test.php значение оттуда
?>
<?if (empty($_GET['ID'])):?>
    <form action="test.php?>" method="post">
    <?foreach ($arParams['FIELD_CODE'] as $key => $w) { // Добавление?>
        <?switch ($arResult['PROPERTY_LIST_FULL'][$w]['PROPERTY_TYPE']):
            case "S":?>
                <label>
                    <?if($arResult["PROPERTY_LIST_FULL"][$w]["USER_TYPE"] == "DateTime"):?>
                        <?=GetMessage("IBLOCK_FIELD_".$w)?> <input type="text" name="<?=$w?>" value="<?=($arResult['ITEM'][$w])?>"> <br />
                        <?
                        $APPLICATION->IncludeComponent(
                            'bitrix:main.calendar',
                            '',
                            array(
                                'FORM_NAME' => 'iblock_add',
                                'INPUT_NAME' => $w,
                                'INPUT_VALUE' => $arResult['ITEM'][$w],
                            ),
                            null,
                            array('HIDE_ICONS' => 'Y')
                        );
                        ?>
                        <br /><?
                    else:?>
                        <?=GetMessage("IBLOCK_FIELD_".$w)?> <input type="text" name="<?=$w?>" value="<?=($arResult['ITEM'][$w])?>">
                    <?endif;?>
                    <br />
                </label>
                <?break;?>
            <?case "T":?>
                <label>
                    <?=GetMessage("IBLOCK_FIELD_".$w)?> <textarea name="<?=$w?>" rows="8" cols="40"><?=($arResult['ITEM'][$w])?></textarea> <br />
                </label>
                <?break;?>
            <?case "F":?>
                <!--                <label>-->
                <!--                    --><?//=GetMessage("IBLOCK_FIELD_".$w)?><!-- <input type="file" name="--><?//=$w?><!--"> <br />-->
                <!--                </label>-->
                <?break;?>
            <?case "L":?>
                <table border="0">
                    <td><?=GetMessage("IBLOCK_FIELD_".$w)?> :</td>
                    <td>[Нет  <input type="radio" name="<?=$e?>" value="">] </td>
                </table>
                <?break;?>
            <?endswitch;?>
<!--        <label>-->
<!--            --><?//=GetMessage("IBLOCK_FIELD_".$w)?><!-- <input type="text" name="--><?//=$w?><!--"> <br />-->
<!--        </label>-->
    <?}?>
<!--    --><?//foreach ($arParams['PROPERTY_CODES'] as $key => $e) { // Добавление?>
<!--        <label>-->
<!--            --><?//=$arResult['PROPERTY_CODE'][$e]['NAME']?><!-- <input type="text" name="--><?//=$e?><!--"> <br />-->
<!--        </label>-->
<!--    --><?//}?>
        <?foreach ($arParams['PROPERTY_CODES'] as $key => $e) { // Изменение?>
            <?switch ($arResult['PROPERTY_CODE'][$e]['PROPERTY_TYPE']):
                case "N":?>
                    <label>
                        <?=$arResult['PROPERTY_CODE'][$e]['NAME']?>  <input type="number" name="<?=$e?>" value="<?=htmlspecialcharsbx($arResult['ITEM']['PROPERTY_'.$e.'_VALUE'])?>"> <br />
                    </label>
                    <?break;?>
                <?case "S":?>
                    <label>
                        <?=$arResult['PROPERTY_CODE'][$e]['NAME']?>  <input type="text" name="<?=$e?>" value="<?=htmlspecialcharsbx($arResult['ITEM']['PROPERTY_'.$e.'_VALUE'])?>"> <br />
                    </label>
                    <?break;?>
                <?case "L":?>
                    <table border="0">
                        <td><?=$arResult['PROPERTY_CODE'][$e]['NAME']?> :</td>
                        <td>[Нет  <input type="radio" name="<?=$e?>" value="">] </td>
                        <?foreach ($arResult['PROPERTY_CODE'][$e]['ENUM'] as $key => $q){?>
                            <td>[<?=$q['VALUE']?>  <input type="radio" name="<?=$e?>" value="<?=$q['ID']?>">] </td>
                        <?}?>
                    </table>
                    <?break;?>
                <?endswitch;?>
        <?}?>
        <input type="submit" name="submit" value="Отправить" />
    </form>
<? else:?>
<form name="iblock_add" action="test.php?ID=<?=$_GET['ID']?>" method="post">
    <?foreach ($arParams['FIELD_CODE_UPDATE'] as $key => $w) { // Изменение?>
        <?switch ($arResult['PROPERTY_LIST_FULL'][$w]['PROPERTY_TYPE']):
            case "S":?>
                 <label>
                     <?if($arResult["PROPERTY_LIST_FULL"][$w]["USER_TYPE"] == "DateTime"):?>
                         <?=GetMessage("IBLOCK_FIELD_".$w)?> <input type="text" name="<?=$w?>" value="<?=($arResult['ITEM'][$w])?>"> <br />
                         <?
                         $APPLICATION->IncludeComponent(
                             'bitrix:main.calendar',
                             '',
                             array(
                                 'FORM_NAME' => 'iblock_add',
                                 'INPUT_NAME' => $w,
                                 'INPUT_VALUE' => $arResult['ITEM'][$w],
                             ),
                             null,
                             array('HIDE_ICONS' => 'Y')
                         );
                         ?>
                         <br /><?
                     else:?>
                         <?=GetMessage("IBLOCK_FIELD_".$w)?> <input type="text" name="<?=$w?>" value="<?=($arResult['ITEM'][$w])?>">
                     <?endif;?>
                     <br />
                 </label>
            <?break;?>
            <?case "T":?>
                <label>
                    <?=GetMessage("IBLOCK_FIELD_".$w)?> <textarea name="<?=$w?>" rows="8" cols="40"><?=($arResult['ITEM'][$w])?></textarea> <br />
                </label>
                <?break;?>
            <?case "F":?>
<!--                <label>-->
<!--                    --><?//=GetMessage("IBLOCK_FIELD_".$w)?><!-- <input type="file" name="--><?//=$w?><!--"> <br />-->
<!--                </label>-->
                <?break;?>
            <?case "L":?>
                <table border="0">
                    <td><?=GetMessage("IBLOCK_FIELD_".$w)?> :</td>
                        <td>[Нет  <input type="radio" name="<?=$e?>" value="">] </td>
                </table>
                <?break;?>
        <?endswitch;?>
    <?}?>
    <?foreach ($arParams['PROPERTY_CODES_UPDATE'] as $key => $e) { // Изменение?>
        <?switch ($arResult['PROPERTY_CODE'][$e]['PROPERTY_TYPE']):
            case "N":?>
                <label>
                    <?=$arResult['PROPERTY_CODE'][$e]['NAME']?>  <input type="number" name="<?=$e?>" value="<?=strip_tags(($arResult['PROPERTIES'][$e]['VALUE']))?>"> <br />
                </label>
                <?break;?>
            <?case "S":?>
                <label>
<!--                    --><?//=$arResult['PROPERTY_CODE'][$e]['NAME']?><!--  <textarea name="--><?//=$e?><!--" value="--><?//=strip_tags(($arResult['PROPERTIES'][$e]['VALUE']))?><!--"> </textarea> <br />-->
                    <?=$arResult['PROPERTY_CODE'][$e]['NAME']?>  <input type="text" name="<?=$e?>" value="<?=strip_tags(($arResult['PROPERTIES'][$e]['VALUE']))?>">  <br />
                </label>
                <?break;?>
            <?case "L":?>
            <?if ($e['MULTIPLE'] = 'N' && $e['LIST_TYPE'] = 'C'){?>
                  <table border="0">
                      <td><?=$arResult['PROPERTY_CODE'][$e]['NAME']?> :</td>
                      <td>[Нет  <input type="radio" name="<?=$e?>" value="">] </td>
                    <?foreach ($arResult['PROPERTY_CODE'][$e]['ENUM'] as $key => $q){?>
                        <?if ($arResult['PROPERTIES'][$e]['VALUE_ENUM_ID'] == $q['ID']){?>
                        <td>[<?=$q['VALUE']?>  <input type="radio" name="<?=$e?>" value="<?=$q['ID']?>" checked>] </td>
                        <?} else {?>
                        <td>[<?=$q['VALUE']?>  <input type="radio" name="<?=$e?>" value="<?=$q['ID']?>" >] </td>
                        <?}?>

                    <?}?>
                </table>
                <?} elseif ($e['MULTIPLE'] = 'Y' && $e['LIST_TYPE'] = 'C'){?>
                <table border="0">
                    <td><?=$arResult['PROPERTY_CODE'][$e]['NAME']?> :</td>
                    <?foreach ($arResult['PROPERTY_CODE'][$e]['ENUM'] as $key => $q){?>
                        <?if ($arResult['PROPERTIES'][$e]['VALUE_ENUM_ID'] == $q['ID']){?>
                            <td>[<?=$q['VALUE']?>  <input type="checkbox" name="<?=$e?>" value="<?=$q['ID']?>" checked>] </td>
                        <?} else {?>
                            <td>[<?=$q['VALUE']?>  <input type="checkbox" name="<?=$e?>" value="<?=$q['ID']?>" >] </td>
                        <?}?>

                    <?}?>
                </table>
                <?} elseif ($e['MULTIPLE'] = 'N' && $e['LIST_TYPE'] = 'L'){?>
                <table border="0">
                    <td><?=$arResult['PROPERTY_CODE'][$e]['NAME']?> :</td>
                    <?foreach ($arResult['PROPERTY_CODE'][$e]['ENUM'] as $key => $q){?>
                        <select>
                        <?if ($arResult['PROPERTIES'][$e]['VALUE_ENUM_ID'] == $q['ID']){?>
                            <option selected value="<?=$q['ID']?>"><?=$q['ID']?></option>
                        <?} else {?>
                            <option value="<?=$q['ID']?>"><?=$q['ID']?></option>
                        <?}?>
                        </select>
                    <?}?>
                </table>
                <?} else {?>
                <td><?=$arResult['PROPERTY_CODE'][$e]['NAME']?> :</td>
                <?foreach ($arResult['PROPERTY_CODE'][$e]['ENUM'] as $key => $q){?>
                    <select multiple>
                        <?if ($arResult['PROPERTIES'][$e]['VALUE_ENUM_ID'] == $q['ID']){?>
                            <option selected value="<?=$q['ID']?>"><?=$q['ID']?></option>
                        <?} else {?>
                            <option value="<?=$q['ID']?>"><?=$q['ID']?></option>
                        <?}?>
                    </select>
                <?}?>
                </table>
                <?}?>
                <?break;?>
            <?endswitch;?>
    <?}?>
    <input type="submit" name="submit" value="Отправить" />
</form>

<?endif; ?>