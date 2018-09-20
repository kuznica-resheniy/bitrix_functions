<?
// ���������� ������� ������������ ������ ��� ��� ���������, ���� � ���� ����������� ������� UF_SHOW_IN_MENU (�������� ������� ���� "������" � ����� ��������� ��������)

AddEventHandler("iblock", "OnAfterIBlockSectionUpdate", "DeactivateSection");

function DeactivateSection(&$arFields)
{
	if($arFields["RESULT"])
	{
		$obSec = new CIBlockSection();
		$obEl = new CIBlockElement();
		CModule::IncludeModule("iblock");

		$arFilter = array('IBLOCK_ID' => $arFields['IBLOCK_ID'], 'ID' => $arFields["ID"]);
		$arSelect = array('ID', 'NAME', 'UF_SHOW_IN_MENU', 'ACTIVE');
		$category = CIBlockSection::GetList(array(), $arFilter, false, $arSelect)->GetNext();

		
		if ($category["UF_SHOW_IN_MENU"] != 0 && $category["ACTIVE"] == 'Y')
		{
			$boolResult = $obSec->Update($category['ID'], array('ACTIVE' => 'N'));

			//������ ���������� ��� ���������
			$res = CIBlockElement::GetList(array(), array('SECTION_ID' => $category['ID'], 'INCLUDE_SUBSECTIONS' => 'Y'));
			while($ob = $res->GetNextElement())
			{
				$fields = $ob->GetFields();
				$boolResult = $obEl->Update($fields['ID'], array('ACTIVE' => 'N'));
			}
		}
		
	}
	else
	{
		AddMessage2Log("������ ��������� ������ ".$arFields["ID"]." (".$arFields["RESULT_MESSAGE"].").");
	}
}