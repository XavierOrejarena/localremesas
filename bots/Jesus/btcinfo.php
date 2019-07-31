#!/usr/bin/env php
<?php
define('TOKEN',"542182830:AAEnF11eBwR5lXeRMg2maZcBgPRZajVkV5A");
$users = ['149273661','309646792','319720914'];
setlocale(LC_MONETARY, 'en_US.UTF-8');

define('URL_BUY_PERU', "https://localbitcoins.com/instant-bitcoins/?action=buy&country_code=PE&amount=&currency=PEN&place_country=PE&online_provider=SPECIFIC_BANK&find-offers=Buscar");
define('URL_SELL_PERU', "https://localbitcoins.com/instant-bitcoins/?action=sell&country_code=PE&amount=&currency=PEN&place_country=PE&online_provider=SPECIFIC_BANK&find-offers=Buscar");
define('URL_BUY_CHILE', "https://localbitcoins.com/instant-bitcoins/?action=buy&country_code=CL&amount=&currency=CLP&place_country=CL&online_provider=NATIONAL_BANK&find-offers=Buscar");
define('URL_SELL_CHILE', "https://localbitcoins.com/instant-bitcoins/?action=sell&country_code=CL&amount=&currency=CLP&place_country=CL&online_provider=NATIONAL_BANK&find-offers=Buscar");
define('URL_BUY_VENEZUELA', "https://localbitcoins.com/instant-bitcoins/?action=buy&country_code=VE&amount=&currency=VES&place_country=VE&online_provider=SPECIFIC_BANK&find-offers=Buscar");
define('URL_SELL_VENEZUELA', "https://localbitcoins.com/instant-bitcoins/?action=sell&country_code=VE&amount=&currency=VES&place_country=VE&online_provider=SPECIFIC_BANK&find-offers=Buscar");

function getBTCPeru($verifyDiff = false) {
    $solValue = getSolValue();
    $arrayBuyPeru = getList(URL_BUY_PERU, $solValue, "S/.");
    $arraySellPeru = getList(URL_SELL_PERU, $solValue, "S/.");
    $diff = json_decode(file_get_contents('diff.json'));
  	$sDiff = abs(array_pop($arrayBuyPeru) - array_pop($arraySellPeru));

    $str = "*Valor del Bitcoin en Perú*\n*Compra*:\n";
    foreach(array_reverse($arrayBuyPeru) as $k=>$v) {
        $str .= "- $v\n";
    }
    $str .= "*Venta*:\n";
    foreach($arraySellPeru as $k=>$v) {
        $str .= "- $v\n";
    }
    $str .= "*Diferencia*: ".money_format('%.2n', $sDiff)."\n";
    $str .= "*BTC*: ".money_format('%.2n', getBTCValue())."\n";
    $str .= "*Dolar*: ".str_replace("$", "S/.", money_format('%.2n', $solValue))."\n";

    if ($verifyDiff == true && $diff->pe > $sDiff) {
        return false;
    } else {
        return $str;
    }
}

function getBTCChile($verifyDiff = false) {
    $pesoValue = getPesoValue();
    $arrayBuyChile = getList(URL_BUY_CHILE, $pesoValue);
    $arraySellChile = getList(URL_SELL_CHILE, $pesoValue);
    $diff = json_decode(file_get_contents('diff.json'));
    $sDiff = abs(array_pop($arrayBuyChile) - array_pop($arraySellChile));
    
    $str = "*Valor del Bitcoin en Chile*\n*Compra*:\n";
    foreach(array_reverse($arrayBuyChile) as $k=>$v) {
        $str .= "- $v\n";
    }
    $str .= "*Venta*:\n";
    foreach($arraySellChile as $k=>$v) {
        $str .= "- $v\n";
    }
    $str .= "*Diferencia*: ".money_format('%.2n', $sDiff)."\n";
    $str .= "*BTC*: ".money_format('%.2n', getBTCValue())."\n";
    $str .= "*Dolar*: ".money_format('%.2n', $pesoValue)."\n";

    if ($verifyDiff == true && $diff->cl > $sDiff) {
        return false;
    } else {
        return $str;
    }
}

