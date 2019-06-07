<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
global $USER;
// Композит
$this->setFrameMode(true);

    // TODO: fix types of vars

// Заданы значения сортировки, если не задано в параметрах
$arParams["SORT_BY"] = trim($arParams["SORT_BY"]);
//if (strlen($arParams["SORT_BY"]) <= 0)
if (empty($arParams["SORT_BY"]))
    $arParams["SORT_BY"] = "ACTIVE_FROM";
//if (strlen($arParams["SORT_ORDER"]) <= 0)
if (empty($arParams["SORT_ORDER"]))
    $arParams["SORT_ORDER "] = "DESC";
if ($arParams['ELEMENTS_COUNT'] <= 0)
    $arParams['ELEMENTS_COUNT'] = 5;
foreach($arParams['FIELD_CODE'] as $k => $v){
    $arParams['FIELD_CODE'][$k] = htmlspecialchars($v);
}
foreach($arParams['PROPERTY_CODE'] as $k => $v){
    $arParams['PROPERTY_CODE'][$k] = htmlspecialchars($v);
}
$arParams['SEF_FOLDER'] = htmlspecialchars($arParams['SEF_FOLDER']);
$arParams['SEF_URL_TEMPLATES_section'] = htmlspecialchars($arParams['SEF_URL_TEMPLATES_section']);
$arParams['SEF_URL_TEMPLATES_detail'] = htmlspecialchars($arParams['SEF_URL_TEMPLATES_detail']);
// Проверка на заполненость полей инфоблока и актуальность кеша

if (empty($arParams['IBLOCK_ID']) ||
    empty($arParams['IBLOCK_TYPE']) ||
    empty($arParams['PROPERTY_CODE2']) ||
    !$this->StartResultCache(false, ($arParams["CACHE_GROUPS"] === "N" ? false : $USER->GetGroups()))) {
    return false;
}
// Остановка кеширования
if (!CModule::IncludeModule("iblock") ||
    !CModule::IncludeModule("catalog")) {
    $this->AbortResultCache();
    ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
    return;
}

// Отчистка от пустых значений
$arParams['PROPERTY_CODE'] = array_filter($arParams['PROPERTY_CODE']);
// Чпу
$arComponentVariables = array(
    "ELEMENT_ID",
    "ELEMENT_CODE",
);

$arDefaultUrlTemplates404 = array(
    "detail" => "#ELEMENT_ID#/",
    "section" => "",
);
if ($arParams["SEF_MODE"] == "Y") {
    $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);

    $arVariables = array();

    $componentPage = CComponentEngine::ParseComponentPath(
        $arParams["SEF_FOLDER"],
        $arUrlTemplates,
        $arVariables
    );

    if (!$componentPage) {
        $componentPage = "list";
    }

    $arResult = array(
        "FOLDER" => $arParams["SEF_FOLDER"],
        "URL_TEMPLATES" => $arUrlTemplates,
        "VARIABLES" => $arVariables
    );

} else {
    $arVariables = array();

    $arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases, $arParams["VARIABLE_ALIASES"]);
    CComponentEngine::InitComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);

    $componentPage = "";

    // TODO: think about it
    //    if (isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0)
    //        $componentPage = "";
    //    elseif (isset($arVariables["ELEMENT_CODE"]) && strlen($arVariables["ELEMENT_CODE"]) > 0)
    //        $componentPage = "";
    //    elseif (isset($arVariables[""])) // TODO: remove
    //        $componentPage = "";
    //    else
        $componentPage = "";

    $arResult = array(
        "FOLDER" => "",
        "URL_TEMPLATES" => Array(
//            "list" => htmlspecialcharsbx($APPLICATION->GetCurPage()), // TODO: Check this
            "detail" => htmlspecialcharsbx($APPLICATION->GetCurPage() . "?" . $arVariableAliases["ELEMENT_ID"] . "=#ELEMENT_ID#"),
        ),
        "VARIABLES" => $arVariables,
    );
}
// Получение имен в /*польз */св-вах инфоблока
// TODO: fix this shit
$prop = CIBlock::GetProperties($arParams['IBLOCK_ID'], array(), false); //Получение массива всех пользовательских свойств инфоблока
$arUserFields = array();
$arProductIDs = array();
while ($res_arr = $prop->Fetch()) {
    if (in_array($res_arr['CODE'], $arParams["PROPERTY_CODE"])) {
        $arUserFields[$res_arr['CODE']] = array(
            "NAME" => $res_arr["NAME"], // Записываем его название в отдельным массив с КОДОМ поля в виде ключа
        );
    }
}


