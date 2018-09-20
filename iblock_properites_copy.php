<?
// скрипт для копирования свойств одного инфоблока (или одних инфоблоков) в другой инфоблок

CModule::IncludeModule('iblock');

$iblockId = 21; // - инфоблок, куда нужно скопировать свойства
$sourceIblocks = array(1,19,12,14,15,13); // - инфоблоки, откуда нужно скопировать свойства

foreach($sourceIblocks as $id) {
	// --- делаем этот блок в цикле, чтобы на каждую итерацию была актуальная информация о наличии тех или иных свойств
	$rsIblockProperties = CIBlock::GetProperties($iblockId);
	$arProperties = array(); // - массив свойств инфоблока, в который нужно скопировать
	while($arProperty = $rsIblockProperties->Fetch()) {
		$arProperties[$arProperty['CODE']] = $arProperty;
	}
	// --- делаем этот блок в цикле, чтобы на каждую итерацию была актуальная информация о наличии тех или иных свойств

	$rsSourceIblockProperties = CIBlock::GetProperties($id);
	$arSourceProperties = array();
	while($arProperty = $rsSourceIblockProperties->Fetch()) {
		$arSourceProperties[$arProperty['CODE']] = $arProperty;
	}
	$arTmpProperties = array_diff_key($arSourceProperties,$arProperties);

	foreach($arTmpProperties as $arProperty) {
		switch($arProperty['PROPERTY_TYPE']) {
			case 'S':
			case 'N':
			case 'F':
			case 'E': {
				unset($arProperty['ID']);
				$arProperty['IBLOCK_ID'] = $iblockId;
				$ibp = new CIBlockProperty;
				$PropID = $ibp->Add($arProperty);
				if(!$PropID)
					echo 'error: '.$ibp->LAST_ERROR."\n";
				else
					echo 'added: '.$arProperty['CODE']."\n";
				break;
			}
			case 'L': {
				$arProperty['IBLOCK_ID'] = $iblockId;
				$rsEnums = CIBlockProperty::GetPropertyEnum($arProperty['ID']);
				while($arEnum = $rsEnums->Fetch()) {
					unset($arEnum['ID']);
					unset($arEnum['PROPERTY_ID']);
					$arProperty['VALUES'][] = $arEnum;
				}
				unset($arProperty['ID']);
				$ibp = new CIBlockProperty;
				$PropID = $ibp->Add($arProperty);
				if(!$PropID)
					echo 'error: '.$ibp->LAST_ERROR."\n";
				else
					echo 'added: '.$arProperty['CODE']."\n";
				break;
			}
		}
	}

	print_r($arTmpProperties);
}