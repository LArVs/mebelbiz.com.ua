<?php

/**
 * Shopkeeper
 *
 * Shopping cart class
 *
 * @author Andchir <andchir@gmail.com>
 * @package shopkeeper
 * @version 2.2.9
 */

class Shopkeeper {
    
    public $version = '2.2.9';
    public $modx;
    public $langTxt = array();
    
/**
 *
 * @param object $modx
 * @param array $config
 */
function __construct(modX &$modx, $config = array()){
    
    $this->modx =& $modx;

    $this->config = array_merge(array(
        "corePath" => $this->modx->getOption('core_path').'components/shopkeeper/',
        "tplPath" => 'core/components/shopkeeper/elements/chunks/ru/',
        "shkPath" => "",
        "linkAllow" => true,
        "currency" => "",
        "noCounter" => false,
        "changePrice" => false,
        "priceTV" => "price",
        "tv_filter" => "",
        "charset"=>"",
        "lang" => "ru",
        "excepDigitGroup" => true,
        "allowFloatCount" => true,
        "TVsaveList" => "",
        "chCachingSnippets" => false,
        "fromParentList" => '',
        "fromParentHeight" => 1,
        "debug" => false
    ),$config);
    
    $this->modx->getService('lexicon','modLexicon');
    $this->modx->lexicon->load($this->config['lang'].':shopkeeper:default');
    $this->langTxt = $this->modx->lexicon->fetch();

    $this->modx->addPackage('shopkeeper', $this->config['corePath'].'model/');

}


/**
 * Возвращает массив данных чанка и кэширует чанк
 * 
 * @param string $source
 * @param array $properties
 * @return string
 */
function getChunk($source, $properties = null){
    if(!$source) return array('name'=>'','snippet'=>'');
    $chunk_arr = array();
    $uniqid = uniqid();
    $_validTypes = array('@CHUNK','@FILE','@INLINE');
    $type = '@CHUNK';
    if (strpos($source, '@') === 0) {
        $endPos = strpos($source, ' ');
        if ($endPos > 2 && $endPos < 10) {
            $tt = substr($source, 0, $endPos);
            if (in_array($tt, $_validTypes)) {
                $type = $tt;
                $source = substr($source, $endPos + 1);
            }
        }
    }
    if (!is_string($type) || !in_array($type, $_validTypes)) $type = $this->modx->getOption('tplType', $properties, '@CHUNK');
    $content = false;
    switch ($type) {
        case '@FILE':
            $path = $this->modx->getOption('tplPath', $properties, $this->config['tplPath']);
            $key = MODX_BASE_PATH . $path . $source;
            if (file_exists($key)) {
                $content = file_get_contents($key);
            }
            if (!empty($content) && $content !== '0') {
                $chunk_arr = array('name'=>$key,'snippet'=>$content);
            }
        break;
        case '@INLINE':
            $chunk_arr = array('name'=>"{$type}-{$uniqid}",'snippet'=>$source);
        break;
        case '@CHUNK':
        default:
            $chunk = null;            
            $chunk = $this->modx->getObject('modChunk', array('name' => $source));
            if ($chunk) {
                $chunk_arr = $chunk->toArray();
            }
        break;
    }
    
    $chunk = $this->modx->newObject('modChunk');
    $chunk->fromArray($chunk_arr);
    $chunk->setCacheable(false);
    
    return $chunk;
}


/**
 * Парсит чанк, возвращает HTML-код
 * 
 * @param string $source
 * @param array $properties
 * @return string
 */
function parseTpl($mainChunk, $properties = null) {
    $output = '';
    if(is_object($mainChunk)){
        $chunk = $this->modx->newObject('modChunk');
        $chunk->fromArray($mainChunk->toArray());
        $output = $chunk->process($properties);
    }
    if (empty($output) && $output !== '0') {
        $prefix = $this->modx->getOption('tplPrefix', $properties, '');
        $chunk = $this->modx->newObject('modChunk');
        $chunk->setCacheable(false);
        $output = $chunk->process(array("{$prefix}output" => print_r($properties, true)), "<pre>[[+{$prefix}output]]</pre>");
    }
    return $output;
}


/**
 * Изменяет некэшируемый вызов сниппетов на кэшированные (полезно при аякс-запросах)
 *
 * @param string $html
 * @return string
 */
function chunkFilter($html){
    if($this->config['chCachingSnippets']){
        $html = str_replace('[[!','[[',$html);
    }
    return $html;
}

/**
* Создает таблицы в БД и добавляет конфигурацию модуля
* 
*/
function install(){
    
    $m = $this->modx->getManager();
    $m->createObjectContainer('SHKconfig');
    $m->createObjectContainer('SHKorder');
    
    $newconf = $this->modx->newObject('SHKconfig');
    $newconf->fromArray(array(
        'setting' => 'statuses',
        'value' => 'a:6:{i:0;a:2:{i:0;s:10:"Новый";i:1;s:7:"#99CCFF";}i:1;a:2:{i:0;s:28:"Принят к оплате";i:1;s:7:"#CCFFFF";}i:2;a:2:{i:0;s:18:"Отправлен";i:1;s:7:"#FFFF99";}i:3;a:2:{i:0;s:16:"Выполнен";i:1;s:7:"#CCFFCC";}i:4;a:2:{i:0;s:14:"Отменен";i:1;s:7:"#FF99CC";}i:5;a:2:{i:0;s:29:"Оплата получена";i:1;s:7:"#FFCC99";}}',
        'xtype' => 'shk-combo-status'
    ));
    $newconf->save();
    
    $newconf = $this->modx->newObject('SHKconfig');
    $newconf->fromArray(array(
        'setting' => 'shk_version',
        'value' => $this->version,
        'xtype' => 'textfield'
    ));
    $newconf->save();
    
    $newconf = $modx->newObject('SHKconfig');
    $newconf->fromArray(array(
        'setting' => 'delivery',
        'value' => '',
        'xtype' => 'textfield'
    ));
    $newconf->save();
    
}


/**
* Проверяет является ли строка сериализованным масивом
*     
* @param string $string
*/
function is_serialized($string){
    if (!is_string($string)) return false;
    return (@unserialize($string) !== false);
}


/**
*
* @param string $str
* @return boolean
*/
function isUTF8($str){
    if($str === mb_convert_encoding(mb_convert_encoding($str, "UTF-32", "UTF-8"), "UTF-8", "UTF-32"))
      return true;
    else
      return false;
}


/**
 * Обратимое шифрование по ключу
 * 
 * @param string $str
 * @return string
 */
function encrypt($str){
    if(function_exists('mcrypt_encrypt')){
        $key = isset($this->modx->site_id) ? $this->modx->site_id : session_id();
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND);
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $str, MCRYPT_MODE_ECB, $iv)));
    }else{
        return trim($str);
    }
}


