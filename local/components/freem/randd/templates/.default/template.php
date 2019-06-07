<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->createFrame()->begin("Загрузка");
foreach ($arResult['ITEMS'] as $key => $good){?>
<p>
    <?foreach ($good['FIELDS'] as $key2 => $value) {
	        echo GetMessage("IBLOCK_FIELD_".$key2)?>:&nbsp;<?=$value;?>
        <br />
        <?}?>
    <?foreach ($good['PROPERTIES']  as $key2 => $wut) {
        if (!empty($wut['VALUE'])){
        	echo $wut["NAME"] . ' - ' . $wut['VALUE']?><br /><?;
        }
    }?>
    <?if (!empty($good['CATALOG_GROUP_NAME_1'])){?>
        <?echo $good['CATALOG_GROUP_NAME_1']?> - <?echo $good['CATALOG_PRICE_1']?> <?echo $good['CATALOG_CURRENCY_1']?><br />
    <?}?>
    <?if (!empty($good['CATALOG_GROUP_NAME_2'])){?>
        <?echo $good['CATALOG_GROUP_NAME_2']?> - <?echo $good['CATALOG_PRICE_2']?> <?echo $good['CATALOG_CURRENCY_2']?><br />
    <?}?>
    <?echo $good['LINK_DELIVERY']['NAME']?><br />

</p>
    <a href="<?=$good['DETAIL_PAGE_URL']?>">Детальная страница</a>
<?}?>