//  Проставляем префиксы для гетлиста
$arNewProps = array();
foreach ($arParams['PROPERTY_CODE'] as $prop) {
    if (!empty($prop))
        $arNewProps[] = "PROPERTY_" . $prop;
}

$arSelect = array_merge($arParams['FIELD_CODE'], $arNewProps);
$arSelect[] = 'PROPERTY_' . $arParams['PROPERTY_CODE2'];
//$arrSortAlown = array('catalog_PRICE_1'); //TODO: add price code into component props
$arSelect = array_merge($arSelect, array_values($arParams['PRICE_CODE']));
$arSelect = array_filter($arSelect);
//  Возвращение списков элементов
$res = CIBlockElement::GetList(
    array(
        $arParams["SORT_BY"] => $arParams["SORT_ORDER"],
    ),
    array(
        "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
        "ACTIVE" => "Y",
    ),
    false,
    array("nTopCount" => $arParams['ELEMENTS_COUNT']),
    $arSelect
);

while ($ar_fields = $res->GetNext()) {
    // Записываем в общий массив с группировкой по id элемента
    $arResult['ITEMS'][$ar_fields['ID']] = $ar_fields;
    // Запись названий и значений пользовательских полей(только тех, которые указаны в настройках компонента), сгруппированных по id элемента
    foreach ($arParams['PROPERTY_CODE'] as $key => $propCode) {
        $propVal = $ar_fields["PROPERTY_" . $propCode . "_VALUE"];
        $arResult['ITEMS'][$ar_fields['ID']]['PROPERTIES'][$key] = array(
            "NAME" => $arUserFields[$propCode]['NAME'],
            "VALUE" => $propVal,
        );
    }
    // Запись в отдельный подмассив только тех стандартных полей, которые указаны в настройках компонента
//    foreach ($arParams{'FIELD_CODE'} as $key => $fieldCode){
//        if ($ar_fields[$fieldCode])
//            $arResult['ITEMS'][$ar_fields['ID']]['FIELDS'][$key] = $ar_fields[$fieldCode];
//    }
        foreach ($ar_fields as $key => $value) {
        if (in_array($key, $arParams['FIELD_CODE']) && $key != 'DETAIL_PAGE_URL')
            $arResult['ITEMS'][$ar_fields['ID']]['FIELDS'][$key] = $value;
    }
    //  Поправить
    $detailUrl = $arResult['URL_TEMPLATES']['detail'];
    $detailUrl = str_replace('#ELEMENT_ID#', $ar_fields['ID'], $detailUrl);
    $detailUrl = str_replace('#ID#', $ar_fields['ID'], $detailUrl);
    $arResult['ITEMS'][$ar_fields['ID']]['DETAIL_PAGE_URL'] = $arResult['FOLDER'] . $detailUrl;
    // Массив ID запесей 2 инфоблока
    $arProductIDs[$ar_fields['PROPERTY_' . $arParams['PROPERTY_CODE2'] . '_VALUE']] = $ar_fields['PROPERTY_' . $arParams['PROPERTY_CODE2'] . '_VALUE'];
}

if (count($arProductIDs) > 0) {
    //  Получение списка связанных элементов
    $res2 = CIBlockElement::GetList(
        array(
            $arParams["SORT_BY"] => $arParams["SORT_ORDER"],
        ),
        array(
            "ID" => $arProductIDs,
            "IBLOCK_ID" => $arParams['IBLOCK_ID2'],
            "ACTIVE" => "Y",
        ),
        false,
        false,
        array(
            "NAME"
        )
    );

    while ($ar_fields = $res2->Fetch()) {
        foreach ($arResult['ITEMS'] as $servID => $arServ) {
            if ($ar_fields['ID'] == $arServ['PROPERTY_DELIVERY_VALUE'])
                $arResult['ITEMS'][$servID]['LINK_DELIVERY'] = $ar_fields;
        }
    }
}
if (!empty($arParams["PRICE_CODE"]))
{
    $rsPrice = CCatalogGroup::GetList(
        false,
        array('=NAME' => $arParams["PRICE_CODE"]),
        false,
        false,
        array()
    );

    while ($arPrice = $rsPrice->Fetch())
    {
            $arResult["arrPrice"][$arPrice["NAME"]] = array(
                "ID" => $arPrice["ID"],
                "TITLE" => ($arPrice["NAME"])
            );
    }
}
// Если найдены записи, то подключаем шаблон компонента. Иначе  - остановка кеширования
if (count($arResult['ITEMS']) > 0) {
    $this->SetResultCacheKeys(array());
    $this->IncludeComponentTemplate();
} else {
    $this->AbortResultCache();
}
?>