/**
 * Расшифровка строки по ключу
 * 
 * @param string $str
 * @return string
 */
function decrypt($str){
    if(function_exists('mcrypt_decrypt')){
        $key = isset($this->modx->site_id) ? $this->modx->site_id : session_id();
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND);
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($str), MCRYPT_MODE_ECB, $iv));
    }else{
        return trim($str);
    }
}


/**
* Форматирование числа (цены)
* 
* @param float $number
* @return float
*/
function numberFormat($number){
    if(!is_numeric($number)) return '';
    $output = floatval(str_replace(array(' ',','), array('','.'), $number));
    if($this->config['excepDigitGroup']==true){
        $output = number_format($number,(floor($number) == $number ? 0 : 2),'.',' ');
    }
    return $output;
}


/**
 * Очищает строку от тегов MODx
 * 
 * @param string $string
 * @return string
 */
function stripModxTags($string){
    if(isset($this->modx->sanitizePatterns['tags'])) $string = preg_replace($this->modx->sanitizePatterns['tags'], '', $string);
    if(isset($this->modx->sanitizePatterns['tags1'])) $string = preg_replace($this->modx->sanitizePatterns['tags1'], '', $string);
    if(isset($this->modx->sanitizePatterns['tags2'])) $string = preg_replace($this->modx->sanitizePatterns['tags2'], '', $string);
    return $string;
}


/**
*
* @param string $table_name
* @return string
*/
function getNextAutoIncrement($table_name){
    $next_increment = 0;
    /*$query = $this->modx->db->query("SHOW TABLE STATUS LIKE '$table_name'");
    while($row = mysql_fetch_assoc($query)){
      $next_increment = $row['Auto_increment'];
    }*/
    return $next_increment;
}

