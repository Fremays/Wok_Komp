<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (!CModule::IncludeModule("iblock") ||
    !CModule::IncludeModule("catalog"))
    return false;
// Получение параметров инфоблоков
$arIBlockType = CIBlockParameters::GetIBlockTypes();
$arIBlockType2 = CIBlockParameters::GetIBlockTypes();

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
$arIBlock2 = array();
$rsIBlock2 = CIBlock::GetList(
    Array(
        "sort" => "asc",
    ),
    Array(
        "TYPE"      => $arCurrentValues["IBLOCK_TYPE2"],
        "ACTIVE"    => "Y",
    )
);

$arPrice = array();

//if (!empty($arCurrentValues["PRICE_CODE"]))
//{
$rsPrice = CCatalogGroup::GetList(
    false,
    array(),
    false,
    false,
    array()
);

while ($price = $rsPrice->Fetch())
{
    $arPrice['catalog_PRICE_'.$price['ID']] = $price['NAME_LANG'];
}
//}



// Получение Ийди инфоблока
while ($arr = $rsIBlock->Fetch()) {
    $arIBlock[$arr["ID"]] = "[" . $arr["ID"] . "] " . $arr["NAME"];
}
while ($arr = $rsIBlock2->Fetch()) {
    $arIBlock2[$arr["ID"]] = "[" . $arr["ID"] . "] " . $arr["NAME"];
}
// Сортировка и названия
$arSorts = array("ASC" => GetMessage("T_IBLOCK_DESC_ASC"), "DESC" => GetMessage("T_IBLOCK_DESC_DESC"));
$arSortFields = array(
    "ID" => GetMessage("T_IBLOCK_DESC_FID"),
);
// Получение пользовательских свойств
$arProperty_LNS = array();
$rsProp = CIBlockProperty::GetList(array(
    "sort" => "asc", "name" => "asc"),
    array(
        "ACTIVE" => "Y",
        "IBLOCK_ID" => (isset(
            $arCurrentValues["IBLOCK_ID"]) ? $arCurrentValues["IBLOCK_ID"] : $arCurrentValues["ID"]
        )
    )
);
while ($arr = $rsProp->Fetch()) {
    $arProperty[$arr["CODE"]] = "[" . $arr["CODE"] . "] " . $arr["NAME"];
    if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S", "E"))) {
        $arProperty_LNS[$arr["CODE"]] = "[" . $arr["CODE"] . "] " . $arr["NAME"];
    }
}
$arComponentParameters = Array(
    "GROUPS" => array(),
    "PARAMETERS" => Array(
        "VARIABLE_ALIASES" => Array(
            "SECTION_ID" => Array("NAME" => GetMessage("BN_P_SECTION_ID_DESC")),
            "ELEMENT_ID" => Array("NAME" => GetMessage("NEWS_ELEMENT_ID_DESC")),
        ),
            "SEF_MODE" => Array(
//            "news" => array(
//                "NAME" => GetMessage("T_IBLOCK_SEF_PAGE_NEWS"),
//                "DEFAULT" => "",
//                "VARIABLES" => array(),
//            ),
            "section" => array(
                "NAME" => GetMessage("T_IBLOCK_SEF_PAGE_NEWS_SECTION"),
                "DEFAULT" => "",
                "VARIABLES" => array("SECTION_ID"),
            ),
            "detail" => array(
                "NAME" => GetMessage("T_IBLOCK_SEF_PAGE_NEWS_DETAIL"),
                "DEFAULT" => "#ELEMENT_ID#/",
                "VARIABLES" => array("ELEMENT_ID", "SECTION_ID"),
            ),
//            "search" => array(
//                "NAME" => GetMessage("T_IBLOCK_SEF_PAGE_SEARCH"),
//                "DEFAULT" => "search/",
//                "VARIABLES" => array(),
//            ),
//            "rss" => array(
//                "NAME" => GetMessage("T_IBLOCK_SEF_PAGE_RSS"),
//                "DEFAULT" => "rss/",
//                "VARIABLES" => array(),
//            ),
//            "rss_section" => array(
//                "NAME" => GetMessage("T_IBLOCK_SEF_PAGE_RSS_SECTION"),
//                "DEFAULT" => "#SECTION_ID#/rss/",
//                "VARIABLES" => array("SECTION_ID"),
//            ),
        ),
        "IBLOCK_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("GOODIES_TYPE"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlockType,
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("GOODIES_ID"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlock,
            "REFRESH" => "Y",
            "CNT_ACTIVE" => "Y"
        ),
        "IBLOCK_TYPE2" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("GOODIES_TYPE2"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlockType2,
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID2" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("GOODIES_ID2"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $arIBlock2,
            "REFRESH" => "Y",
            "CNT_ACTIVE" => "Y"
        ),
        "ELEMENTS_COUNT" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("ELEMENTS_COUNT"),
            "TYPE" => "STRING",
            "ADDITIONAL_VALUES" => "Y",
            "REFRESH" => "Y"
        ),
        "SORT_BY" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("T_IBLOCK_DESC_IBORD"),
            "TYPE" => "LIST",
            "DEFAULT" => "ACTIVE_FROM",
            "VALUES" => $arSortFields,
            "ADDITIONAL_VALUES" => "Y",
        ),
        "SORT_ORDER" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("T_IBLOCK_DESC_IBBY"),
            "TYPE" => "LIST",
            "DEFAULT" => "DESC",
            "VALUES" => $arSorts,
            "ADDITIONAL_VALUES" => "Y",
        ),
        "FIELD_CODE" => CIBlockParameters::GetFieldCode(GetMessage("IBLOCK_FIELD"), "DATA_SOURCE"),
        "PROPERTY_CODE" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("T_IBLOCK_PROPERTY"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arProperty_LNS,
            "ADDITIONAL_VALUES" => "Y",
        ),
        "PROPERTY_CODE2" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("T_IBLOCK_PROPERTY2"),
            "TYPE" => "LIST",
            "MULTIPLE" => "N",
            "VALUES" => $arProperty_LNS,
        ),
//        "CATALOG" => array(
//            "PARENT" => "DATA_SOURCE",
//            "NAME" => GetMessage("T_IBLOCK_CATALOG"),
//            "TYPE" => "LIST",
//            "MULTIPLE" => "N",
//            "VALUES" => $arProperty_LNS2,
//        ),

        "CACHE_TIME" => array("DEFAULT" => "360000"),
        "AJAX_MODE" => array(),
        "PRICE_CODE" => array(
            "PARENT" => "PRICES",
            "NAME" => GetMessage("IBLOCK_PRICE_CODE"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arPrice,
        ),
    ),
);
?>