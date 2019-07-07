<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (!CModule::IncludeModule("iblock"))
    return false;
// Получение параметров инфоблоков
$arIBlockType = CIBlockParameters::GetIBlockTypes();
// Список инфоблоков
$arIBlock = array();
$rsIBlock = CIBlock::GetList(
    Array(
        "sort" => "asc",
    ),
    Array(
        "TYPE"      => $arCurrentValues["IBLOCK_TYPE"],
        "ACTIVE"    => "Y",
    )
);
// Получение Ийди инфоблока
while ($arr = $rsIBlock->Fetch()) {
    $arIBlock[$arr["ID"]] = "[" . $arr["ID"] . "] " . $arr["NAME"];
}
// Сортировка и названия
$arSorts = array("ASC" => GetMessage("T_IBLOCK_DESC_ASC"), "DESC" => GetMessage("T_IBLOCK_DESC_DESC"));
$arSortFields = array(
    "ID" => GetMessage("T_IBLOCK_DESC_FID"),
);

$arField_LNSF = array(
    "NAME" => GetMessage("IBLOCK_ADD_NAME"),
    "TAGS" => GetMessage("IBLOCK_ADD_TAGS"),
    "DATE_ACTIVE_FROM" => GetMessage("IBLOCK_ADD_ACTIVE_FROM"),
    "DATE_ACTIVE_TO" => GetMessage("IBLOCK_ADD_ACTIVE_TO"),
    "IBLOCK_SECTION" => GetMessage("IBLOCK_ADD_IBLOCK_SECTION"),
    "PREVIEW_TEXT" => GetMessage("IBLOCK_ADD_PREVIEW_TEXT"),
    "PREVIEW_PICTURE" => GetMessage("IBLOCK_ADD_PREVIEW_PICTURE"),
    "DETAIL_TEXT" => GetMessage("IBLOCK_ADD_DETAIL_TEXT"),
    "DETAIL_PICTURE" => GetMessage("IBLOCK_ADD_DETAIL_PICTURE"),
);
$arVirtualProperties = $arProperty_LNSF;

$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID"]));
while ($arr=$rsProp->Fetch())
{
    $arProperty[$arr["ID"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
    if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S", "F")))
    {
        $arProperty_LNSF[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
    }
}
dump($arProperty);

$arComponentParameters = Array(
    "GROUPS" => array(
        "PARAMS" => array(
            "NAME" => GetMessage("IBLOCK_PARAMS"),
            "SORT" => "200"
        ),
        "FIELDS" => array(
            "NAME" => GetMessage("IBLOCK_FIELDS"),
            "SORT" => "300",
        ),
        "TITLES" => array(
            "NAME" => GetMessage("IBLOCK_TITLES"),
            "SORT" => "1000",
        ),
    ),
    "PARAMETERS" => Array(
        "IBLOCK_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("GOODIES_TYPE"),
            "TYPE" => "LIST",
            "VALUES" => $arIBlockType,
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("GOODIES_ID"),
            "TYPE" => "LIST",
            "VALUES" => $arIBlock,
            "REFRESH" => "Y",
            "CNT_ACTIVE" => "Y"
        ),
        "FIELD_CODE" => array(
            "PARENT" => "FIELDS",
            "NAME" => GetMessage("IBLOCK_FIELD"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arField_LNSF,
        ),
        "PROPERTY_CODES" => array(
            "PARENT" => "FIELDS",
            "NAME" => GetMessage("IBLOCK_PROPERTY"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arProperty_LNSF,
        ),
        "FIELD_CODE_UPDATE" => array(
            "PARENT" => "FIELDS",
            "NAME" => GetMessage("IBLOCK_FIELD_UPDATE"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "ADDITIONAL_VALUES" => "N",
            "VALUES" => $arField_LNSF,
        ),
        "PROPERTY_CODES_UPDATE" => array(
            "PARENT" => "FIELDS",
            "NAME" => GetMessage("IBLOCK_PROPERTY_UPDATE"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "ADDITIONAL_VALUES" => "N",
            "VALUES" => $arProperty_LNSF,
        ),
    )
);
//foreach ($arVirtualProperties as $key => $title)
//{
//    $arComponentParameters["PARAMETERS"]["CUSTOM_TITLE_".$key] = array(
//        "PARENT" => "TITLES",
//        "NAME" => $title,
//        "TYPE" => "STRING",
//    );
//}
?>