/**
* Добавляет товар в корзину (сохраняет в сессию)
* 
* @param array $purchaseArray
* @return null
*/
function savePurchaseData($purchaseArray,$addit_params = array(array())){
    
    if(!isset($purchaseArray['shk-id'])) return;

    $curSavedP = !empty($_SESSION['shk_purchases']) ? $_SESSION['shk_purchases'] : array();
    $curSavedA = !empty($_SESSION['shk_addit_params']) ? $_SESSION['shk_addit_params'] : array();
    $purchases = array(array());
    
    $multiplication = false;
    $p_id = 0;
    //определяем ID товара и если передано имя TV с ценой
    $prodIdArr = isset($purchaseArray['shk-id']) ? explode('__',$purchaseArray['shk-id']) : array(1);
    $p_id = is_numeric($prodIdArr[0]) ? $prodIdArr[0] : 0;
    $price_tv_name = isset($prodIdArr[1]) ? $prodIdArr[1] : $this->config['priceTV'];
    //проверяем есть ли такой ID в БД
    $doc_count = $this->modx->getCount('modResource',array('id'=>$p_id,'published'=>1,'deleted'=>0));
    if(!$doc_count) return;
    
    $price_tv = $this->modx->getObject('modTemplateVar',array('name'=>$price_tv_name));
    
    //формируем массив данных товара
    $purchases[0]['id'] = $p_id;
    $purchases[0]['count'] = !empty($purchaseArray['count']) ? $this->cleanCount($purchaseArray['count']) : (!empty($purchaseArray['shk-count']) ? $this->cleanCount($purchaseArray['shk-count']) : 1);
    $purchases[0]['price'] = $price_tv ? floatval(str_replace(',','.',$price_tv->renderOutput($p_id))) : 0;
    $purchases[0]['tv_add'] = array();
    
    //Добавление данных родителя
    if($this->config['fromParentList']){
        $purchases[0]['tv_add'] = $this->addFromParent($p_id);
    }
    
    //OnSHKaddProduct
    $evtOut = $this->modx->invokeEvent('OnSHKaddProduct',array('purchaseArray' => $purchaseArray));
    if(is_array($evtOut) && $evtOut[0]=='return') return;
    //OnSHKgetProductPrice
    $evtOut = $this->modx->invokeEvent('OnSHKgetProductPrice',array('purchaseArray' => $purchaseArray, 'price' => $purchases[0]['price']));    
    if(is_array($evtOut) && count($evtOut)>0){
        $new_price = (float) str_replace(array(' ',','),array('','.'),$evtOut[0]);
        if($new_price != $purchases[0]['price']){
            $purchases[0]['tv_add']['shk_oldprice'] = $purchases[0]['price'];
            $purchases[0]['price'] = $new_price;
        }
    }
    
    unset($purchaseArray['shk-id'],$purchaseArray['count'],$purchaseArray['shk-count']);
    
    //обрабатываем доп.параметры товара
    foreach($purchaseArray as $key => $value){
        if(!preg_match("/".$p_id."/",$key) || empty($value)) continue;
        
        list($a_fieldname,$a_id,$a_string) = substr_count($key,'__')==2 ? explode('__',$key) : explode('__',$key.'__');
        
        //если параметр не нужно брать из TV
        if(!empty($a_string) && $a_string=='add'){
            
            list($a_name,$a_price) = array($value,0);
            $purchases[0]['tv_add'][$a_fieldname] = $value;
            
        //если цену доп.параметра нужно брать из TV
        }else{
            
            $a_tv = $this->modx->getObject('modTemplateVar',array('name'=>$a_fieldname));
            $a_val_res = $a_tv ? $a_tv->getValue($p_id) : '';
            $a_val_arr = explode('||',$a_val_res);//разбиваем строку из значения TV
            //разбиваем строку из значения поля параметра из $_POST
            list($afi,$afp,$afn) = substr_count($value,'__')==2 ? explode('__',$value) : explode('__',$value.'__');
            //разбиваем значение TV
            list($a_name,$a_price) = empty($afn) ? explode('==',$a_val_arr[$afi]) : array($afn,0);
            
        }
        
        if(strlen($a_price)>0){
            
            //проверяем нужно ли умножать
            if(strpos($a_price,'*')!==false){
                $multiplication = true;
                $a_price = (float) str_replace('*','',$a_price);
            }else{
                $multiplication = false;
            }
            
            $a_price = (float) str_replace(',','.',$a_price);
            
            if($this->config['changePrice'] || $multiplication){
            if(isset($purchases[0]['price'])){
                if($this->config['changePrice']=='replace'){
                    if($a_price>0) $purchases[0]['price'] = $a_price;
                }else{
                    $purchases[0]['price'] = !$multiplication ? $purchases[0]['price']+$a_price : $purchases[0]['price']*$a_price;
                }
            }else{
                $purchases[0]['price'] = $a_price;
            }
                $a_price = 0;
            }
            //добавляем параметр в массив
            $addit_params[0][] = array($a_name,$a_price);
            //добаляем имя и значение параметра для вывода отдельно в корзине
            $purchases[0]['tv_add']['shk_'.$a_fieldname] = $a_name;
            unset($a_name,$a_price);
            
        }
    }
    
    //Дополнительные параметры товара из плагина
    $evtOut = $this->modx->invokeEvent('OnSHKgetProductAdditParams',array('purchaseArray' => $purchaseArray, 'id' => $purchases[0]['id']));
    if(is_array($evtOut) && count($evtOut)>0){
        $plugin_params = json_decode($evtOut[0],true);
        if(!empty($plugin_params) && is_array($plugin_params)){
            $purchases[0]['tv_add'] = array_merge($purchases[0]['tv_add'],$plugin_params);
        }
    }
    
    ksort($purchases[0]);
    if(!isset($addit_params[0])) $addit_params[0] = array();
    $intersect = $this->checkIntersect($purchases[0],$addit_params[0]);
    if($intersect===false){
        $_SESSION['shk_purchases'] = array_merge($purchases,$curSavedP);
        $_SESSION['shk_addit_params'] = array_merge($addit_params,$curSavedA);
    }else{
        $this->recountDataArray($intersect,$purchases[0]['count'],true);
    }
    
    $evtOut = $this->modx->invokeEvent('OnSHKAfterAddProduct',array(array('id'=>$p_id)));
    
}


/**
 * Добавление данных родителя для товара
 * 
 * @param integer $id
 * @return array
 */