function getBTCVenezuela($verifyDiff = false) {
    $btcValue = getBTCValue();
    $arrayBuyVenezuela = getList(URL_BUY_VENEZUELA, $btcValue, "BsS. ");
    $arraySellVenezuela = getList(URL_SELL_VENEZUELA, $btcValue, "BsS. ");
    $diff = json_decode(file_get_contents('diff.json'));
    $sDiff = abs(array_pop($arrayBuyVenezuela) - array_pop($arraySellVenezuela));

    $str = "*Valor del Bitcoin en Venezuela*\n*Compra*:\n";
    foreach(array_reverse($arrayBuyVenezuela) as $k=>$v) {
        $str .= "- $v\n";
    }
    $str .= "*Venta*:\n";
    foreach($arraySellVenezuela as $k=>$v) {
        $str .= "- $v\n";
    }
    $str .= "*Diferencia*: ".money_format('%.2n', $sDiff)."\n";
    $str = str_replace("$", "BsS. ", $str);
    $str .= "*BTC*: ".money_format('%.2n', getBTCValue())."\n";

    if ($verifyDiff == true && $diff->ve > $sDiff) {
        return false;
    } else {
        return $str;
    }
}

function getList($url, $currencyValue, $originalSymbol = false) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0');
    curl_setopt($ch, CURLOPT_FRESH_CONNECT,TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,50);
    curl_setopt($ch, CURLOPT_TIMEOUT,300);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $str = curl_exec($ch);
    curl_close ($ch);
    
    $re = '/column-price">\n[^\n]*\n[ ]*([^ ]*)/m';
    preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
    
    $result = [];
    $i = 0;
    
    foreach($matches as $k=>$v) {
        $result[] = money_format('%.2n', (str_replace(",", "", $v[1]) / $currencyValue)) . ($originalSymbol != false ? " ($originalSymbol{$v[1]})" : '');
        if (++$i == 10) break;
        
    }
    
    $result[] = (str_replace(",", "", $matches[0][1]) / $currencyValue);
    return $result;
}

function getSolValue() {
    $url = "https://kambista.herokuapp.com/v1/exchange-rates";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0');
    curl_setopt($ch, CURLOPT_FRESH_CONNECT,TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,50);
    curl_setopt($ch, CURLOPT_TIMEOUT,300);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $str = curl_exec($ch);
    curl_close ($ch);

    $obj = json_decode($str);
    return $obj->ask_exchange_rate;
}

function getPesoValue() {
    $url = "https://www.valor-dolar.cl/currencies_rates.json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0');
    curl_setopt($ch, CURLOPT_FRESH_CONNECT,TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,50);
    curl_setopt($ch, CURLOPT_TIMEOUT,300);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $str = curl_exec($ch);
    curl_close ($ch);

    $obj = json_decode($str);
    
    foreach($obj->currencies as $k=>$v){
        if($v->code == 'CLP') return $v->rate;
    }
    
    return 0;
}

function getBTCValue() {
    $BINANCE_BTCUSDT = file_get_contents("https://www.bitmex.com/api/v1/trade/bucketed?binSize=1m&partial=true&count=100&reverse=true");
    $BINANCE_BTCUSDT = json_decode($BINANCE_BTCUSDT, true);

    foreach ($BINANCE_BTCUSDT as $coin) {
        if ($coin['symbol'] == 'XBTUSD') {
            return round($coin['open'],2);
            break;
        }
    }
    
    return 0;
}

function sendMethod($method, $params = array()){
    $url   = "https://api.telegram.org/bot".TOKEN."/$method";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:multipart/form-data"));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    return curl_exec($ch);
}






