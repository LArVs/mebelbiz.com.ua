<?php

/**
 * Class for getProducts.
 *
 * @package getproducts
 * @version 1.2.5
 * @author Andchir <andchir@modx-shopkeeper.ru>
 */
class getProducts {
    
    public $modx = null;
    public $config = array();
    public $products = array();
    public $ids_arr = array();
    public $parent_ids = array();
    public $parents_data = array();
    
    function __construct(modX &$modx, array $config = array()) {
        
        $this->modx =& $modx;
        
        $this->config = array_merge(array(
            "tplPath" => 'core/components/getproducts/elements/chunks/',
            'parents' => $this->modx->resource->get('id'),
            'resources' => '',
            'tpl' => '',
            'depth' => 0,
            'tvFilters' => array(),
            'where' => '',
            'table_prefix' => $this->modx->config['table_prefix'],
            'context' => '',
            'limit' => '',
            'offset' => '0',
            'groupby' => '',
            'sortby' => 'menuindex',
            'sortdir' => 'ASC',
            'orderby' => '',
            'orderbyResources' => false,
            'sortbyTV' => '',
            'sortdirTV' => 'ASC',
            'sortbyTVType' => 'string',
            'includeTVs' => false,
            'includeTVList' => '',
            'processTVs' => false,
            'processTVList' => '',
            'fromParentList' => '',
            'fromParentHeight' => '',
            'activeClass' => 'active',
            'addSubItemCount' => false,
            'activeParentSnippet' => '',
            'includeContent' => false,
            'useSmarty' => false,
            'debug' => false
        ),$config);
        
        if(!empty($this->config['tvFilters'])) $this->config['tvFilters'] = $this->modx->fromJSON($this->config['tvFilters']);
        if(!$this->config['context']) $this->config['context'] = $this->modx->context->get('key');
        
        $this->config['processTVList'] = explode(',',str_replace(' ','',$this->config['processTVList']));
        
        $this->parent_ids = $this->getParents();
        $this->config['includeTVList_arr'] = explode(',',$this->config['includeTVList']);
        $this->config['fromParentList_arr'] = explode(',',str_replace(' ','',$this->config['fromParentList']));
        
        if($this->config['orderby']){
            $this->config['orderby'] = $this->modx->fromJSON($this->config['orderby']);
        }else{
            $this->config['orderby'] = array($this->config['sortby'] => $this->config['sortdir']);
        }
        
        $this->config['all_parents'] = $this->modx->getParentIds($this->modx->resource->get('id'),10,array('context'=>$this->config['context']));
        
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
               $key = $path . $source;
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
        
        //Smarty
        if($this->config['useSmarty']){
            
            $this->modx->smarty->clear_assign('item');
            $this->modx->smarty->assign('item',$properties);
            $output = $this->modx->smarty->fetch('string:'.$mainChunk->get('snippet'));
            
        //Парсер MODX
        }else{
            
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
            
        }
        
        return $output;
    }
    