function addFromParent($id){
    $output = array();
    $tv_arr = array();
    $fromParent_arr = explode(',',str_replace(' ','',$this->config['fromParentList']));
    $parent_id_arr = $this->modx->getParentIds($id, $this->config['fromParentHeight']);
    foreach($parent_id_arr as $level => $parent_id){
        $parent = $this->modx->getObject('modResource',$parent_id);
        if($parent){
            foreach($fromParent_arr as $key => $val){
                if($parent->get($val)) $output['parent'.($level>0 ? $level+1 : '').'.'.$val] = $parent->get($val);
                else{
                    $tv_val = $parent->getTVValue($val);
                    if($tv_val) $output['parent'.($level>0 ? $level+1 : '').'.'.$val] = $tv_val;
                }
            }
        }
    }
    return $output;
}


/**
 * Добавляет товар к заказу
 * 
 * @param array $curSavedP
 * @param int $p_id
 * @param string $p_count
 * @param string $p_price
 * @param string $price_tv_name
 * @return array
 */
function addToOrder($curSavedP=array(),$p_id,$p_count=1,$p_price='',$price_tv_name=''){
    
    if(count($curSavedP)==0){
        $curSavedP = !empty($_SESSION['shk_purchases']) ? $_SESSION['shk_purchases'] : array();
    }
    
    //Проверяем есть ли такой ID в БД
    $doc_count = $this->modx->getCount('modResource',array('id'=>$p_id,'published'=>1,'deleted'=>0));
    if($doc_count==0) return $curSavedP;
    
    $purchases = array();
    
    $price_tv_name = !empty($price_tv_name) ? $price_tv_name : $this->config['priceTV'];
    $price_tv = $this->modx->getObject('modTemplateVar',array('name'=>$price_tv_name));
    
    //формируем массив данных товара
    $purchases[0]['id'] = $p_id;
    $purchases[0]['count'] = is_numeric($p_price) ? $p_price : 1;
    $purchases[0]['price'] = strlen($p_price)>0 ? floatval(str_replace(',','.',$p_price)) : ($price_tv ? floatval(str_replace(',','.',$price_tv->renderOutput($p_id))) : 0);
    
    //Название товара
    $contentData = $this->getContentData($purchases);
    $purchases[0]['name'] = isset($contentData[$p_id]['pagetitle']) ? $contentData[$p_id]['pagetitle'] : '';
    
    return array_merge($curSavedP,$purchases);
}


/**
 * Добавляет товары по массиву ID
 * 
 * @param array $ids_arr
 * @param string $price_tv_name
 * @return boolean
 */
function addProductsFromArray($ids_arr,$count_arr=array(),$price_tv_name = 0){
    if(is_array($ids_arr) && count($ids_arr)>0){
        $curSavedP = !empty($_SESSION['shk_purchases']) ? $_SESSION['shk_purchases'] : array();
        $curSavedA = !empty($_SESSION['shk_addit_params']) ? $_SESSION['shk_addit_params'] : array();
        $purchases = array();
        $addit_params = array();
        if(!$price_tv_name) $price_tv_name = $this->config['priceTV'];
        
        foreach($ids_arr as $key => $p_id){
            if($this->modx->getCount('modResource',array('id'=>$p_id,'published'=>1))==0) continue;
            $price_tv = $this->modx->getObject('modTemplateVar',array('name'=>$price_tv_name));
            $p_price = $price_tv ? floatval(str_replace(',','.',$price_tv->renderOutput($p_id))) : 0;
            if(!$p_price) continue;
            
            $temp_purchases = array('id'=>$p_id,'count'=>(isset($count_arr[$key]) && is_numeric($count_arr[$key]) ? $count_arr[$key] : 1),'price'=>$p_price);
            $intersect = $this->checkIntersect($temp_purchases,array());
            
            //Пересчёт товаров в корзине
            if($intersect!==false){
                $this->recountDataArray($intersect,$temp_purchases['count'],true);
            //Добавление товаров
            }else{
                
                //OnSHKaddProduct
                $evtOut = $this->modx->invokeEvent('OnSHKaddProduct',array('purchaseArray' => $temp_purchases));
                if(is_array($evtOut) && $evtOut[0]=='return') return;
                //OnSHKgetProductPrice
                $evtOut = $this->modx->invokeEvent('OnSHKgetProductPrice',array('purchaseArray' => $temp_purchases, 'price' => $temp_purchases['price']));    
                if(is_array($evtOut) && count($evtOut)>0){
                    $new_price = (float) str_replace(array(' ',','),array('','.'),$evtOut[0]);
                    if($new_price != $temp_purchases['price']){
                        $temp_purchases['tv_add']['shk_oldprice'] = $temp_purchases['price'];
                        $temp_purchases['price'] = $new_price;
                    }
                }
                
                $purchases[] = $temp_purchases;
                $addit_params[] = array();
                
            }
        }
        
        if(count($purchases)>0){
            $_SESSION['shk_purchases'] = array_merge($purchases,$curSavedP);
            $_SESSION['shk_addit_params'] = array_merge($addit_params,$curSavedA);
        }
        
        $evtOut = $this->modx->invokeEvent('OnSHKAfterAddProduct',array(array('id'=>$ids_arr)));
        
        return true;
    }
    return false;
}

