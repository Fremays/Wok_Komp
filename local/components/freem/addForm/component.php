<?php
global $APPLICATION;
if(!CModule::IncludeModule("iblock"))
{
    ShowError(GetMessage("CC_BIEAF_IBLOCK_MODULE_NOT_INSTALLED"));
    return;
}
$arUserFields = array();
$arProductIDs = array();
//  Проставляем префиксы для гетлиста
$arNewProps = array();
$arProp = array();
foreach ($arParams['PROPERTY_CODE'] as $prop) {
    if (!empty($prop))
        $arNewProps[] = "PROPERTY_" . $prop;
}
$arSelect = array_merge($arParams['FIELD_CODE'], $arNewProps);
$arSelect[] = 'PROPERTY_' . $arParams['PROPERTY_CODE2'];
$arSelect = array_merge($arSelect, array_values($arParams['PRICE_CODE']));
$arSelect = array_filter($arSelect);

$arResult["PROPERTY_LIST_FULL"] = array(
    "NAME" => array(
        "PROPERTY_TYPE" => "S",
    ),

    "TAGS" => array(
        "PROPERTY_TYPE" => "S",
    ),

    "DATE_ACTIVE_FROM" => array(
        "PROPERTY_TYPE" => "S",
        "USER_TYPE" => "DateTime",
    ),

    "DATE_ACTIVE_TO" => array(
        "PROPERTY_TYPE" => "S",
        "USER_TYPE" => "DateTime",
    ),

    "IBLOCK_SECTION" => array(
        "PROPERTY_TYPE" => "L",
//        "ENUM" => $arResult["SECTION_LIST"],
    ),

    "PREVIEW_TEXT" => array(
        "PROPERTY_TYPE" => ($arParams["PREVIEW_TEXT_USE_HTML_EDITOR"]? "HTML": "T"),
    ),
    "PREVIEW_PICTURE" => array(
        "PROPERTY_TYPE" => "F",
        "FILE_TYPE" => "jpg, gif, bmp, png, jpeg",
    ),
    "DETAIL_TEXT" => array(
        "PROPERTY_TYPE" => ($arParams["DETAIL_TEXT_USE_HTML_EDITOR"]? "HTML": "T"),
    ),
    "DETAIL_PICTURE" => array(
        "PROPERTY_TYPE" => "F",
        "FILE_TYPE" => "jpg, gif, bmp, png, jpeg",
    ),
);

// Добавление нового элемента

if (empty($_GET['ID'])) {
    foreach ($arParams['FIELD_CODE'] as $key => $fieldCode) {
        if ($_POST[$fieldCode]) {
            $arAddFields[$fieldCode] = $_POST[$fieldCode];
        }
    }
    foreach ($arParams['PROPERTY_CODES'] as $elhehe => $propCode) {
        if ($_POST[$propCode]) {
            $arAddProps[$propCode] = $_POST[$propCode];
        }
    }

    $el = new CIBlockElement;
    $fields = array(
        //    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
        'IBLOCK_ID' => intval($arParams['IBLOCK_ID']),
        'ACTIVE' => "Y",
        $arAddFields,
        "PROPERTY_VALUES" => $arAddProps,
//        'CREATED_BY' => '1',
//        'MODIFIED_BY' => '1',
    );
    $fields = array_merge($fields, $arAddFields);
    if ($PRODUCT_ID = $el->Add($fields)) {
        echo 'Добавлен элемент, ID: ' . $PRODUCT_ID;
    } else {
        echo "Error[" . $PRODUCT_ID . "]: " . $el->LAST_ERROR . '<br />';
    }
} else {
    foreach ($arParams['FIELD_CODE_UPDATE'] as $keyhey => $fieldCodeSec){
        if($_POST[$fieldCodeSec]){
            $arAddFieldsUp[$fieldCodeSec] = $_POST[$fieldCodeSec];
        }
    }
    foreach ($arParams['PROPERTY_CODES_UPDATE'] as $elhehe => $propCodeSec){
        if($_POST[$propCodeSec]){
            $arAddPropsUp[$propCodeSec] = $_POST[$propCodeSec];
        }
    }

    $el = new CIBlockElement;
    $fields = array(
        //    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
        'IBLOCK_ID' => intval($arParams['IBLOCK_ID']),
        'ACTIVE' => "Y",
        "PROPERTY_VALUES" => $arAddPropsUp,
//        'CREATED_BY' => '1',
//        'MODIFIED_BY' => '1',
    );
$fields = array_merge($fields, $arAddFieldsUp);

    $PRODUCT_ID = $_GET['ID'];  // изменяем элемент с кодом (ID) 2
    $res = $el->Update($PRODUCT_ID, $fields);
    $res = CIBlockElement::GetList(
        array(),
        array(
            "ID" => intval($_GET['ID']),
            "IBLOCK_ID" => $arParams['IBLOCK_ID'],
        ),
        false,
        array(),
        $arSelect
    );
    while ($ar_fields = $res->GetNextElement()) {
        $ar_res = $ar_fields->GetFields();
//    $arResult["PROPERTIES"] = $ar_fields->GetProperties();
        $arResult['ITEM'] = $ar_res;
    }
}

$rsIBLockPropertyList = CIBlockProperty::GetList(array("sort"=>"asc", "name"=>"asc"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arParams["IBLOCK_ID"]));
while ($arProperty = $rsIBLockPropertyList->GetNext())
{
//    dump($arProperty);
    if ($arProperty["PROPERTY_TYPE"] == "L")
    {
    $rsPropertyEnum = CIBlockProperty::GetPropertyEnum($arProperty["ID"]);
    $arProperty["ENUM"] = array();
    while ($arPropertyEnum = $rsPropertyEnum->GetNext())
        {
            $arProperty["ENUM"][$arPropertyEnum["ID"]] = $arPropertyEnum;
        }
    }
    if (in_array($arProperty['CODE'], $arParams['PROPERTY_CODES']) ||
    in_array($arProperty['CODE'], $arParams['PROPERTY_CODES_UPDATE']))
    $arResult['PROPERTY_CODE'][$arProperty['CODE']] = $arProperty;
}

$res = CIBlockElement::GetList(
    array(),
    array(
        "ID" => intval($_GET['ID']),
        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"ACTIVE" => "Y",
    ),
    false,
    array(),
    $arSelect
);

while ($ar_fields = $res->GetNextElement()) {
    $ar_res = $ar_fields->GetFields();
    $arResult["PROPERTIES"] = $ar_fields->GetProperties();
    $arResult['ITEM'] = $ar_res;
    foreach ($arParams["PROPERTY_CODE"] as $key => $pid) {
        if(!empty($ar_res["PROPERTIES"][$pid]))
            $arResult['ITEMS'][$ar_res['ID']]["DISPLAY_PROPERTIES"][$pid] = $ar_res["PROPERTIES"][$pid];
    }
}

$this->IncludeComponentTemplate();
dump($arResult);
dump($arParams);
?>