    /**
     * Создание SQL для фильтрации
     *
     * @param string $flt_act
     * @param mixed $ftl_val
     */
    function prepareFiltersSQL($flt_act,$ftl_val){
        
        $flt_act = explode(':', $flt_act);
        
        if(isset($flt_act[1])) $flt_act[1] = explode(',',$flt_act[1]);
        else $flt_act[1] = '=';
        
        if(!is_array($ftl_val)) $ftl_val = array($ftl_val);
        
        $sql = " AND (`tv`.`name` = '{$flt_act[0]}' AND (";
        
        foreach($ftl_val as $key => $val){
            $f_act = isset($flt_act[1][$key]) ? $flt_act[1][$key] : (isset($flt_act[1][0]) ? $flt_act[1][0] : '=');
            $f_val = isset($ftl_val[$key]) ? $ftl_val[$key] : $ftl_val[0];
            
            if(count($ftl_val)%2 == 0 && $flt_act[2] == 'BETWEEN'){
                
                if($key%2==1){ continue; }
                
                if($key>0) $sql .= " OR ";
                $sql .= " (`tvc`.`value` BETWEEN ".$ftl_val[$key]." AND ".$ftl_val[$key+1]." ) ";
                
            }else{
                
                if($key>0) $sql .= isset($flt_act[2]) ? " {$flt_act[2]} " : ' OR ';
                
                if((in_array($f_act,array('<','>','<=','>=')) && is_numeric($f_val)) || in_array($f_act,array('IN','NOT IN'))){
                    $sql .= "`tvc`.`value` {$f_act} {$f_val}";
                }else{
                    $sql .= "`tvc`.`value` {$f_act} '{$f_val}'";
                }
                
            }
            
        }
        
        $sql .= "))";
        
        return $sql;
        
    }
    
    
    /**
     * Поиск ID товаров по заданным параметрам
     *
     */
    function getProductIds(){
        
        $where_str = '';
        if($this->config['where']){
            $where_arr = $this->modx->fromJSON($this->config['where']);
            foreach($where_arr as $key => $val){
                $wh = explode(':',$key);
                if(!isset($wh[1])) $wh[1] = '=';
                if(strtolower($wh[1])=='in' && is_array($val)){
                    $val_str = is_numeric($val[0]) ? "(".implode(',',$val).")" : "('".implode("','",$val)."')";
                    $where_str .= " AND `sc`.`{$wh[0]}` {$wh[1]} ".$val_str;
                }else{
                    $where_str .= " AND `sc`.`{$wh[0]}` {$wh[1]} '{$val}'";
                }
            }
            unset($key,$val);
        }
        
        if(count($this->config['tvFilters'])>0){
            
            $sql = "
            SELECT DISTINCT `sc`.`id` FROM `".$this->config['table_prefix']."site_content` `sc`
            LEFT JOIN `".$this->config['table_prefix']."site_tmplvar_contentvalues` `tvc` ON `sc`.`id` = `tvc`.`contentid`
            LEFT JOIN `".$this->config['table_prefix']."site_tmplvars` `tv` ON `tv`.`id` = `tvc`.`tmplvarid`
            WHERE ( `sc`.`parent` IN (".implode(',',$this->parent_ids).")
            ";
            
            $sql .= $this->prepareFiltersSQL(current(array_keys($this->config['tvFilters'])), current(array_values($this->config['tvFilters'])));
            
            $sql .= "
            AND `sc`.`published` = 1 AND `sc`.`deleted` = 0 AND `sc`.`context_key` = '".$this->config['context']."'
            {$where_str} ) ";
            $sql .= $this->appendSQLResourcesIds();
            $sql .= $this->config['groupby'];
            
            array_shift($this->config['tvFilters']);
            
        }else{
            
            $sql = "
            SELECT DISTINCT `sc`.`id` FROM `".$this->config['table_prefix']."site_content` `sc`
            WHERE ( `sc`.`parent` IN (".implode(',',$this->parent_ids).")
            AND `sc`.`published` = 1 AND `deleted` = 0 AND `sc`.`context_key` = '".$this->config['context']."'
            {$where_str} ) ";
            $sql .= $this->appendSQLResourcesIds();
            $sql .= $this->config['groupby'];
            
        }
        
        $stmt = $this->modx->prepare($sql);
        $count = 0;
        if($stmt && $stmt->execute()){
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($this->ids_arr,$row['id']);
            }
        }
        