/**
* Проверяет есть ли добавляемый товар уже в корзине
* 
* @param array $newArrayP
* @param array $newArrayA
* @return int
*/
function checkIntersect($newArrayP,$newArrayA){
    $curSavedP = !empty($_SESSION['shk_purchases']) ? $_SESSION['shk_purchases'] : array();
    $curSavedA = !empty($_SESSION['shk_addit_params']) ? $_SESSION['shk_addit_params'] : false;
    $output = false;
    for($i=0;$i<count($curSavedP);$i++){
      if($curSavedP[$i]['id']==$newArrayP['id'] && $curSavedP[$i]['price']==$newArrayP['price']){
        if($curSavedA!==false){
          if(serialize($newArrayA)==serialize($curSavedA[$i])){
            $output = $i;
            break;
          }
        }else{
          $output = $i;
          break;
        }
      }
    }
    return $output;
}


/**
 * Проверяет введенное число кол-ва товаров и приводит к нормальному виду
 * 
 * @param type $count
 * @return int|float
 */
function cleanCount($count){
    $output = str_replace(array(',',' '),array('.',''),$count);
    if(!is_numeric($output) || empty($output)) return 1;
    $output = $this->config['allowFloatCount'] ? floatval($output) : ceil(floatval($output));
    return abs($output);
}


/**
* Пересчитывает кол-во товара
* 
* @param int $index
* @param int $count
* @param boolean $plus
*/
function recountDataArray($index,$count,$plus=true){
    if($count<=0) return;
    else $count = $this->cleanCount($count);
    $curSavedP = $_SESSION['shk_purchases'];
    $curSavedP[$index]['count'] = $plus==true ? $curSavedP[$index]['count']+$count : $count;
    $_SESSION['shk_purchases'] = $curSavedP;
}


/**
* Пересчитывает все товары с новым количеством
* 
* @param array $countArr
*/
function recountAll($countArr){
    $curSavedP = $_SESSION['shk_purchases'];
    $outputArray = $curSavedP;
    for($i=0;$i<count($curSavedP);$i++){
        $outputArray[$i]['count'] = isset($countArr[$i]) ? $this->cleanCount($countArr[$i]) : 1;
    }
    $_SESSION['shk_purchases'] = $outputArray;
}


/**
* Удаляет товар из корзины
* 
* @param int $index
* @return null
*/
function delArrayItem($index){
    if(!isset($index)) return;
    $curSavedP = !empty($_SESSION['shk_purchases']) ? $_SESSION['shk_purchases'] : array();
    $curSavedA = !empty($_SESSION['shk_addit_params']) ? $_SESSION['shk_addit_params'] : false;
    $outputP = array();
    $outputA = array();
    if(count($curSavedP)>1 && isset($curSavedA[$index])){
        unset($curSavedP[$index]);
        if($curSavedA!==false){
            unset($curSavedA[$index]);
        }
        $c = 0;
        foreach ($curSavedP as $i => $value){
            $outputP[$c] = $value;
            if($curSavedA!==false){
                $outputA[$c] = $curSavedA[$i];
            }
            $c++;
        }
        $_SESSION['shk_purchases'] = $outputP;
        if($curSavedA!==false){
            $_SESSION['shk_addit_params'] = $outputA;
        }
    }else{
        $this->emptySavedData();
    }
}


/**
* Очищает корзину
*/
function emptySavedData(){
    $_SESSION['shk_purchases'] = NULL;
    $_SESSION['shk_addit_params'] = NULL;
    unset($_SESSION['shk_purchases'],$_SESSION['shk_addit_params']);
    //echo '<script type="text/javascript">if(typeof(jQuery)!=\'undefined\' && typeof(jQuery.refreshCart)!=\'undefined\')$(document).bind(\'ready\',jQuery.refreshCart);</script>';
}


/**
* Вытаскивает значения полей для товаров
* 
* @param array $array
* @param boolean $withids
* @return array
*/
function getContentData($array, $withids=true){
    $output = array();
    $ids = array();
    foreach($array as $val){
        if(!empty($val['id'])) array_push($ids, (int) $val['id']);
    }
    //Если не хватает данных товаров
    if(!isset($array[0]['name'])){
        
        if(count($ids)>0){
            $query = $this->modx->newQuery('modResource');
            $query->where(array('id:IN' => $ids, 'published' => 1));
            $query->select($this->modx->getSelectColumns('modResource','modResource','',array('id','pagetitle')));
            $resources = $this->modx->getCollection('modResource',$query);
            foreach ($resources as $key => $resource) {
                $output[$resource->get('id')] = $resource->toArray();
            }
        }
    //Если данные товара уже есть в массиве
    }else{
        
        foreach($array as $prod){
            $output[$prod['id']]['pagetitle'] = $prod['name'];
        }
        
    }
    return $output;
}

