$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());

/** int $orderId номер заказа */
$basket = Sale\Order::load($orderId)->getBasket();
// или:
/** Sale\Basket $order объект заказа */
$basket = Sale\Basket::loadItemsForOrder($order);

/** int $productId ID товара */
/** int $quantity количество */
if ($item = $basket->getExistsItem('catalog', $productId)) {
    $item->setField('QUANTITY', $item->getQuantity() + $quantity);
}
else {
    $item = $basket->createItem('catalog', $productId);
    $item->setFields(array(
        'QUANTITY' => $quantity,
        'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
        'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
        'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
    ));
    /* 
    Если вы хотите добавить товар с произвольной ценой, нужно сделать так:
    $item->setFields(array(
        'QUANTITY' => $quantity,
        'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
        'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
        'PRICE' => $customPrice,
        'CUSTOM_PRICE' => 'Y',
   ));
   */
}
$basket->save();