        if($this->config['debug']) $this->modx->log(modX::LOG_LEVEL_ERROR, 'getProducts: total = '.count($this->ids_arr).' - '.$sql);
        
        
    }
    
    
    /**
     * 
     * 
     */
    function appendSQLResourcesIds(){
        
        $out = '';
        
        if($this->config['resources']){
            $resources_arr = explode(',',str_replace(' ','',$this->config['resources']));
            if(count($resources_arr)>0){
                $out = "
                OR `sc`.`id` IN (".implode(',',$resources_arr).")
                ";
            }
        }
        
        return $out;
        
    }
    
    
    /**
     * Фильтрация
     * 
     */
    function filterProducts(){
        
        foreach($this->config['tvFilters'] as $key => $val){
            
            if(!empty($val)){
                
                $sql = "
                SELECT DISTINCT `sc`.`id` AS `res_id` FROM `".$this->config['table_prefix']."site_content` `sc`
                LEFT JOIN `".$this->config['table_prefix']."site_tmplvar_contentvalues` `tvc` ON `sc`.`id` = `tvc`.`contentid`
                LEFT JOIN `".$this->config['table_prefix']."site_tmplvars` `tv` ON `tv`.`id` = `tvc`.`tmplvarid`
                WHERE `sc`.`id` IN (".implode(',',$this->ids_arr).")
                ";
                
                $sql .= $this->prepareFiltersSQL($key, $val);
                
                $sql .= "
                ORDER BY `sc`.`id` ASC
                ";
                
                $stmt = $this->modx->prepare($sql);
                $this->ids_arr = array();
                if ($stmt && $stmt->execute()){
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($this->ids_arr,$row['res_id']);
                    }
                }
                
            }
            
        }
        
    }
    
    
    /**
     * Формирует строку SELECT с полями для выборки данных товара
     *
     */
    function getSelectFields($tab_name='sc'){
        
        $out = '';
        
        $fields_arr = array(
            'id',
            'type',
            'contentType',
            'pagetitle',
            'longtitle',
            'description',
            'alias',
            'link_attributes',
            'createdon',
            'published',
            'pub_date',
            'unpub_date',
            'parent',
            'isfolder',
            'introtext',
            'template',
            'menuindex',
            'createdon',
            'editedon',
            'deleted',
            'publishedon',
            'menutitle',
            'hidemenu',
            'class_key',
            'context_key',
            'content_type',
            'uri'
        );
        
        if(!$this->config['includeContent']){
            $out = "`{$tab_name}`.`".implode("`, `{$tab_name}`.`",$fields_arr)."`";
        }else{
            $out = "`{$tab_name}`.*";
        }
        
        return $out;
        
    }
    
    
    /**
     * Вытаскивает все данные товаров
     *
     */
    function getProductsData(){
        
        $limit_str = $this->config['limit'] ? "LIMIT ".$this->config['offset'].', '.$this->config['limit'].' ' : '';
        
        //includeContent
        $orderby_str = '';
        $select_str = $this->getSelectFields('sc');
        $subitemcount_str = '';
        if($this->config['addSubItemCount']){
            $subitemcount_str = ", (SELECT COUNT(`id`) FROM `".$this->config['table_prefix']."site_content` WHERE `parent` = `sc`.`id` AND `published`=1 AND `deleted`=0) AS `subitemcount` ";
        }
        
        //Сортировка по TV
        if($this->config['sortbyTV']){
            $tv_stmt = $this->modx->prepare("SELECT `id` FROM `".$this->config['table_prefix']."site_tmplvars` WHERE `name` = '".$this->config['sortbyTV']."'");
            $tv_id = 0;
            if($tv_stmt->execute()){
                $tv_id = $tv_stmt->fetchColumn();
                $tv_stmt->closeCursor();
            }
            $select_str .= ", (SELECT `value` FROM `".$this->config['table_prefix']."site_tmplvar_contentvalues` WHERE `tmplvarid` = '{$tv_id}' AND `contentid` = `sc`.`id` LIMIT 1) AS `".$this->config['sortbyTV']."`";
            $orderby_str = "ORDER BY ";
            if($this->config['sortbyTVType'] == 'integer'){
                //$orderby_str .= "CAST(`".$this->config['sortbyTV']."` AS SIGNED)";
                $orderby_str .= "ABS(`".$this->config['sortbyTV']."`)";
            }else{
                $orderby_str .= $this->config['sortbyTV'];
            }
            $orderby_str .= ' '.$this->config['sortdirTV'];
        //Сортировка по списку ID ресурсов
        }else if($this->config['resources'] && $this->config['orderbyResources']){
            
            $orderby_str = 'ORDER BY FIND_IN_SET(id,"'.$this->config['resources'].'")';
            
        //Сортировка по полю ресурсов
        }else{
            if(count($this->config['orderby'])>0){
                $orderby_str = 'ORDER BY ';
                if(strtoupper(current(array_keys($this->config['orderby']))) == 'RAND()'){
                    $orderby_str .= ' RAND() ';
                }else{
                    foreach($this->config['orderby'] as $k => $v){
                        $orderby_str .= '`sc`.'.$this->modx->escape($k)." {$v}, ";
                    }
                    $orderby_str = substr($orderby_str,0,-2);
                }
            }
        }
        $sql = "
        SELECT {$select_str}{$subitemcount_str} FROM `".$this->config['table_prefix']."site_content` `sc`
        WHERE `sc`.`id` IN (".implode(',',$this->ids_arr).")
        {$orderby_str}
        {$limit_str}
        ";
        
        $this->ids_arr = array();
        $stmt = $this->modx->prepare($sql);
        
        //var_dump($sql);
        
        if ($stmt && $stmt->execute()) {
            //$total = $stmt->rowCount();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $this->products[$row['id']] = $row;
                array_push($this->ids_arr,$row['id']);
            }
            $stmt->closeCursor();
        }else{
            //if($this->config['debug'])
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'getProducts SQL error: '.print_r($stmt->errorInfo(),true));
        }
        
        if($this->config['debug']) $this->modx->log(modX::LOG_LEVEL_ERROR, 'getProducts: total = '.count($this->ids_arr).' - '.$sql);
        
    }
    
    
    /**
     * Возвращает массив ID родителей
     */
    function getParents(){
        
        $parent_ids = explode(',',$this->config['parents']);
        $pcchildren = $this->config['depth'] ? $this->modx->getChildIds($parent_ids[0], $this->config['depth'], array('context' => $this->config['context'])) : array();
        $pcchildren = array_merge($pcchildren,$parent_ids);
        
        return $pcchildren;
        
    }
    
    /**
     *
     * 
     */
    function appendTVs(){
        
        if($this->config['includeTVs'] && count($this->config['includeTVList_arr'])>0){
            $tv_sql = "
            SELECT DISTINCT `tv`.`id`, `tv`.`name`, `tvc`.`contentid`
            ";
            
            if($this->config['processTVs']){
                $tv_sql .= ", `tv`.`display`, `tv`.`elements`, `tv`.`output_properties`, `tv`.`type`";
            }
            
            $tv_sql .= "
            , IF(`tvc`.`value`!='',`tvc`.`value`,`tv`.`default_text`) as `value`
            FROM `".$this->config['table_prefix']."site_tmplvars` AS `tv`
            LEFT JOIN `".$this->config['table_prefix']."site_tmplvar_contentvalues` AS `tvc` ON `tvc`.`tmplvarid`=`tv`.`id`
            LEFT JOIN `".$this->config['table_prefix']."site_tmplvar_access` AS `tva` ON `tva`.`tmplvarid`=`tv`.`id`
            WHERE `tv`.`name` IN ('".implode("','",$this->config['includeTVList_arr'])."')
            AND `tvc`.`contentid` IN (".implode(',',$this->ids_arr).") AND (ISNULL(`tva`.`documentgroup`))
            ORDER BY `tv`.`rank`
            ";
            $stmt = $this->modx->prepare($tv_sql);
            if ($stmt && $stmt->execute()) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    if(!isset($this->products[$row['contentid']])) $this->products[$row['contentid']] = array();
                    
                    if($this->config['processTVs'] && (empty($this->config['processTVList']) || in_array($row['name'],$this->config['processTVList']))){
                        $this->products[$row['contentid']]['tv.'.$row['name']] = $this->processTV($row,$row['contentid'],$row['value']);
                    }else{
                        $this->products[$row['contentid']]['tv.'.$row['name']] = $row['value'];
                    }
                }
                $stmt->closeCursor();
            }
            
            //Вытаскиваем все значения по умолчанию и заполняем "дыры" значениями
            $tv_sql = "
            SELECT `tv`.`name`, `tv`.`default_text`
            ";
            
            if($this->config['processTVs']){
                $tv_sql .= ", `tv`.`display`, `tv`.`elements`, `tv`.`output_properties`, `tv`.`type`";
            }
            
            $tv_sql .= "
            FROM `".$this->config['table_prefix']."site_tmplvars` AS `tv`
            LEFT JOIN `".$this->config['table_prefix']."site_tmplvar_templates` AS `tvt` ON `tv`.`id` = `tvt`.`tmplvarid`
            LEFT JOIN `".$this->config['table_prefix']."site_content` AS `ct` ON `ct`.`template` = `tvt`.`templateid`
            WHERE `tv`.`name` IN ('".implode("','",$this->config['includeTVList_arr'])."')
            AND `ct`.`id` IN (".implode(',',$this->ids_arr).")
            ";
            //Справка: $this->ids_arr к этому моменту уже обрезан по LIMIT (ф-я getProductsData())
            
            
            $tv_default = array();
            $stmt = $this->modx->prepare($tv_sql);
            if ($stmt && $stmt->execute()) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $tv_default[$row['name']] = $row;//$row['default_text'];
                }
            }
            $stmt->closeCursor();
            
            foreach($this->products as &$product){
                foreach($this->config['includeTVList_arr'] as $tv_name){
                    if(!isset($product['tv.'.$tv_name])){
                        if(isset($tv_default[$tv_name])){
                            $product['tv.'.$tv_name] = isset($this->config['processTVs']) && in_array($tv_name,$this->config['processTVList']) ? $this->processTV($tv_default[$tv_name],$product['id'],$tv_default[$tv_name]['default_text']) : '';//$tv_default[$tv_name] : '';
                        }else{
                            $tv_default[$tv_name] = '';
                        }
                    }
                }
            }
            
        }
        
    }
    
    
    /**
     * Просчитываение кода TV по типу вывода
     * 
     * @param array $tv_data
     * @param string $contentid
     * @param string $value
     */
    function processTV($tv_data=array(),$contentid='',$value=''){
        
        $output = '';
        if(is_array($tv_data) && count($tv_data)>0){
            
            $templateVar = $this->modx->newObject('modTemplateVar');
            $templateVar->fromArray(array(
                'name' => $tv_data['name'],
                'caption' => $tv_data['type'],
                'type' => '',
                'display' => $tv_data['display'],
                'elements' => $tv_data['elements'],
                'output_properties' => unserialize($tv_data['output_properties']),
                'default_text' => '',
                'value' => $value,
                'resourceId' => $contentid
            ));
            
            $output = $templateVar->renderOutput($contentid);
            
        }
        
        return $output;
        
    }
    
    /**
     * Собирает TV для массива ID документов
     * 
     * 
     */
    function getTVs($ids_arr, $tvnames_arr, $prefix='tv.'){
        
        $tvs_output = array();
        
        $tv_sql = "
        SELECT DISTINCT `tv`.`id`, `tv`.`name`, `tvc`.`contentid`,
        ";
        
        //`tv`.`default_text`
        $tv_sql .= "
        IF(`tvc`.`value`!='',`tvc`.`value`,'') as `value`
        FROM `".$this->config['table_prefix']."site_tmplvars` AS `tv`
        LEFT JOIN `".$this->config['table_prefix']."site_tmplvar_contentvalues` AS `tvc` ON `tvc`.`tmplvarid`=`tv`.`id`
        LEFT JOIN `".$this->config['table_prefix']."site_tmplvar_access` AS `tva` ON `tva`.`tmplvarid`=`tv`.`id`
        WHERE `tv`.`name` IN ('".implode("','",$tvnames_arr)."')
        AND `tvc`.`contentid` IN (".implode(',',$ids_arr).") AND (ISNULL(`tva`.`documentgroup`))
        ORDER BY `tv`.`rank`
        ";
        
        $stmt = $this->modx->prepare($tv_sql);
        if ($stmt && $stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                if(!isset($tvs_output[$row['contentid']])) $tvs_output[$row['contentid']] = array();
                $tvs_output[$row['contentid']][$prefix.$row['name']] = $row['value'];
            }
            $stmt->closeCursor();
        }
        
        //Вытаскиваем все значения по умолчанию и заполняем "дыры" значениями
        $tv_sql = "
        SELECT `tv`.`name`, `tv`.`default_text`
        FROM `".$this->config['table_prefix']."site_tmplvars` AS `tv`
        WHERE `tv`.`name` IN ('".implode("','",$tvnames_arr)."')
        ";
        $tv_default = array();
        $stmt = $this->modx->prepare($tv_sql);
        
        if ($stmt && $stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $tv_default[$prefix.$row['name']] = $row['default_text'];
            }
        }
        $stmt->closeCursor();
        
        foreach($tvs_output as &$product){
            foreach($tvnames_arr as $tv_name){
                if(!isset($product[$prefix.$tv_name])){
                    $product[$prefix.$tv_name] = isset($tv_default[$prefix.$tv_name]) ? $tv_default[$prefix.$tv_name] : '';
                }
            }
        }
        
        return $tvs_output;
    
    }
    
    
    /**
     * Вытаскивает данные от родителей
     * 
     */
    function appendFromParents(){
        
        if($this->config['fromParentList']){
            
            $sql = "
            SELECT * FROM `".$this->config['table_prefix']."site_content` `sc`
            WHERE `sc`.`id` IN (".implode(',',$this->parent_ids).")
            ";
            $stmt = $this->modx->prepare($sql);
            if($stmt && $stmt->execute()){
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    foreach(array_intersect(array_keys($row),$this->config['fromParentList_arr']) as $k => $v){
                        $this->parents_data[$row['id']]['parent.'.$v] = $row[$v];
                    }
                    if(!isset($fromParentListTV_arr)) $fromParentListTV_arr = array_diff($this->config['fromParentList_arr'],array_keys($row));
                }
                $stmt->closeCursor();
            }
            
            if(isset($fromParentListTV_arr) && count($fromParentListTV_arr)>0){
                $tv_sql = "
                SELECT DISTINCT `tv`.`id`, `tv`.`name`, `tvc`.`contentid`, IF(`tvc`.`value`!='',`tvc`.`value`,`tv`.`default_text`) as `value`
                FROM `".$this->config['table_prefix']."site_tmplvars` AS `tv`
                LEFT JOIN `".$this->config['table_prefix']."site_tmplvar_contentvalues` AS `tvc` ON `tvc`.`tmplvarid`=`tv`.`id`
                LEFT JOIN `".$this->config['table_prefix']."site_tmplvar_access` AS `tva` ON `tva`.`tmplvarid`=`tv`.`id`
                WHERE `tv`.`name` IN ('".implode("','",$fromParentListTV_arr)."')
                AND `tvc`.`contentid` IN (".implode(',',$this->parent_ids).") AND (ISNULL(`tva`.`documentgroup`))
                ORDER BY `tv`.`rank`
                ";
                $stmt = $this->modx->prepare($tv_sql);
                if ($stmt && $stmt->execute()) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        if(!isset($this->parents_data[$row['contentid']])) $this->parents_data[$row['contentid']] = array();
                        $this->parents_data[$row['contentid']]['parent.'.$row['name']] = $row['value'];
                    }
                    $stmt->closeCursor();
                }
            }
            
        }
        
    }
    
    /**
     * 
     * 
     */
    function isActive($id){
        
        $out = in_array($id,array_merge($this->config['all_parents'],array($this->modx->resource->get('id'))));
        
        return $out;
        
    }
    
    /**
     * Создаёт HTML код по шаблону
     *
     */
    function getHTMLOutput(){
        
        $output = '';
        
        $chunk = $this->getChunk($this->config['tpl']);
        
        $idx = 0;
        foreach($this->products as $product){
            $properties = array_merge(
                array(
                    'idx' => $idx,
                    'num' => $idx + 1,
                    'first' => $idx==0 ? 1 : 0,
                    'last' => $idx+1==count($this->products) ? 1 : 0,
                    'odd' => $idx%2==0 ? 1 : 0,
                    'activeClass' => $this->isActive($product['id']) ? $this->config['activeClass'] : '',
                    'active' => $this->isActive($product['id']) ? 1 : 0,
                    'activeParent_snippet' => ''
                ),
                $product
            );
            
            $properties['classnames'] = ($properties['first'] ? 'first ' : '')
            .($properties['last'] ? 'last ' : '')
            .($properties['odd'] ? 'odd ' : '')
            .($properties['activeClass'] ? $this->config['activeClass'] : '');
            
            $properties['classnames'] = trim($properties['classnames']);
            
            if(/*$properties['activeParent'] && */ $properties['active'] && $this->config['activeParentSnippet']){
                
                $this->config['activeParentSnippet'] = str_replace('[[+id]]',$properties['id'],$this->config['activeParentSnippet']);
                $tag_end = strpos($this->config['activeParentSnippet'],"?");
                $tagName = $tag_end!==false ? substr($this->config['activeParentSnippet'],0,$tag_end) : $this->config['activeParentSnippet'];
                $tagPropString = $tag_end!==false ? substr($this->config['activeParentSnippet'],(0-(strlen($this->config['activeParentSnippet']) - $tag_end - 1))) : '';
                
                if ($element= $this->modx->parser->getElement('modSnippet', $tagName)) {
                    $element->set('name', $tagName);
                    //$element->setTag($outerTag);
                    $element->setCacheable(false);
                    $elementOutput = $element->process($tagPropString);
                }
                
                if($elementOutput) $properties['activeParent_snippet'] = $elementOutput;
                else $properties['active'] = 0;
                
            }
            
            if(isset($this->parents_data[$product['parent']])){
                $properties = array_merge($product,$this->parents_data[$product['parent']]);
            }
            $output .= $this->parseTpl($chunk,$properties)."\n";//$this->modx->getChunk($this->config['tpl'], $properties)."\n";
            $idx++;
        }
        
        return $output;
        
    }
    
    

}