/**
* Вытаскивает значения TV параметров по ID товаров
* 
* @param array $array
* @return array
*/
function getTmplVars($array,$tvNames=array()){
    $templateVars = array(array(),array());
    $tv_names = array();
        
    $ids = array();
    foreach($array as $val){
        array_push($ids,(int)$val['id']);
    }
    
    if(count($ids)>0){
        $query = $this->modx->newQuery('modTemplateVarResource');
        $query->leftJoin('modTemplateVar','modTemplateVar',array("modTemplateVar.id = tmplvarid"));
        $query->where(array('contentid:IN'=>$ids));
        $query->select($this->modx->getSelectColumns('modTemplateVarResource','modTemplateVarResource','',array('id','tmplvarid','contentid','value')));
        $query->select($this->modx->getSelectColumns('modTemplateVar','modTemplateVar','',array('name')));
        
        //Если нужно фильтруем по ID параметров
        if(strlen($this->config['tv_filter'])>0) $query->where(array('tmplvarid:NOT IN'=>explode(',',$this->config['tv_filter'])));
        
        //Фильтруем по именам
        if(count($tvNames)>0){
            $query->where(array('modTemplateVar.name:IN'=>$tvNames));
        }
        
        $tvars = $this->modx->getCollection('modTemplateVarResource',$query);
        //var_dump($this->modx->getCount('modTemplateVarResource', $query));
        foreach ($tvars as $key => $tv) {
            $templateVars[$tv->get('contentid')][$tv->get('name')] = $tv->get('value');
        }
    }

    return $templateVars;
}

/**
* 
* @param array $array
* @param array $keys  
* @return array
*/
function arrayFillHoles($array,$keys){
    foreach($array as $key => &$val){
        $hole = array_diff($keys,array_keys($val));
        foreach($hole as $k => $v) $val[$v] = '';
    }
    return $array;
}


/**
* Собирает данные всех товаров и возвращает HTML-код по шаблону
* 
* @param array $purchases
* @param array $addit_params
* @param array $allowed
* @param string $this_page_url
* @param boolean $del_notavailable
* @return string
*/
function getProductsList($purchases,$addit_params,$allowed=false,$this_page_url='',$del_notavailable=false){
    $output = '';
    $url_qs = strpos($this_page_url, "?")!==false ? "&amp;" : "?";
    
    if(count($purchases)>0){

        $contentData = $this->getContentData($purchases);
        $templateVars = $this->getTmplVars($purchases);
        
        $num = 0;
        
        if($del_notavailable){
            //Удаляем товары, которые не могут быть куплены
            foreach($purchases as $key => $value){
                if(is_array($allowed) && !in_array($key,$allowed)) unset($purchases[$key]);
            }
            unset($key,$value);
        }
        
        $mainChunk = $this->getChunk($this->config['cartRowTpl']);
        $mainChunk->set('snippet',$this->chunkFilter($mainChunk->get('snippet')));
        $chunk_addit = isset($this->config['additDataTpl']) ? $this->getChunk($this->config['additDataTpl']) : false;
        
        foreach($purchases as $i => $dataArray){
            
            $id = $dataArray['id'];
            
            $name = isset($dataArray['name']) ? $dataArray['name'] : (isset($contentData[$id]['pagetitle']) ? $contentData[$id]['pagetitle'] : '');
            $tvArr = isset($templateVars[$id]) ? (array) $templateVars[$id] : array();
            if(isset($purchases[$i]['tv_add'])) $tvArr = array_merge($tvArr,$purchases[$i]['tv_add']);
            $link = is_numeric($id) && $id>0 ? $this->modx->makeUrl($id, '', '', 'full') : '';
            
            list($additStr, $additPrice) = $this->getAddit($chunk_addit,$addit_params[$i],$i);
            
            $available = $allowed===false || (is_array($allowed) && in_array($i,$allowed)) ? true : false;
            if($available) $num++;
            
            $chunkArr = array(
              'name' => $name,
              'id' => $id,
              'link' => $link,
              'addit_data' => $additStr,
              'price' => $this->numberFormat($dataArray['price']),
              'price_total' => $this->numberFormat($dataArray['price'] + $additPrice),
              'price_count' => $this->numberFormat($dataArray['price'] * $dataArray['count']),
              'price_count_total' => $this->numberFormat(($dataArray['price'] + $additPrice) * $dataArray['count']),
              'currency' => $this->config['currency'],
              'count' => $dataArray['count'],
              'this_page_url' => $this_page_url,
              'index' => $i,
              'num' => $num,
              'even' => $num%2==0 ? 1 : 0,
              'comma' => (($del_notavailable && $num!=count($purchases)) || (!$del_notavailable && $i!=count($purchases)-1)) && count($purchases)>1 ? ',' : '',
              'url_del_item' => $this_page_url.$url_qs."shk_action=del&amp;n=".$i,
              'available' => $available ? 'available' : 'notavailable',
              's' => $available ? '' : '<s>',
              '/s' => $available ? '' : '</s>'
            );
            
            $output .= $this->parseTpl($mainChunk,array_merge($tvArr,$chunkArr));
            
        }//end foreach
        
        $output = $this->stripModxTags($output);
        
    }
    return $output;
}


