<?php
$json_contacts = file_get_contents('https://b24-jr3k5i.bitrix24.ru/rest/1/m8plllckhgjsn4w0/crm.contact.list');
$json_deal = file_get_contents('https://b24-jr3k5i.bitrix24.ru/rest/1/m8plllckhgjsn4w0/crm.deal.list');
$contacts = json_decode($json_contacts, true)['result'];
$deals = json_decode($json_deal, true)['result'];
date_default_timezone_set('UTC');
$today = new DateTime('now', new DateTimeZone('Asia/Barnaul'));
echo'start program '.$today->format('d.m.Y H:i').'. Время Томское.'.PHP_EOL;
$output = [];
echo('[Количество сделок] => '.count($deals).PHP_EOL);
echo('[Количество контактов] => '.count($contacts).PHP_EOL);
$count_with_comments = 0;
$count_deals_non_contact = 0;
$category_id = [];
$count_deals = [];
foreach($contacts as $item) {
  if($item['COMMENTS']) {$count_with_comments++;}
}
foreach($deals as $item) {
  if(!$item['CONTACT_ID']) {$count_deals_non_contact++;}
  if(!in_array($item['CATEGORY_ID'], $category_id)) {
    $category_id[] = $item['CATEGORY_ID'];
  } 
}
foreach($category_id as $item) {
  $count_deal = 0;
  foreach($deals as $i){
    if($i['CATEGORY_ID'] ===$item) {$count_deal++;}
  }
  $count_deals[] = $count_deal;
}
$category_deal = array_combine($category_id, $count_deals);
echo('[count_with_comments]=> '.$count_with_comments.PHP_EOL);
echo('[count_deals_non_contact]=> '.$count_deals_non_contact.PHP_EOL);
foreach($count_deals as $key => $value) {
  echo('[count_'.$key.'_hopper] => '.$value.PHP_EOL);
}
echo <<< END
  [points_sum] => undefined'
  Пояснения:
    Всего одна сделка без контактов - либо наладка, либо ошибкa.
    Количество баллов - неопределено, поскольку найти поле "Баллы"
    мне не удалось. Прсмотрел все, на мой взгляд таблицы.
    Смотрел здесь:
      https://dev.1c-bitrix.ru/api_d7/bitrix/crm/dynamic/lib/itemcategory.php
      https://dev.1c-bitrix.ru/api_d7/bitrix/crm/dynamic/lib/typetable.php
      https://dev.1c-bitrix.ru/api_d7/bitrix/crm/dynamic/lib/prototypeitem.php
      https://dev.1c-bitrix.ru/rest_help/crm/fields.php
  END;
