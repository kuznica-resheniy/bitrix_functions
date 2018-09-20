<?
	// $order - объект созданного заказа
	$arUser = CUser::GetByID($order->getUserId());
	$basketList = '<table>
<tr>
	<th>Наименование</th>
	<th>Цена за единицу</th>
	<th>Количество</th>
	<th>Стоимость</th>
</tr>
#order_list#
</table>';
	$basket = $order->getBasket()->getBasketItems();
	$strBasket = '';
	foreach($basket as $basketItem) {
		$strBasket .= '<tr>
<td>'.$basketItem->getField('NAME').'</td>
<td>'.$basketItem->getPrice().'</td>
<td>'.$basketItem->getQuantity().'</td>
<td>'.$basketItem->getFinalPrice().'</td>
<tr>';
	}
	$basketList = str_replace('#order_list#',$strBasket,$basketList);
	$arEmailFields = array(
		'ORDER_ID' => $order->getId(),
		'ORDER_DATE' => $order->getDateInsert()->toString(),
		'ORDER_USER' => $arUser['NAME'].' '.$arUser['LAST_NAME'],
		'PRICE' => $order->getPrice(),
		'ORDER_LIST' => $basketList,
		'EMAIL' => $arUser['EMAIL']
	);
	\Bitrix\Main\Mail\Event::send(array( 
		"EVENT_NAME" => "SALE_NEW_ORDER", // - почтовое событие 
		"LID" => SITE_ID, 
		"C_FIELDS" => $arEmailFields 
	));