/**
* Формирует строку доп. параметров товаров
* 
* @param array $array
* @param int $index
* @return array
*/
function getAddit($chunk,$additParam,$index){
    $outputArray = array();
    $outputStr = '';
    $outputPrice = 0;
    if(!empty($additParam[0][0])){
      for($i=0;$i<count($additParam);$i++){
        list($name,$price) = $additParam[$i];
        if($this->config['charset']=='windows-1251' && $this->isUTF8($name)){
            $name = iconv("UTF-8", "windows-1251", $name);
        }
        $param = $price!=0 ? "$name ($price)" : $name;
        $outputPrice += $price;
        if(is_object($chunk)){
            $chunkArr = array(
                'param' => $param,
                'name' => $name,
                'price' => $price
            );
            $outputStr .= $this->parseTpl($chunk,array_merge(array('tplPath'=>MODX_BASE_PATH.$this->config['tplPath']),$chunkArr));
        }else{
            $outputStr .= "$param, ";
        }
      }
    }
    return array(trim($outputStr,", "), $outputPrice);
}


/**
* Считает общую стоимость и количество товара
* 
* @param array $purchases
* @param array $addit_params
* @param array $allowed
* @return array
*/
function getTotal($purchases=array(),$addit_params=array(),$allowed=false){
    $price_total = 0;
    $items_total = 0;
    $items_unique_total = 0;
    
    if(!is_array($purchases) || count($purchases)==0)
        $purchases = !empty($_SESSION['shk_purchases']) ? $_SESSION['shk_purchases'] : array();
    if(!is_array($addit_params) || count($addit_params)==0)
        $addit_params = !empty($_SESSION['shk_addit_params']) ? $_SESSION['shk_addit_params'] : array();
    
    if(!empty($purchases)){
      for($i=0;$i<count($purchases);$i++){
        $prod = $purchases[$i];
        if($allowed===false || (is_array($allowed) && in_array($i,$allowed))){
          if(!empty($addit_params[$i])){
            $price_total += $prod['price']*$prod['count'];
            for($ii=0;$ii<count($addit_params[$i]);$ii++){
              $price_total += $addit_params[$i][$ii][1] * $prod['count'];
            }
          }else{
            $price_total += $prod['price'] * $prod['count'];
          }
          $items_total += $prod['count'];
        }
      }
    }
    
    $items_unique_total = count($purchases);
    
    //OnSHKcalcTotalPrice
    $evtOut = $this->modx->invokeEvent('OnSHKcalcTotalPrice',array(
        'price_total' => $price_total,
        'purchases' => $purchases
    ));
    if(isset($evtOut[0]) && $evtOut[0]!='') $price_total = $evtOut[0];
    $price_total = str_replace(',', '.', (string) $price_total);
    
    //OnSHKcalcTotaQuantity
    $evtOut = $this->modx->invokeEvent('OnSHKcalcTotaQuantity',array(
        'items_total' => $items_total,
        'items_unique_total' => $items_unique_total
    ));
    if(isset($evtOut[0]) && strlen($evtOut[0])>2){
        list($items_total,$items_unique_total) = explode('||',$evtOut[0]);
    }
    
    $output = array($price_total,$items_total,$items_unique_total);
    return $output;
}


/**
* Возвращает массив или строку ID товаров в корзине
* 
* @param string $outtype
* @return mixed
*/
function getProdIds($outtype='array'){
    $purchases = isset($_SESSION['shk_purchases']) ? $_SESSION['shk_purchases'] : array();
    $out_arr = array();
    foreach($purchases as $prod){
        array_push($out_arr,intval($prod['id']));
    }
    return $outtype=='array' ? array_unique($out_arr) : implode(',',array_unique($out_arr));
}


/**
 * Приводит к нормальному виду (массиву) строку разрешённых к заказу товаров
 * 
 * @param string $allowed
 * @param array $purchases
 * @return array
 */
function allowedArray($allowed,$purchases){
    if(empty($allowed) && $allowed!='0'){
        $o_allowed = array();
    }else if(is_array($allowed)){
        $o_allowed = $allowed;
    }elseif($allowed=='all'){
        $o_allowed = array();
        foreach ($purchases as $i => $arr) {
            $o_allowed = array_merge($o_allowed,array($i));
        }
        unset($arr);
    }else{
        $o_allowed = explode(',',$allowed);
    }
    return $o_allowed;
}


/**
*
* @param int $n
* @return string
*/
function getPlural($n){
    $plural = '';
    $plural_arr = explode(',',str_replace(', ',',',$this->langTxt['shk.plural']));
    if($this->langTxt['shk.this']=='russian' || $this->langTxt['shk.this']=='russian-UTF8'){
        $plural = $n%10==1 && $n%100!=11 ? $plural_arr[0] : ($n%10>=2 && $n%10<=4 && ($n%100<10 || $n%100>=20) ? $plural_arr[1] : $plural_arr[2]);
    }else{
        $plural = $n!=1 ? $plural_arr[0] : $plural_arr[1];
    }
    return $plural;
}


/**
* Создает HTML-код корзины
* 
* @param int $thisPage
* @return string
*/
function getCartContent($thisPage=false){
    if($thisPage===false) $thisPage = $this->modx->resourceIdentifier;
    $mainChunk = $this->getChunk($this->config['cartTpl']);
    $chunk_parts = $mainChunk->get('snippet') ? explode('<!--tpl_separator-->',$mainChunk->get('snippet')) : array();
    
    $this->modx->invokeEvent('OnSHKbeforeCartLoad');
    
    if(!empty($_SESSION['shk_purchases'])){
        
        $this_page_url = is_numeric($thisPage) ? $this->modx->makeUrl($thisPage, '', '', 'full') : $thisPage;
        $url_qs = strpos($this_page_url, "?")!==false ? "&amp;" : "?";
        $purchases = !empty($_SESSION['shk_purchases']) ? $_SESSION['shk_purchases'] : array();
        $addit_params = !empty($_SESSION['shk_addit_params']) ? $_SESSION['shk_addit_params'] : array();
        list($price_total,$items_total,$items_unique_total) = $this->getTotal($purchases,$addit_params);
        
        $evtOut = $this->modx->invokeEvent('OnSHKcartLoad',array('price_total'=>$price_total,'items_total'=>$items_total,'items_unique_total'=>$items_unique_total));
        $plugin = is_array($evtOut) ? implode('', $evtOut) : '';
        
        $cartInner = $this->getProductsList($purchases,$addit_params,false,$this_page_url);
        
        $chunkArr = array(
            'inner' => $cartInner,
            'price_total' => $this->numberFormat($price_total),
            'items_total' => $items_total,
            'items_unique_total' => $items_unique_total,
            'plural' => $this->getPlural($items_total),
            'this_page_url' => $this_page_url,
            'empty_url' => $this_page_url.$url_qs.'shk_action=empty',
            'order_page_url' => $this->modx->makeUrl($this->modx->getOption('orderFormPage',$this->config,1)),
            'currency' => $this->config['currency'],
            'plugin' => $plugin
        );
        
        $this->modx->setPlaceholders(array(
            'price_total' => $price_total,
            'items_total' => $items_total,
            'items_unique_total' => $items_unique_total,
            'currency' => $this->config['currency']
        ),'shk.');
        
        $chunk = $this->getChunk('@INLINE '.$this->chunkFilter($chunk_parts[1]));
        $output = $this->parseTpl($chunk,$chunkArr);
        
        //Отладочная информация в журнале ошибок
        if($this->config['debug']){
            $this->modx->log(modX::LOG_LEVEL_ERROR, print_r($purchases,true).print_r($addit_params,true));
        }

    }else{
        
        $this->modx->setPlaceholders(array(
            'price_total' => 0,
            'items_total' => 0,
            'items_unique_total' => 0,
            'currency' => $this->config['currency']
        ),'shk.');
        
        $chunk = $this->getChunk('@INLINE '.$this->chunkFilter($chunk_parts[0]));
        $output = $this->parseTpl($chunk,array('plugin'=>''));
        
    }
    
    //создаём плейсхолдеры с данными последнего заказа
    if(isset($_SESSION['shk_lastOrder']) && is_array($_SESSION['shk_lastOrder'])){
        $this->modx->setPlaceholders($_SESSION['shk_lastOrder'],'shk.');
    }
    
    return $output;
}


/**
 * Формирует HTML-код с данными о заказе
 * 
 * @param type $purchases
 * @param type $addit_params
 * @param type $allowed
 * @param type $allowed
 * @return string 
 */
function getOrderData($purchases,$addit_params=array(),$allowed='all'){
    $output = '';
    if(!isset($purchases) || empty($this->config['orderDataTpl'])) return $output;
    
    $chunk_parts = $this->getChunk($this->config['orderDataTpl']);
    $mainChunk = $chunk_parts->get('snippet') ? $chunk_parts->get('snippet') : '';
    
    $p_allowed = $this->allowedArray($allowed,$purchases);
    $chunk = preg_split('/(\[\[\+loop\]\]|\[\[\+end_loop\]\])/s', $mainChunk);
    
    $this->config['cartRowTpl'] = '@INLINE '.$chunk[1];
    list($price_total,$items_total,$items_unique_total) = $this->getTotal($purchases,$addit_params,$p_allowed);
    $orderData = $this->getProductsList($purchases,$addit_params,$p_allowed);
    $chunkArr = array(
        'price_total' => $this->numberFormat($price_total),
        'items_total' => $items_total,
        'items_unique_total' => $items_unique_total,
        'currency' => $this->config['currency'],
        'inner' => $orderData
    );
    $chunk = $this->getChunk('@INLINE '.$chunk[0].'[[+inner]]'.$chunk[2]);
    $output = $this->parseTpl($chunk, $chunkArr);
    return $output;
}


/**
 * Сохраняет данные последнего заказа в сессию
 * 
 * @param array $order
 * @return boolean
 */
function setOrderDataSession($order=array()){
    
    $_SESSION['shk_lastOrder'] = array(
        'id' => $order['id'],
        'price' => $order['price'],
        'currency' => $order['currency'],
        'date' => date('d.m.Y',strtotime($order['date'])),
        'full_date' => $order['date'],
        'email' => $order['email'],
        'phone' => $order['phone'],
        'delivery' => $order['delivery'],
        'payment' => $order['payment'],
        'userid' => $order['userid']
    );
    
    return true;
}


}


?>