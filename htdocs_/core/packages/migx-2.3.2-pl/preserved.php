<?php return array (
  '2dbbf90d1aa56712fa34e127c2681ae8' => 
  array (
    'criteria' => 
    array (
      'name' => 'migx',
    ),
    'object' => 
    array (
      'name' => 'migx',
      'path' => '{core_path}components/migx/',
      'assets_path' => '',
    ),
  ),
  'f9fdee8a07c614cbf612ffa333fb50bc' => 
  array (
    'criteria' => 
    array (
      'name' => 'MIGX',
    ),
    'object' => 
    array (
      'id' => 7,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'MIGX',
      'description' => '',
      'editor_type' => 0,
      'category' => 1,
      'cache_type' => 0,
      'plugincode' => '$corePath = $modx->getOption(\'migx.core_path\',null,$modx->getOption(\'core_path\').\'components/migx/\');
$assetsUrl = $modx->getOption(\'migx.assets_url\', null, $modx->getOption(\'assets_url\') . \'components/migx/\');
switch ($modx->event->name) {
    case \'OnTVInputRenderList\':
        $modx->event->output($corePath.\'elements/tv/input/\');
        break;
    case \'OnTVInputPropertiesList\':
        $modx->event->output($corePath.\'elements/tv/inputoptions/\');
        break;

        case \'OnDocFormPrerender\':
        $modx->controller->addCss($assetsUrl.\'css/mgr.css\');
        break; 
 
    /*          
    case \'OnTVOutputRenderList\':
        $modx->event->output($corePath.\'elements/tv/output/\');
        break;
    case \'OnTVOutputRenderPropertiesList\':
        $modx->event->output($corePath.\'elements/tv/properties/\');
        break;
    
    case \'OnDocFormPrerender\':
        $assetsUrl = $modx->getOption(\'colorpicker.assets_url\',null,$modx->getOption(\'assets_url\').\'components/colorpicker/\'); 
        $modx->regClientStartupHTMLBlock(\'<script type="text/javascript">
        Ext.onReady(function() {
            
        });
        </script>\');
        $modx->regClientStartupScript($assetsUrl.\'sources/ColorPicker.js\');
        $modx->regClientStartupScript($assetsUrl.\'sources/ColorMenu.js\');
        $modx->regClientStartupScript($assetsUrl.\'sources/ColorPickerField.js\');		
        $modx->regClientCSS($assetsUrl.\'resources/css/colorpicker.css\');
        break;
     */
}
return;',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'disabled' => 0,
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '$corePath = $modx->getOption(\'migx.core_path\',null,$modx->getOption(\'core_path\').\'components/migx/\');
$assetsUrl = $modx->getOption(\'migx.assets_url\', null, $modx->getOption(\'assets_url\') . \'components/migx/\');
switch ($modx->event->name) {
    case \'OnTVInputRenderList\':
        $modx->event->output($corePath.\'elements/tv/input/\');
        break;
    case \'OnTVInputPropertiesList\':
        $modx->event->output($corePath.\'elements/tv/inputoptions/\');
        break;

        case \'OnDocFormPrerender\':
        $modx->controller->addCss($assetsUrl.\'css/mgr.css\');
        break; 
 
    /*          
    case \'OnTVOutputRenderList\':
        $modx->event->output($corePath.\'elements/tv/output/\');
        break;
    case \'OnTVOutputRenderPropertiesList\':
        $modx->event->output($corePath.\'elements/tv/properties/\');
        break;
    
    case \'OnDocFormPrerender\':
        $assetsUrl = $modx->getOption(\'colorpicker.assets_url\',null,$modx->getOption(\'assets_url\').\'components/colorpicker/\'); 
        $modx->regClientStartupHTMLBlock(\'<script type="text/javascript">
        Ext.onReady(function() {
            
        });
        </script>\');
        $modx->regClientStartupScript($assetsUrl.\'sources/ColorPicker.js\');
        $modx->regClientStartupScript($assetsUrl.\'sources/ColorMenu.js\');
        $modx->regClientStartupScript($assetsUrl.\'sources/ColorPickerField.js\');		
        $modx->regClientCSS($assetsUrl.\'resources/css/colorpicker.css\');
        break;
     */
}
return;',
    ),
  ),
  'e3b598068d51e2f9cb9372d1ea6bd291' => 
  array (
    'criteria' => 
    array (
      'pluginid' => 7,
      'event' => 'OnDocFormPrerender',
    ),
    'object' => 
    array (
      'pluginid' => 7,
      'event' => 'OnDocFormPrerender',
      'priority' => 0,
      'propertyset' => 0,
    ),
  ),
  '2373ca688dc29d07916c36acf66b5620' => 
  array (
    'criteria' => 
    array (
      'pluginid' => 7,
      'event' => 'OnTVInputPropertiesList',
    ),
    'object' => 
    array (
      'pluginid' => 7,
      'event' => 'OnTVInputPropertiesList',
      'priority' => 0,
      'propertyset' => 0,
    ),
  ),
  'c0a96199588141e7606f269e77e9741c' => 
  array (
    'criteria' => 
    array (
      'pluginid' => 7,
      'event' => 'OnTVInputRenderList',
    ),
    'object' => 
    array (
      'pluginid' => 7,
      'event' => 'OnTVInputRenderList',
      'priority' => 0,
      'propertyset' => 0,
    ),
  ),
  'eff2e54681f133f9ba4b183f2f349780' => 
  array (
    'criteria' => 
    array (
      'category' => 'MIGX',
    ),
    'object' => 
    array (
      'id' => 6,
      'parent' => 0,
      'category' => 'MIGX',
    ),
  ),
  'a35440d687a40c25bf98c13b29605764' => 
  array (
    'criteria' => 
    array (
      'name' => 'getImageList',
    ),
    'object' => 
    array (
      'id' => 10,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'getImageList',
      'description' => '',
      'editor_type' => 0,
      'category' => 6,
      'cache_type' => 0,
      'snippet' => '/**
 * getImageList
 *
 * Copyright 2009-2011 by Bruno Perner <b.perner@gmx.de>
 *
 * getImageList is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * getImageList is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * getImageList; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package migx
 */
/**
 * getImageList
 *
 * display Items from outputvalue of TV with custom-TV-input-type MIGX or from other JSON-string for MODx Revolution 
 *
 * @version 1.4
 * @author Bruno Perner <b.perner@gmx.de>
 * @copyright Copyright &copy; 2009-2011
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License
 * version 2 or (at your option) any later version.
 * @package migx
 */

/*example: <ul>[[!getImageList? &tvname=`myTV`&tpl=`@CODE:<li>[[+idx]]<img src="[[+imageURL]]"/><p>[[+imageAlt]]</p></li>`]]</ul>*/
/* get default properties */


$tvname = $modx->getOption(\'tvname\', $scriptProperties, \'\');
$tpl = $modx->getOption(\'tpl\', $scriptProperties, \'\');
$limit = $modx->getOption(\'limit\', $scriptProperties, \'0\');
$offset = $modx->getOption(\'offset\', $scriptProperties, 0);
$totalVar = $modx->getOption(\'totalVar\', $scriptProperties, \'total\');
$randomize = $modx->getOption(\'randomize\', $scriptProperties, false);
$preselectLimit = $modx->getOption(\'preselectLimit\', $scriptProperties, 0); // when random preselect important images
$where = $modx->getOption(\'where\', $scriptProperties, \'\');
$where = !empty($where) ? $modx->fromJSON($where) : array();
$sort = $modx->getOption(\'sort\', $scriptProperties, \'\');
$sort = !empty($sort) ? $modx->fromJSON($sort) : array();
$toSeparatePlaceholders = $modx->getOption(\'toSeparatePlaceholders\', $scriptProperties, false);
$toPlaceholder = $modx->getOption(\'toPlaceholder\', $scriptProperties, false);
$outputSeparator = $modx->getOption(\'outputSeparator\', $scriptProperties, \'\');
$placeholdersKeyField = $modx->getOption(\'placeholdersKeyField\', $scriptProperties, \'MIGX_id\');
$toJsonPlaceholder = $modx->getOption(\'toJsonPlaceholder\', $scriptProperties, false);
$jsonVarKey = $modx->getOption(\'jsonVarKey\', $scriptProperties, \'migx_outputvalue\');
$outputvalue = $modx->getOption(\'value\', $scriptProperties, \'\');
$outputvalue = isset($_REQUEST[$jsonVarKey]) ? $_REQUEST[$jsonVarKey] : $outputvalue;
$docidVarKey = $modx->getOption(\'docidVarKey\', $scriptProperties, \'migx_docid\');
$docid = $modx->getOption(\'docid\', $scriptProperties, (isset($modx->resource) ? $modx->resource->get(\'id\') : 1));
$docid = isset($_REQUEST[$docidVarKey]) ? $_REQUEST[$docidVarKey] : $docid;
$processTVs = $modx->getOption(\'processTVs\', $scriptProperties, \'1\');

$base_path = $modx->getOption(\'base_path\', null, MODX_BASE_PATH);
$base_url = $modx->getOption(\'base_url\', null, MODX_BASE_URL);

$migx = $modx->getService(\'migx\', \'Migx\', $modx->getOption(\'migx.core_path\', null, $modx->getOption(\'core_path\') . \'components/migx/\') . \'model/migx/\', $scriptProperties);
if (!($migx instanceof Migx)) return \'\';
$migx->working_context = $modx->resource->get(\'context_key\');


if (!empty($tvname)) {
    if ($tv = $modx->getObject(\'modTemplateVar\', array(\'name\' => $tvname))) {

        /*
        *   get inputProperties
        */


        $properties = $tv->get(\'input_properties\');
        $properties = isset($properties[\'formtabs\']) ? $properties : $tv->getProperties();

        $migx->config[\'configs\'] = $properties[\'configs\'];
        $migx->loadConfigs();
        // get tabs from file or migx-config-table
        $formtabs = $migx->getTabs();
        if (empty($formtabs)) {
            //try to get formtabs and its fields from properties
            $formtabs = $modx->fromJSON($properties[\'formtabs\']);
        }

        if (!empty($properties[\'basePath\'])) {
            if ($properties[\'autoResourceFolders\'] == \'true\') {
                $scriptProperties[\'base_path\'] = $base_path . $properties[\'basePath\'] . $docid . \'/\';
                $scriptProperties[\'base_url\'] = $base_url . $properties[\'basePath\'] . $docid . \'/\';
            } else {
                $scriptProperties[\'base_path\'] = $base_path . $properties[\'base_path\'];
                $scriptProperties[\'base_url\'] = $base_url . $properties[\'basePath\'];
            }
        }
        if ($jsonVarKey == \'migx_outputvalue\' && !empty($properties[\'jsonvarkey\'])) {
            $jsonVarKey = $properties[\'jsonvarkey\'];
            $outputvalue = isset($_REQUEST[$jsonVarKey]) ? $_REQUEST[$jsonVarKey] : $outputvalue;
        }
        $outputvalue = empty($outputvalue) ? $tv->renderOutput($docid) : $outputvalue;
        /*
        *   get inputTvs 
        */
        $inputTvs = array();
        if (is_array($formtabs)) {

            //multiple different Forms
            // Note: use same field-names and inputTVs in all forms
            $inputTvs = $migx->extractInputTvs($formtabs);
        }

    }
    $migx->source = $tv->getSource($migx->working_context, false);
}

if (empty($outputvalue)) {
    return \'\';
}

//echo $outputvalue.\'<br/><br/>\';


$items = $modx->fromJSON($outputvalue);
$modx->setPlaceholder($totalVar, count($items));

// where filter
if (is_array($where) && count($where) > 0) {
    $items = $migx->filterItems($where, $items);
}

// sort items
if (is_array($sort) && count($sort) > 0) {
    $items = $migx->sortDbResult($items, $sort);
}


if (count($items) > 0) {
    $items = $offset > 0 ? array_slice($items, $offset) : $items;
    $count = count($items);
    $limit = $limit == 0 || $limit > $count ? $count : $limit;
    $preselectLimit = $preselectLimit > $count ? $count : $preselectLimit;
    //preselect important items
    $preitems = array();
    if ($randomize && $preselectLimit > 0) {
        for ($i = 0; $i < $preselectLimit; $i++) {
            $preitems[] = $items[$i];
            unset($items[$i]);
        }
        $limit = $limit - count($preitems);
    }

    //shuffle items
    if ($randomize) {
        shuffle($items);
    }

    //limit items
    $tempitems = array();
    for ($i = 0; $i < $limit; $i++) {
        $tempitems[] = $items[$i];
    }
    $items = $tempitems;

    //add preselected items and schuffle again
    if ($randomize && $preselectLimit > 0) {
        $items = array_merge($preitems, $items);
        shuffle($items);
    }

    $properties = array();
    foreach ($scriptProperties as $property => $value) {
        $properties[\'property.\' . $property] = $value;
    }

    $idx = 0;
    $output = array();
    foreach ($items as $key => $item) {

        $fields = array();
        foreach ($item as $field => $value) {
            $value = is_array($value) ? implode(\'||\', $value) : $value; //handle arrays (checkboxes, multiselects)
            if ($processTVs && isset($inputTvs[$field])) {
                if ($tv = $modx->getObject(\'modTemplateVar\', array(\'name\' => $inputTvs[$field][\'inputTV\']))) {

                } else {
                    $tv = $modx->newObject(\'modTemplateVar\');
                    $tv->set(\'type\',$inputTvs[$field][\'inputTVtype\']);
                }
                $inputTV = $inputTvs[$field];
  
                $mTypes = $modx->getOption(\'manipulatable_url_tv_output_types\', null, \'image,file\');
                //don\'t manipulate any urls here
                $modx->setOption(\'manipulatable_url_tv_output_types\', \'\');
                $tv->set(\'default_text\', $value);
                $value = $tv->renderOutput($docid);
                //set option back
                $modx->setOption(\'manipulatable_url_tv_output_types\', $mTypes);
                //now manipulate urls
                if ($mediasource = $migx->getFieldSource($inputTV, $tv)) {
                     $mTypes = explode(\',\', $mTypes);
                    if (!empty($value) && in_array($tv->get(\'type\'), $mTypes)) {
                        //$value = $mediasource->prepareOutputUrl($value);
                        $value = str_replace(\'/./\', \'/\', $mediasource->prepareOutputUrl($value));
                    }
                }

            }
            $fields[$field] = $value;

        }
        if ($toJsonPlaceholder) {
            $output[] = $fields;
        } else {
            $fields[\'_alt\'] = $idx % 2;
            $idx++;
            $fields[\'_first\'] = $idx == 1 ? true : \'\';
            $fields[\'_last\'] = $idx == $limit ? true : \'\';
            $fields[\'idx\'] = $idx;
            $rowtpl = $tpl;
            //get changing tpls from field
            if (substr($tpl, 0, 7) == "@FIELD:") {
                $tplField = substr($tpl, 7);
                $rowtpl = $fields[$tplField];
            }

            if (!isset($template[$rowtpl])) {
                if (substr($rowtpl, 0, 6) == "@FILE:") {
                    $template[$rowtpl] = file_get_contents($modx->config[\'base_path\'] . substr($rowtpl, 6));
                } elseif (substr($rowtpl, 0, 6) == "@CODE:") {
                    $template[$rowtpl] = substr($tpl, 6);
                } elseif ($chunk = $modx->getObject(\'modChunk\', array(\'name\' => $rowtpl), true)) {
                    $template[$rowtpl] = $chunk->getContent();
                } else {
                    $template[$rowtpl] = false;
                }
            }

            $fields = array_merge($fields, $properties);

            if ($template[$rowtpl]) {
                $chunk = $modx->newObject(\'modChunk\');
                $chunk->setCacheable(false);
                $chunk->setContent($template[$rowtpl]);
                if (!empty($placeholdersKeyField) && isset($fields[$placeholdersKeyField])) {
                    $output[$fields[$placeholdersKeyField]] = $chunk->process($fields);
                } else {
                    $output[] = $chunk->process($fields);
                }
            } else {
                if (!empty($placeholdersKeyField)) {
                    $output[$fields[$placeholdersKeyField]] = \'<pre>\' . print_r($fields, 1) . \'</pre>\';
                } else {
                    $output[] = \'<pre>\' . print_r($fields, 1) . \'</pre>\';
                }
            }
        }


    }
}

if ($toJsonPlaceholder) {
    $modx->setPlaceholder($toJsonPlaceholder, $modx->toJson($output));
    return \'\';
}

if (!empty($toSeparatePlaceholders)) {
    $modx->toPlaceholders($output, $toSeparatePlaceholders);
    return \'\';
}
/*
if (!empty($outerTpl))
$o = parseTpl($outerTpl, array(\'output\'=>implode($outputSeparator, $output)));
else 
*/
if (is_array($output)) {
    $o = implode($outputSeparator, $output);
} else {
    $o = $output;
}

if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $o);
    return \'\';
}

return $o;',
      'locked' => 0,
      'properties' => NULL,
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '/**
 * getImageList
 *
 * Copyright 2009-2011 by Bruno Perner <b.perner@gmx.de>
 *
 * getImageList is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * getImageList is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * getImageList; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package migx
 */
/**
 * getImageList
 *
 * display Items from outputvalue of TV with custom-TV-input-type MIGX or from other JSON-string for MODx Revolution 
 *
 * @version 1.4
 * @author Bruno Perner <b.perner@gmx.de>
 * @copyright Copyright &copy; 2009-2011
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License
 * version 2 or (at your option) any later version.
 * @package migx
 */

/*example: <ul>[[!getImageList? &tvname=`myTV`&tpl=`@CODE:<li>[[+idx]]<img src="[[+imageURL]]"/><p>[[+imageAlt]]</p></li>`]]</ul>*/
/* get default properties */


$tvname = $modx->getOption(\'tvname\', $scriptProperties, \'\');
$tpl = $modx->getOption(\'tpl\', $scriptProperties, \'\');
$limit = $modx->getOption(\'limit\', $scriptProperties, \'0\');
$offset = $modx->getOption(\'offset\', $scriptProperties, 0);
$totalVar = $modx->getOption(\'totalVar\', $scriptProperties, \'total\');
$randomize = $modx->getOption(\'randomize\', $scriptProperties, false);
$preselectLimit = $modx->getOption(\'preselectLimit\', $scriptProperties, 0); // when random preselect important images
$where = $modx->getOption(\'where\', $scriptProperties, \'\');
$where = !empty($where) ? $modx->fromJSON($where) : array();
$sort = $modx->getOption(\'sort\', $scriptProperties, \'\');
$sort = !empty($sort) ? $modx->fromJSON($sort) : array();
$toSeparatePlaceholders = $modx->getOption(\'toSeparatePlaceholders\', $scriptProperties, false);
$toPlaceholder = $modx->getOption(\'toPlaceholder\', $scriptProperties, false);
$outputSeparator = $modx->getOption(\'outputSeparator\', $scriptProperties, \'\');
$placeholdersKeyField = $modx->getOption(\'placeholdersKeyField\', $scriptProperties, \'MIGX_id\');
$toJsonPlaceholder = $modx->getOption(\'toJsonPlaceholder\', $scriptProperties, false);
$jsonVarKey = $modx->getOption(\'jsonVarKey\', $scriptProperties, \'migx_outputvalue\');
$outputvalue = $modx->getOption(\'value\', $scriptProperties, \'\');
$outputvalue = isset($_REQUEST[$jsonVarKey]) ? $_REQUEST[$jsonVarKey] : $outputvalue;
$docidVarKey = $modx->getOption(\'docidVarKey\', $scriptProperties, \'migx_docid\');
$docid = $modx->getOption(\'docid\', $scriptProperties, (isset($modx->resource) ? $modx->resource->get(\'id\') : 1));
$docid = isset($_REQUEST[$docidVarKey]) ? $_REQUEST[$docidVarKey] : $docid;
$processTVs = $modx->getOption(\'processTVs\', $scriptProperties, \'1\');

$base_path = $modx->getOption(\'base_path\', null, MODX_BASE_PATH);
$base_url = $modx->getOption(\'base_url\', null, MODX_BASE_URL);

$migx = $modx->getService(\'migx\', \'Migx\', $modx->getOption(\'migx.core_path\', null, $modx->getOption(\'core_path\') . \'components/migx/\') . \'model/migx/\', $scriptProperties);
if (!($migx instanceof Migx)) return \'\';
$migx->working_context = $modx->resource->get(\'context_key\');


if (!empty($tvname)) {
    if ($tv = $modx->getObject(\'modTemplateVar\', array(\'name\' => $tvname))) {

        /*
        *   get inputProperties
        */


        $properties = $tv->get(\'input_properties\');
        $properties = isset($properties[\'formtabs\']) ? $properties : $tv->getProperties();

        $migx->config[\'configs\'] = $properties[\'configs\'];
        $migx->loadConfigs();
        // get tabs from file or migx-config-table
        $formtabs = $migx->getTabs();
        if (empty($formtabs)) {
            //try to get formtabs and its fields from properties
            $formtabs = $modx->fromJSON($properties[\'formtabs\']);
        }

        if (!empty($properties[\'basePath\'])) {
            if ($properties[\'autoResourceFolders\'] == \'true\') {
                $scriptProperties[\'base_path\'] = $base_path . $properties[\'basePath\'] . $docid . \'/\';
                $scriptProperties[\'base_url\'] = $base_url . $properties[\'basePath\'] . $docid . \'/\';
            } else {
                $scriptProperties[\'base_path\'] = $base_path . $properties[\'base_path\'];
                $scriptProperties[\'base_url\'] = $base_url . $properties[\'basePath\'];
            }
        }
        if ($jsonVarKey == \'migx_outputvalue\' && !empty($properties[\'jsonvarkey\'])) {
            $jsonVarKey = $properties[\'jsonvarkey\'];
            $outputvalue = isset($_REQUEST[$jsonVarKey]) ? $_REQUEST[$jsonVarKey] : $outputvalue;
        }
        $outputvalue = empty($outputvalue) ? $tv->renderOutput($docid) : $outputvalue;
        /*
        *   get inputTvs 
        */
        $inputTvs = array();
        if (is_array($formtabs)) {

            //multiple different Forms
            // Note: use same field-names and inputTVs in all forms
            $inputTvs = $migx->extractInputTvs($formtabs);
        }

    }
    $migx->source = $tv->getSource($migx->working_context, false);
}

if (empty($outputvalue)) {
    return \'\';
}

//echo $outputvalue.\'<br/><br/>\';


$items = $modx->fromJSON($outputvalue);
$modx->setPlaceholder($totalVar, count($items));

// where filter
if (is_array($where) && count($where) > 0) {
    $items = $migx->filterItems($where, $items);
}

// sort items
if (is_array($sort) && count($sort) > 0) {
    $items = $migx->sortDbResult($items, $sort);
}


if (count($items) > 0) {
    $items = $offset > 0 ? array_slice($items, $offset) : $items;
    $count = count($items);
    $limit = $limit == 0 || $limit > $count ? $count : $limit;
    $preselectLimit = $preselectLimit > $count ? $count : $preselectLimit;
    //preselect important items
    $preitems = array();
    if ($randomize && $preselectLimit > 0) {
        for ($i = 0; $i < $preselectLimit; $i++) {
            $preitems[] = $items[$i];
            unset($items[$i]);
        }
        $limit = $limit - count($preitems);
    }

    //shuffle items
    if ($randomize) {
        shuffle($items);
    }

    //limit items
    $tempitems = array();
    for ($i = 0; $i < $limit; $i++) {
        $tempitems[] = $items[$i];
    }
    $items = $tempitems;

    //add preselected items and schuffle again
    if ($randomize && $preselectLimit > 0) {
        $items = array_merge($preitems, $items);
        shuffle($items);
    }

    $properties = array();
    foreach ($scriptProperties as $property => $value) {
        $properties[\'property.\' . $property] = $value;
    }

    $idx = 0;
    $output = array();
    foreach ($items as $key => $item) {

        $fields = array();
        foreach ($item as $field => $value) {
            $value = is_array($value) ? implode(\'||\', $value) : $value; //handle arrays (checkboxes, multiselects)
            if ($processTVs && isset($inputTvs[$field])) {
                if ($tv = $modx->getObject(\'modTemplateVar\', array(\'name\' => $inputTvs[$field][\'inputTV\']))) {

                } else {
                    $tv = $modx->newObject(\'modTemplateVar\');
                    $tv->set(\'type\',$inputTvs[$field][\'inputTVtype\']);
                }
                $inputTV = $inputTvs[$field];
  
                $mTypes = $modx->getOption(\'manipulatable_url_tv_output_types\', null, \'image,file\');
                //don\'t manipulate any urls here
                $modx->setOption(\'manipulatable_url_tv_output_types\', \'\');
                $tv->set(\'default_text\', $value);
                $value = $tv->renderOutput($docid);
                //set option back
                $modx->setOption(\'manipulatable_url_tv_output_types\', $mTypes);
                //now manipulate urls
                if ($mediasource = $migx->getFieldSource($inputTV, $tv)) {
                     $mTypes = explode(\',\', $mTypes);
                    if (!empty($value) && in_array($tv->get(\'type\'), $mTypes)) {
                        //$value = $mediasource->prepareOutputUrl($value);
                        $value = str_replace(\'/./\', \'/\', $mediasource->prepareOutputUrl($value));
                    }
                }

            }
            $fields[$field] = $value;

        }
        if ($toJsonPlaceholder) {
            $output[] = $fields;
        } else {
            $fields[\'_alt\'] = $idx % 2;
            $idx++;
            $fields[\'_first\'] = $idx == 1 ? true : \'\';
            $fields[\'_last\'] = $idx == $limit ? true : \'\';
            $fields[\'idx\'] = $idx;
            $rowtpl = $tpl;
            //get changing tpls from field
            if (substr($tpl, 0, 7) == "@FIELD:") {
                $tplField = substr($tpl, 7);
                $rowtpl = $fields[$tplField];
            }

            if (!isset($template[$rowtpl])) {
                if (substr($rowtpl, 0, 6) == "@FILE:") {
                    $template[$rowtpl] = file_get_contents($modx->config[\'base_path\'] . substr($rowtpl, 6));
                } elseif (substr($rowtpl, 0, 6) == "@CODE:") {
                    $template[$rowtpl] = substr($tpl, 6);
                } elseif ($chunk = $modx->getObject(\'modChunk\', array(\'name\' => $rowtpl), true)) {
                    $template[$rowtpl] = $chunk->getContent();
                } else {
                    $template[$rowtpl] = false;
                }
            }

            $fields = array_merge($fields, $properties);

            if ($template[$rowtpl]) {
                $chunk = $modx->newObject(\'modChunk\');
                $chunk->setCacheable(false);
                $chunk->setContent($template[$rowtpl]);
                if (!empty($placeholdersKeyField) && isset($fields[$placeholdersKeyField])) {
                    $output[$fields[$placeholdersKeyField]] = $chunk->process($fields);
                } else {
                    $output[] = $chunk->process($fields);
                }
            } else {
                if (!empty($placeholdersKeyField)) {
                    $output[$fields[$placeholdersKeyField]] = \'<pre>\' . print_r($fields, 1) . \'</pre>\';
                } else {
                    $output[] = \'<pre>\' . print_r($fields, 1) . \'</pre>\';
                }
            }
        }


    }
}

if ($toJsonPlaceholder) {
    $modx->setPlaceholder($toJsonPlaceholder, $modx->toJson($output));
    return \'\';
}

if (!empty($toSeparatePlaceholders)) {
    $modx->toPlaceholders($output, $toSeparatePlaceholders);
    return \'\';
}
/*
if (!empty($outerTpl))
$o = parseTpl($outerTpl, array(\'output\'=>implode($outputSeparator, $output)));
else 
*/
if (is_array($output)) {
    $o = implode($outputSeparator, $output);
} else {
    $o = $output;
}

if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $o);
    return \'\';
}

return $o;',
    ),
  ),
  'b6f4d92906511bde7f399eb86d1e46e7' => 
  array (
    'criteria' => 
    array (
      'name' => 'migxGetRelations',
    ),
    'object' => 
    array (
      'id' => 11,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'migxGetRelations',
      'description' => '',
      'editor_type' => 0,
      'category' => 6,
      'cache_type' => 0,
      'snippet' => '$id = $modx->getOption(\'id\', $scriptProperties, $modx->resource->get(\'id\'));
$toPlaceholder = $modx->getOption(\'toPlaceholder\', $scriptProperties, \'\');
$element = $modx->getOption(\'element\', $scriptProperties, \'getResources\');
$outputSeparator = $modx->getOption(\'outputSeparator\', $scriptProperties, \',\');
$sourceWhere = $modx->getOption(\'sourceWhere\', $scriptProperties, \'\');
$ignoreRelationIfEmpty = $modx->getOption(\'ignoreRelationIfEmpty\', $scriptProperties, false);
$inheritFromParents = $modx->getOption(\'inheritFromParents\', $scriptProperties, false);
$parentIDs = $inheritFromParents ? array_merge(array($id), $modx->getParentIds($id)) : array($id);

$packageName = \'resourcerelations\';

$packagepath = $modx->getOption(\'core_path\') . \'components/\' . $packageName . \'/\';
$modelpath = $packagepath . \'model/\';

$modx->addPackage($packageName, $modelpath, $prefix);
$classname = \'rrResourceRelation\';
$output = \'\';

foreach ($parentIDs as $id) {
    if (!empty($id)) {
        $output = \'\';
                
        $c = $modx->newQuery($classname, array(\'target_id\' => $id, \'published\' => \'1\'));
        $c->select($modx->getSelectColumns($classname, $classname));

        if (!empty($sourceWhere)) {
            $sourceWhere_ar = $modx->fromJson($sourceWhere);
            if (is_array($sourceWhere_ar)) {
                $where = array();
                foreach ($sourceWhere_ar as $key => $value) {
                    $where[\'Source.\' . $key] = $value;
                }
                $joinclass = \'modResource\';
                $joinalias = \'Source\';
                $selectfields = \'id\';
                $selectfields = !empty($selectfields) ? explode(\',\', $selectfields) : null;
                $c->leftjoin($joinclass, $joinalias);
                $c->select($modx->getSelectColumns($joinclass, $joinalias, $joinalias . \'_\', $selectfields));
                $c->where($where);
            }
        }

        //$c->prepare(); echo $c->toSql();
        if ($c->prepare() && $c->stmt->execute()) {
            $collection = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        foreach ($collection as $row) {
            $ids[] = $row[\'source_id\'];
        }
        $output = implode($outputSeparator, $ids);
    }
    if (!empty($output)){
        break;
    }
}


if (!empty($element)) {
    if (empty($output) && $ignoreRelationIfEmpty) {
        return $modx->runSnippet($element, $scriptProperties);
    } else {
        $scriptProperties[\'resources\'] = $output;
        $scriptProperties[\'parents\'] = \'9999999\';
        return $modx->runSnippet($element, $scriptProperties);
    }


}

if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return \'\';
}

return $output;',
      'locked' => 0,
      'properties' => NULL,
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '$id = $modx->getOption(\'id\', $scriptProperties, $modx->resource->get(\'id\'));
$toPlaceholder = $modx->getOption(\'toPlaceholder\', $scriptProperties, \'\');
$element = $modx->getOption(\'element\', $scriptProperties, \'getResources\');
$outputSeparator = $modx->getOption(\'outputSeparator\', $scriptProperties, \',\');
$sourceWhere = $modx->getOption(\'sourceWhere\', $scriptProperties, \'\');
$ignoreRelationIfEmpty = $modx->getOption(\'ignoreRelationIfEmpty\', $scriptProperties, false);
$inheritFromParents = $modx->getOption(\'inheritFromParents\', $scriptProperties, false);
$parentIDs = $inheritFromParents ? array_merge(array($id), $modx->getParentIds($id)) : array($id);

$packageName = \'resourcerelations\';

$packagepath = $modx->getOption(\'core_path\') . \'components/\' . $packageName . \'/\';
$modelpath = $packagepath . \'model/\';

$modx->addPackage($packageName, $modelpath, $prefix);
$classname = \'rrResourceRelation\';
$output = \'\';

foreach ($parentIDs as $id) {
    if (!empty($id)) {
        $output = \'\';
                
        $c = $modx->newQuery($classname, array(\'target_id\' => $id, \'published\' => \'1\'));
        $c->select($modx->getSelectColumns($classname, $classname));

        if (!empty($sourceWhere)) {
            $sourceWhere_ar = $modx->fromJson($sourceWhere);
            if (is_array($sourceWhere_ar)) {
                $where = array();
                foreach ($sourceWhere_ar as $key => $value) {
                    $where[\'Source.\' . $key] = $value;
                }
                $joinclass = \'modResource\';
                $joinalias = \'Source\';
                $selectfields = \'id\';
                $selectfields = !empty($selectfields) ? explode(\',\', $selectfields) : null;
                $c->leftjoin($joinclass, $joinalias);
                $c->select($modx->getSelectColumns($joinclass, $joinalias, $joinalias . \'_\', $selectfields));
                $c->where($where);
            }
        }

        //$c->prepare(); echo $c->toSql();
        if ($c->prepare() && $c->stmt->execute()) {
            $collection = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        foreach ($collection as $row) {
            $ids[] = $row[\'source_id\'];
        }
        $output = implode($outputSeparator, $ids);
    }
    if (!empty($output)){
        break;
    }
}


if (!empty($element)) {
    if (empty($output) && $ignoreRelationIfEmpty) {
        return $modx->runSnippet($element, $scriptProperties);
    } else {
        $scriptProperties[\'resources\'] = $output;
        $scriptProperties[\'parents\'] = \'9999999\';
        return $modx->runSnippet($element, $scriptProperties);
    }


}

if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return \'\';
}

return $output;',
    ),
  ),
  'f9a016b8411554cae16ccda70bbd3b8d' => 
  array (
    'criteria' => 
    array (
      'name' => 'migx',
    ),
    'object' => 
    array (
      'id' => 12,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'migx',
      'description' => '',
      'editor_type' => 0,
      'category' => 6,
      'cache_type' => 0,
      'snippet' => '$tvname = $modx->getOption(\'tvname\', $scriptProperties, \'\');
$tpl = $modx->getOption(\'tpl\', $scriptProperties, \'\');
$limit = $modx->getOption(\'limit\', $scriptProperties, \'0\');
$offset = $modx->getOption(\'offset\', $scriptProperties, 0);
$totalVar = $modx->getOption(\'totalVar\', $scriptProperties, \'total\');
$randomize = $modx->getOption(\'randomize\', $scriptProperties, false);
$preselectLimit = $modx->getOption(\'preselectLimit\', $scriptProperties, 0); // when random preselect important images
$where = $modx->getOption(\'where\', $scriptProperties, \'\');
$where = !empty($where) ? $modx->fromJSON($where) : array();
$sortConfig = $modx->getOption(\'sortConfig\', $scriptProperties, \'\');
$sortConfig = !empty($sortConfig) ? $modx->fromJSON($sortConfig) : array();
$configs = $modx->getOption(\'configs\', $scriptProperties, \'\');
$configs = !empty($configs) ? explode(\',\',$configs):array();
$toSeparatePlaceholders = $modx->getOption(\'toSeparatePlaceholders\', $scriptProperties, false);
$toPlaceholder = $modx->getOption(\'toPlaceholder\', $scriptProperties, false);
$outputSeparator = $modx->getOption(\'outputSeparator\', $scriptProperties, \'\');
//$placeholdersKeyField = $modx->getOption(\'placeholdersKeyField\', $scriptProperties, \'MIGX_id\');
$placeholdersKeyField = $modx->getOption(\'placeholdersKeyField\', $scriptProperties, \'id\');
$toJsonPlaceholder = $modx->getOption(\'toJsonPlaceholder\', $scriptProperties, false);
$jsonVarKey = $modx->getOption(\'jsonVarKey\', $scriptProperties, \'migx_outputvalue\');
$outputvalue = $modx->getOption(\'value\', $scriptProperties, \'\');
$outputvalue = isset($_REQUEST[$jsonVarKey]) ? $_REQUEST[$jsonVarKey] : $outputvalue;
$docidVarKey = $modx->getOption(\'docidVarKey\', $scriptProperties, \'migx_docid\');
$docid = $modx->getOption(\'docid\', $scriptProperties, (isset($modx->resource) ? $modx->resource->get(\'id\') : 1));
$docid = isset($_REQUEST[$docidVarKey]) ? $_REQUEST[$docidVarKey] : $docid;
$processTVs = $modx->getOption(\'processTVs\', $scriptProperties, \'1\');

$base_path = $modx->getOption(\'base_path\', null, MODX_BASE_PATH);
$base_url = $modx->getOption(\'base_url\', null, MODX_BASE_URL);

$migx = $modx->getService(\'migx\', \'Migx\', $modx->getOption(\'migx.core_path\', null, $modx->getOption(\'core_path\') . \'components/migx/\') . \'model/migx/\', $scriptProperties);
if (!($migx instanceof Migx))
    return \'\';
//$modx->migx = &$migx;
$defaultcontext = \'web\';
$migx->working_context = isset($modx->resource) ? $modx->resource->get(\'context_key\') : $defaultcontext;

if (!empty($tvname))
{
    if ($tv = $modx->getObject(\'modTemplateVar\', array(\'name\' => $tvname)))
    {

        /*
        *   get inputProperties
        */


        $properties = $tv->get(\'input_properties\');
        $properties = isset($properties[\'configs\']) ? $properties : $tv->getProperties();
        $cfgs = $modx->getOption(\'configs\',$properties,\'\');
        if (!empty($cfgs)){
            $cfgs = explode(\',\',$cfgs);
            $configs = array_merge($configs,$cfgs);
           
        }
        
    }
}



//$migx->config[\'configs\'] = implode(\',\',$configs);
$migx->loadConfigs(false,true,array(\'configs\'=>implode(\',\',$configs)));
$migx->customconfigs = array_merge($migx->customconfigs,$scriptProperties);



// get tabs from file or migx-config-table
$formtabs = $migx->getTabs();
if (empty($formtabs))
{
    //try to get formtabs and its fields from properties
    $formtabs = $modx->fromJSON($properties[\'formtabs\']);
}

if ($jsonVarKey == \'migx_outputvalue\' && !empty($properties[\'jsonvarkey\']))
{
    $jsonVarKey = $properties[\'jsonvarkey\'];
    $outputvalue = isset($_REQUEST[$jsonVarKey]) ? $_REQUEST[$jsonVarKey] : $outputvalue;
}

$outputvalue = $tv && empty($outputvalue) ? $tv->renderOutput($docid) : $outputvalue;
/*
*   get inputTvs 
*/
$inputTvs = array();
if (is_array($formtabs))
{

    //multiple different Forms
    // Note: use same field-names and inputTVs in all forms
    $inputTvs = $migx->extractInputTvs($formtabs);
}

if ($tv)
{
    $migx->source = $tv->getSource($migx->working_context, false);
}

//$task = $modx->migx->getTask();
$filename = \'getlist.php\';
$processorspath = $migx->config[\'processorsPath\'] . \'mgr/\';
$filenames = array();
$scriptProperties[\'start\'] = $modx->getOption(\'offset\', $scriptProperties, 0);
if ($processor_file = $migx->findProcessor($processorspath, $filename, $filenames))
{
    include ($processor_file);
    //todo: add getlist-processor for default-MIGX-TV
}

$items = isset($rows) && is_array($rows) ? $rows : array();
$modx->setPlaceholder($totalVar, isset($count) ? $count : 0);

$properties = array();
foreach ($scriptProperties as $property => $value)
{
    $properties[\'property.\' . $property] = $value;
}

$idx = 0;
$output = array();
foreach ($items as $key => $item)
{

    $fields = array();
    foreach ($item as $field => $value)
    {
        $value = is_array($value) ? implode(\'||\', $value) : $value; //handle arrays (checkboxes, multiselects)
        if ($processTVs && isset($inputTvs[$field]))
        {
            if ($tv = $modx->getObject(\'modTemplateVar\', array(\'name\' => $inputTvs[$field][\'inputTV\'])))
            {

            } else
            {
                $tv = $modx->newObject(\'modTemplateVar\');
                $tv->set(\'type\', $inputTvs[$field][\'inputTVtype\']);
            }
            $inputTV = $inputTvs[$field];

            $mTypes = $modx->getOption(\'manipulatable_url_tv_output_types\', null, \'image,file\');
            //don\'t manipulate any urls here
            $modx->setOption(\'manipulatable_url_tv_output_types\', \'\');
            $tv->set(\'default_text\', $value);
            $value = $tv->renderOutput($docid);
            //set option back
            $modx->setOption(\'manipulatable_url_tv_output_types\', $mTypes);
            //now manipulate urls
            if ($mediasource = $migx->getFieldSource($inputTV, $tv))
            {
                $mTypes = explode(\',\', $mTypes);
                if (!empty($value) && in_array($tv->get(\'type\'), $mTypes))
                {
                    //$value = $mediasource->prepareOutputUrl($value);
                    $value = str_replace(\'/./\', \'/\', $mediasource->prepareOutputUrl($value));
                }
            }

        }
        $fields[$field] = $value;

    }
    if ($toJsonPlaceholder)
    {
        $output[] = $fields;
    } else
    {
        $fields[\'_alt\'] = $idx % 2;
        $idx++;
        $fields[\'_first\'] = $idx == 1 ? true : \'\';
        $fields[\'_last\'] = $idx == $limit ? true : \'\';
        $fields[\'idx\'] = $idx;
        $rowtpl = $tpl;
        //get changing tpls from field
        if (substr($tpl, 0, 7) == "@FIELD:")
        {
            $tplField = substr($tpl, 7);
            $rowtpl = $fields[$tplField];
        }

        if (!isset($template[$rowtpl]))
        {
            if (substr($rowtpl, 0, 6) == "@FILE:")
            {
                $template[$rowtpl] = file_get_contents($modx->config[\'base_path\'] . substr($rowtpl, 6));
            } elseif (substr($rowtpl, 0, 6) == "@CODE:")
            {
                $template[$rowtpl] = substr($tpl, 6);
            } elseif ($chunk = $modx->getObject(\'modChunk\', array(\'name\' => $rowtpl), true))
            {
                $template[$rowtpl] = $chunk->getContent();
            } else
            {
                $template[$rowtpl] = false;
            }
        }

        $fields = array_merge($fields, $properties);

        if ($template[$rowtpl])
        {
            $chunk = $modx->newObject(\'modChunk\');
            $chunk->setCacheable(false);
            $chunk->setContent($template[$rowtpl]);
            if (!empty($placeholdersKeyField) && isset($fields[$placeholdersKeyField]))
            {
                $output[$fields[$placeholdersKeyField]] = $chunk->process($fields);
            } else
            {
                $output[] = $chunk->process($fields);
            }
        } else
        {
            if (!empty($placeholdersKeyField))
            {
                $output[$fields[$placeholdersKeyField]] = \'<pre>\' . print_r($fields, 1) . \'</pre>\';
            } else
            {
                $output[] = \'<pre>\' . print_r($fields, 1) . \'</pre>\';
            }
        }
    }


}


if ($toJsonPlaceholder)
{
    $modx->setPlaceholder($toJsonPlaceholder, $modx->toJson($output));
    return \'\';
}

if (!empty($toSeparatePlaceholders))
{
    $modx->toPlaceholders($output, $toSeparatePlaceholders);
    return \'\';
}
/*
if (!empty($outerTpl))
$o = parseTpl($outerTpl, array(\'output\'=>implode($outputSeparator, $output)));
else 
*/
if (is_array($output))
{
    $o = implode($outputSeparator, $output);
} else
{
    $o = $output;
}

if (!empty($toPlaceholder))
{
    $modx->setPlaceholder($toPlaceholder, $o);
    return \'\';
}

return $o;',
      'locked' => 0,
      'properties' => NULL,
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '$tvname = $modx->getOption(\'tvname\', $scriptProperties, \'\');
$tpl = $modx->getOption(\'tpl\', $scriptProperties, \'\');
$limit = $modx->getOption(\'limit\', $scriptProperties, \'0\');
$offset = $modx->getOption(\'offset\', $scriptProperties, 0);
$totalVar = $modx->getOption(\'totalVar\', $scriptProperties, \'total\');
$randomize = $modx->getOption(\'randomize\', $scriptProperties, false);
$preselectLimit = $modx->getOption(\'preselectLimit\', $scriptProperties, 0); // when random preselect important images
$where = $modx->getOption(\'where\', $scriptProperties, \'\');
$where = !empty($where) ? $modx->fromJSON($where) : array();
$sortConfig = $modx->getOption(\'sortConfig\', $scriptProperties, \'\');
$sortConfig = !empty($sortConfig) ? $modx->fromJSON($sortConfig) : array();
$configs = $modx->getOption(\'configs\', $scriptProperties, \'\');
$configs = !empty($configs) ? explode(\',\',$configs):array();
$toSeparatePlaceholders = $modx->getOption(\'toSeparatePlaceholders\', $scriptProperties, false);
$toPlaceholder = $modx->getOption(\'toPlaceholder\', $scriptProperties, false);
$outputSeparator = $modx->getOption(\'outputSeparator\', $scriptProperties, \'\');
//$placeholdersKeyField = $modx->getOption(\'placeholdersKeyField\', $scriptProperties, \'MIGX_id\');
$placeholdersKeyField = $modx->getOption(\'placeholdersKeyField\', $scriptProperties, \'id\');
$toJsonPlaceholder = $modx->getOption(\'toJsonPlaceholder\', $scriptProperties, false);
$jsonVarKey = $modx->getOption(\'jsonVarKey\', $scriptProperties, \'migx_outputvalue\');
$outputvalue = $modx->getOption(\'value\', $scriptProperties, \'\');
$outputvalue = isset($_REQUEST[$jsonVarKey]) ? $_REQUEST[$jsonVarKey] : $outputvalue;
$docidVarKey = $modx->getOption(\'docidVarKey\', $scriptProperties, \'migx_docid\');
$docid = $modx->getOption(\'docid\', $scriptProperties, (isset($modx->resource) ? $modx->resource->get(\'id\') : 1));
$docid = isset($_REQUEST[$docidVarKey]) ? $_REQUEST[$docidVarKey] : $docid;
$processTVs = $modx->getOption(\'processTVs\', $scriptProperties, \'1\');

$base_path = $modx->getOption(\'base_path\', null, MODX_BASE_PATH);
$base_url = $modx->getOption(\'base_url\', null, MODX_BASE_URL);

$migx = $modx->getService(\'migx\', \'Migx\', $modx->getOption(\'migx.core_path\', null, $modx->getOption(\'core_path\') . \'components/migx/\') . \'model/migx/\', $scriptProperties);
if (!($migx instanceof Migx))
    return \'\';
//$modx->migx = &$migx;
$defaultcontext = \'web\';
$migx->working_context = isset($modx->resource) ? $modx->resource->get(\'context_key\') : $defaultcontext;

if (!empty($tvname))
{
    if ($tv = $modx->getObject(\'modTemplateVar\', array(\'name\' => $tvname)))
    {

        /*
        *   get inputProperties
        */


        $properties = $tv->get(\'input_properties\');
        $properties = isset($properties[\'configs\']) ? $properties : $tv->getProperties();
        $cfgs = $modx->getOption(\'configs\',$properties,\'\');
        if (!empty($cfgs)){
            $cfgs = explode(\',\',$cfgs);
            $configs = array_merge($configs,$cfgs);
           
        }
        
    }
}



//$migx->config[\'configs\'] = implode(\',\',$configs);
$migx->loadConfigs(false,true,array(\'configs\'=>implode(\',\',$configs)));
$migx->customconfigs = array_merge($migx->customconfigs,$scriptProperties);



// get tabs from file or migx-config-table
$formtabs = $migx->getTabs();
if (empty($formtabs))
{
    //try to get formtabs and its fields from properties
    $formtabs = $modx->fromJSON($properties[\'formtabs\']);
}

if ($jsonVarKey == \'migx_outputvalue\' && !empty($properties[\'jsonvarkey\']))
{
    $jsonVarKey = $properties[\'jsonvarkey\'];
    $outputvalue = isset($_REQUEST[$jsonVarKey]) ? $_REQUEST[$jsonVarKey] : $outputvalue;
}

$outputvalue = $tv && empty($outputvalue) ? $tv->renderOutput($docid) : $outputvalue;
/*
*   get inputTvs 
*/
$inputTvs = array();
if (is_array($formtabs))
{

    //multiple different Forms
    // Note: use same field-names and inputTVs in all forms
    $inputTvs = $migx->extractInputTvs($formtabs);
}

if ($tv)
{
    $migx->source = $tv->getSource($migx->working_context, false);
}

//$task = $modx->migx->getTask();
$filename = \'getlist.php\';
$processorspath = $migx->config[\'processorsPath\'] . \'mgr/\';
$filenames = array();
$scriptProperties[\'start\'] = $modx->getOption(\'offset\', $scriptProperties, 0);
if ($processor_file = $migx->findProcessor($processorspath, $filename, $filenames))
{
    include ($processor_file);
    //todo: add getlist-processor for default-MIGX-TV
}

$items = isset($rows) && is_array($rows) ? $rows : array();
$modx->setPlaceholder($totalVar, isset($count) ? $count : 0);

$properties = array();
foreach ($scriptProperties as $property => $value)
{
    $properties[\'property.\' . $property] = $value;
}

$idx = 0;
$output = array();
foreach ($items as $key => $item)
{

    $fields = array();
    foreach ($item as $field => $value)
    {
        $value = is_array($value) ? implode(\'||\', $value) : $value; //handle arrays (checkboxes, multiselects)
        if ($processTVs && isset($inputTvs[$field]))
        {
            if ($tv = $modx->getObject(\'modTemplateVar\', array(\'name\' => $inputTvs[$field][\'inputTV\'])))
            {

            } else
            {
                $tv = $modx->newObject(\'modTemplateVar\');
                $tv->set(\'type\', $inputTvs[$field][\'inputTVtype\']);
            }
            $inputTV = $inputTvs[$field];

            $mTypes = $modx->getOption(\'manipulatable_url_tv_output_types\', null, \'image,file\');
            //don\'t manipulate any urls here
            $modx->setOption(\'manipulatable_url_tv_output_types\', \'\');
            $tv->set(\'default_text\', $value);
            $value = $tv->renderOutput($docid);
            //set option back
            $modx->setOption(\'manipulatable_url_tv_output_types\', $mTypes);
            //now manipulate urls
            if ($mediasource = $migx->getFieldSource($inputTV, $tv))
            {
                $mTypes = explode(\',\', $mTypes);
                if (!empty($value) && in_array($tv->get(\'type\'), $mTypes))
                {
                    //$value = $mediasource->prepareOutputUrl($value);
                    $value = str_replace(\'/./\', \'/\', $mediasource->prepareOutputUrl($value));
                }
            }

        }
        $fields[$field] = $value;

    }
    if ($toJsonPlaceholder)
    {
        $output[] = $fields;
    } else
    {
        $fields[\'_alt\'] = $idx % 2;
        $idx++;
        $fields[\'_first\'] = $idx == 1 ? true : \'\';
        $fields[\'_last\'] = $idx == $limit ? true : \'\';
        $fields[\'idx\'] = $idx;
        $rowtpl = $tpl;
        //get changing tpls from field
        if (substr($tpl, 0, 7) == "@FIELD:")
        {
            $tplField = substr($tpl, 7);
            $rowtpl = $fields[$tplField];
        }

        if (!isset($template[$rowtpl]))
        {
            if (substr($rowtpl, 0, 6) == "@FILE:")
            {
                $template[$rowtpl] = file_get_contents($modx->config[\'base_path\'] . substr($rowtpl, 6));
            } elseif (substr($rowtpl, 0, 6) == "@CODE:")
            {
                $template[$rowtpl] = substr($tpl, 6);
            } elseif ($chunk = $modx->getObject(\'modChunk\', array(\'name\' => $rowtpl), true))
            {
                $template[$rowtpl] = $chunk->getContent();
            } else
            {
                $template[$rowtpl] = false;
            }
        }

        $fields = array_merge($fields, $properties);

        if ($template[$rowtpl])
        {
            $chunk = $modx->newObject(\'modChunk\');
            $chunk->setCacheable(false);
            $chunk->setContent($template[$rowtpl]);
            if (!empty($placeholdersKeyField) && isset($fields[$placeholdersKeyField]))
            {
                $output[$fields[$placeholdersKeyField]] = $chunk->process($fields);
            } else
            {
                $output[] = $chunk->process($fields);
            }
        } else
        {
            if (!empty($placeholdersKeyField))
            {
                $output[$fields[$placeholdersKeyField]] = \'<pre>\' . print_r($fields, 1) . \'</pre>\';
            } else
            {
                $output[] = \'<pre>\' . print_r($fields, 1) . \'</pre>\';
            }
        }
    }


}


if ($toJsonPlaceholder)
{
    $modx->setPlaceholder($toJsonPlaceholder, $modx->toJson($output));
    return \'\';
}

if (!empty($toSeparatePlaceholders))
{
    $modx->toPlaceholders($output, $toSeparatePlaceholders);
    return \'\';
}
/*
if (!empty($outerTpl))
$o = parseTpl($outerTpl, array(\'output\'=>implode($outputSeparator, $output)));
else 
*/
if (is_array($output))
{
    $o = implode($outputSeparator, $output);
} else
{
    $o = $output;
}

if (!empty($toPlaceholder))
{
    $modx->setPlaceholder($toPlaceholder, $o);
    return \'\';
}

return $o;',
    ),
  ),
  'cc859ce7e06040caa925aa80dce9c773' => 
  array (
    'criteria' => 
    array (
      'name' => 'migxLoopCollection',
    ),
    'object' => 
    array (
      'id' => 13,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'migxLoopCollection',
      'description' => '',
      'editor_type' => 0,
      'category' => 6,
      'cache_type' => 0,
      'snippet' => '$tpl = $modx->getOption(\'tpl\', $scriptProperties, \'\');
$limit = $modx->getOption(\'limit\', $scriptProperties, \'0\');
$offset = $modx->getOption(\'offset\', $scriptProperties, 0);
$totalVar = $modx->getOption(\'totalVar\', $scriptProperties, \'total\');

$where = $modx->getOption(\'where\', $scriptProperties, \'\');
$where = !empty($where) ? $modx->fromJSON($where) : array();
$queries = $modx->getOption(\'queries\', $scriptProperties, \'\');
$queries = !empty($queries) ? $modx->fromJSON($queries) : array();
$sortConfig = $modx->getOption(\'sortConfig\', $scriptProperties, \'\');
$sortConfig = !empty($sortConfig) ? $modx->fromJSON($sortConfig) : array();
$configs = $modx->getOption(\'configs\', $scriptProperties, \'\');
$configs = explode(\',\', $configs);
$toSeparatePlaceholders = $modx->getOption(\'toSeparatePlaceholders\', $scriptProperties, false);
$toPlaceholder = $modx->getOption(\'toPlaceholder\', $scriptProperties, false);
$outputSeparator = $modx->getOption(\'outputSeparator\', $scriptProperties, \'\');
//$placeholdersKeyField = $modx->getOption(\'placeholdersKeyField\', $scriptProperties, \'MIGX_id\');
$placeholdersKeyField = $modx->getOption(\'placeholdersKeyField\', $scriptProperties, \'id\');
$toJsonPlaceholder = $modx->getOption(\'toJsonPlaceholder\', $scriptProperties, false);
$jsonVarKey = $modx->getOption(\'jsonVarKey\', $scriptProperties, \'migx_outputvalue\');
$prefix = isset($scriptProperties[\'prefix\']) ? $scriptProperties[\'prefix\'] : null; 

$packageName = $scriptProperties[\'packageName\'];
$joins = $modx->getOption(\'joins\',$scriptProperties,\'\'); 
$joins = !empty($joins) ? $modx->fromJson($joins) : false;

$packagepath = $modx->getOption(\'core_path\') . \'components/\' . $packageName . \'/\';
$modelpath = $packagepath . \'model/\';

$modx->addPackage($packageName, $modelpath, $prefix);
$classname = $scriptProperties[\'classname\'];

$base_path = $modx->getOption(\'base_path\', null, MODX_BASE_PATH);
$base_url = $modx->getOption(\'base_url\', null, MODX_BASE_URL);

$migx = $modx->getService(\'migx\', \'Migx\', $modx->getOption(\'migx.core_path\', null, $modx->getOption(\'core_path\') . \'components/migx/\') . \'model/migx/\', $scriptProperties);
if (!($migx instanceof Migx))
    return \'\';
//$modx->migx = &$migx;
$defaultcontext = \'web\';
$migx->working_context = isset($modx->resource) ? $modx->resource->get(\'context_key\') : $defaultcontext;

$properties = array();
foreach ($scriptProperties as $property => $value) {
    $properties[\'property.\' . $property] = $value;
}

$idx = 0;
$output = array();
$c = $modx->newQuery($classname);
$c->select($modx->getSelectColumns($classname,$classname));

if ($joins) {
    $migx->prepareJoins($classname,$joins,$c);
}

if (!empty($where)) {
    $c->where($where);
}

if (!empty($queries)) {
    foreach ($queries as $key=>$query){
        $c->where($query,$key);
    }
    
}

if (is_array($sortConfig)) {
    foreach ($sortConfig as $sort) {
        $sortby = $sort[\'sortby\'];
        $sortdir = isset($sort[\'sortdir\']) ? $sort[\'sortdir\'] : \'ASC\';
        $c->sortby($sortby, $sortdir);
    }
}

$c->prepare();echo $c->toSql();

if ($collection = $modx->getCollection($classname, $c)) {
    foreach ($collection as $object) {
        $fields = $object->toArray();
        if ($toJsonPlaceholder) {
            $output[] = $fields;
        } else {
            $fields[\'_alt\'] = $idx % 2;
            $idx++;
            $fields[\'_first\'] = $idx == 1 ? true : \'\';
            $fields[\'_last\'] = $idx == $limit ? true : \'\';
            $fields[\'idx\'] = $idx;
            $rowtpl = $tpl;
            //get changing tpls from field
            if (substr($tpl, 0, 7) == "@FIELD:") {
                $tplField = substr($tpl, 7);
                $rowtpl = $fields[$tplField];
            }

            if (!isset($template[$rowtpl])) {
                if (substr($rowtpl, 0, 6) == "@FILE:") {
                    $template[$rowtpl] = file_get_contents($modx->config[\'base_path\'] . substr($rowtpl, 6));
                } elseif (substr($rowtpl, 0, 6) == "@CODE:") {
                    $template[$rowtpl] = substr($tpl, 6);
                } elseif ($chunk = $modx->getObject(\'modChunk\', array(\'name\' => $rowtpl), true)) {
                    $template[$rowtpl] = $chunk->getContent();
                } else {
                    $template[$rowtpl] = false;
                }
            }

            $fields = array_merge($fields, $properties);

            if ($template[$rowtpl]) {
                $chunk = $modx->newObject(\'modChunk\');
                $chunk->setCacheable(false);
                $chunk->setContent($template[$rowtpl]);
                if (!empty($placeholdersKeyField) && isset($fields[$placeholdersKeyField])) {
                    $output[$fields[$placeholdersKeyField]] = $chunk->process($fields);
                } else {
                    $output[] = $chunk->process($fields);
                }
            } else {
                if (!empty($placeholdersKeyField)) {
                    $output[$fields[$placeholdersKeyField]] = \'<pre>\' . print_r($fields, 1) . \'</pre>\';
                } else {
                    $output[] = \'<pre>\' . print_r($fields, 1) . \'</pre>\';
                }
            }
        }


    }
}


if ($toJsonPlaceholder) {
    $modx->setPlaceholder($toJsonPlaceholder, $modx->toJson($output));
    return \'\';
}

if (!empty($toSeparatePlaceholders)) {
    $modx->toPlaceholders($output, $toSeparatePlaceholders);
    return \'\';
}
/*
if (!empty($outerTpl))
$o = parseTpl($outerTpl, array(\'output\'=>implode($outputSeparator, $output)));
else 
*/
if (is_array($output)) {
    $o = implode($outputSeparator, $output);
} else {
    $o = $output;
}

if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $o);
    return \'\';
}

return $o;',
      'locked' => 0,
      'properties' => NULL,
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '$tpl = $modx->getOption(\'tpl\', $scriptProperties, \'\');
$limit = $modx->getOption(\'limit\', $scriptProperties, \'0\');
$offset = $modx->getOption(\'offset\', $scriptProperties, 0);
$totalVar = $modx->getOption(\'totalVar\', $scriptProperties, \'total\');

$where = $modx->getOption(\'where\', $scriptProperties, \'\');
$where = !empty($where) ? $modx->fromJSON($where) : array();
$queries = $modx->getOption(\'queries\', $scriptProperties, \'\');
$queries = !empty($queries) ? $modx->fromJSON($queries) : array();
$sortConfig = $modx->getOption(\'sortConfig\', $scriptProperties, \'\');
$sortConfig = !empty($sortConfig) ? $modx->fromJSON($sortConfig) : array();
$configs = $modx->getOption(\'configs\', $scriptProperties, \'\');
$configs = explode(\',\', $configs);
$toSeparatePlaceholders = $modx->getOption(\'toSeparatePlaceholders\', $scriptProperties, false);
$toPlaceholder = $modx->getOption(\'toPlaceholder\', $scriptProperties, false);
$outputSeparator = $modx->getOption(\'outputSeparator\', $scriptProperties, \'\');
//$placeholdersKeyField = $modx->getOption(\'placeholdersKeyField\', $scriptProperties, \'MIGX_id\');
$placeholdersKeyField = $modx->getOption(\'placeholdersKeyField\', $scriptProperties, \'id\');
$toJsonPlaceholder = $modx->getOption(\'toJsonPlaceholder\', $scriptProperties, false);
$jsonVarKey = $modx->getOption(\'jsonVarKey\', $scriptProperties, \'migx_outputvalue\');
$prefix = isset($scriptProperties[\'prefix\']) ? $scriptProperties[\'prefix\'] : null; 

$packageName = $scriptProperties[\'packageName\'];
$joins = $modx->getOption(\'joins\',$scriptProperties,\'\'); 
$joins = !empty($joins) ? $modx->fromJson($joins) : false;

$packagepath = $modx->getOption(\'core_path\') . \'components/\' . $packageName . \'/\';
$modelpath = $packagepath . \'model/\';

$modx->addPackage($packageName, $modelpath, $prefix);
$classname = $scriptProperties[\'classname\'];

$base_path = $modx->getOption(\'base_path\', null, MODX_BASE_PATH);
$base_url = $modx->getOption(\'base_url\', null, MODX_BASE_URL);

$migx = $modx->getService(\'migx\', \'Migx\', $modx->getOption(\'migx.core_path\', null, $modx->getOption(\'core_path\') . \'components/migx/\') . \'model/migx/\', $scriptProperties);
if (!($migx instanceof Migx))
    return \'\';
//$modx->migx = &$migx;
$defaultcontext = \'web\';
$migx->working_context = isset($modx->resource) ? $modx->resource->get(\'context_key\') : $defaultcontext;

$properties = array();
foreach ($scriptProperties as $property => $value) {
    $properties[\'property.\' . $property] = $value;
}

$idx = 0;
$output = array();
$c = $modx->newQuery($classname);
$c->select($modx->getSelectColumns($classname,$classname));

if ($joins) {
    $migx->prepareJoins($classname,$joins,$c);
}

if (!empty($where)) {
    $c->where($where);
}

if (!empty($queries)) {
    foreach ($queries as $key=>$query){
        $c->where($query,$key);
    }
    
}

if (is_array($sortConfig)) {
    foreach ($sortConfig as $sort) {
        $sortby = $sort[\'sortby\'];
        $sortdir = isset($sort[\'sortdir\']) ? $sort[\'sortdir\'] : \'ASC\';
        $c->sortby($sortby, $sortdir);
    }
}

$c->prepare();echo $c->toSql();

if ($collection = $modx->getCollection($classname, $c)) {
    foreach ($collection as $object) {
        $fields = $object->toArray();
        if ($toJsonPlaceholder) {
            $output[] = $fields;
        } else {
            $fields[\'_alt\'] = $idx % 2;
            $idx++;
            $fields[\'_first\'] = $idx == 1 ? true : \'\';
            $fields[\'_last\'] = $idx == $limit ? true : \'\';
            $fields[\'idx\'] = $idx;
            $rowtpl = $tpl;
            //get changing tpls from field
            if (substr($tpl, 0, 7) == "@FIELD:") {
                $tplField = substr($tpl, 7);
                $rowtpl = $fields[$tplField];
            }

            if (!isset($template[$rowtpl])) {
                if (substr($rowtpl, 0, 6) == "@FILE:") {
                    $template[$rowtpl] = file_get_contents($modx->config[\'base_path\'] . substr($rowtpl, 6));
                } elseif (substr($rowtpl, 0, 6) == "@CODE:") {
                    $template[$rowtpl] = substr($tpl, 6);
                } elseif ($chunk = $modx->getObject(\'modChunk\', array(\'name\' => $rowtpl), true)) {
                    $template[$rowtpl] = $chunk->getContent();
                } else {
                    $template[$rowtpl] = false;
                }
            }

            $fields = array_merge($fields, $properties);

            if ($template[$rowtpl]) {
                $chunk = $modx->newObject(\'modChunk\');
                $chunk->setCacheable(false);
                $chunk->setContent($template[$rowtpl]);
                if (!empty($placeholdersKeyField) && isset($fields[$placeholdersKeyField])) {
                    $output[$fields[$placeholdersKeyField]] = $chunk->process($fields);
                } else {
                    $output[] = $chunk->process($fields);
                }
            } else {
                if (!empty($placeholdersKeyField)) {
                    $output[$fields[$placeholdersKeyField]] = \'<pre>\' . print_r($fields, 1) . \'</pre>\';
                } else {
                    $output[] = \'<pre>\' . print_r($fields, 1) . \'</pre>\';
                }
            }
        }


    }
}


if ($toJsonPlaceholder) {
    $modx->setPlaceholder($toJsonPlaceholder, $modx->toJson($output));
    return \'\';
}

if (!empty($toSeparatePlaceholders)) {
    $modx->toPlaceholders($output, $toSeparatePlaceholders);
    return \'\';
}
/*
if (!empty($outerTpl))
$o = parseTpl($outerTpl, array(\'output\'=>implode($outputSeparator, $output)));
else 
*/
if (is_array($output)) {
    $o = implode($outputSeparator, $output);
} else {
    $o = $output;
}

if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $o);
    return \'\';
}

return $o;',
    ),
  ),
  '5339f75b168ed4cf169a88399c418d78' => 
  array (
    'criteria' => 
    array (
      'name' => 'migxResourceMediaPath',
    ),
    'object' => 
    array (
      'id' => 14,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'migxResourceMediaPath',
      'description' => '',
      'editor_type' => 0,
      'category' => 6,
      'cache_type' => 0,
      'snippet' => '$pathTpl = $modx->getOption(\'pathTpl\', $scriptProperties, \'\');
$docid = $modx->getOption(\'docid\', $scriptProperties, \'\');

if (empty($docid) && $modx->getPlaceholder(\'docid\')) {
    $docid = $modx->getPlaceholder(\'docid\');
}
if (empty($docid)) {

    if (is_Object($modx->resource)) {
        $docid = $modx->resource->get(\'id\');
    } else {

        $parsedUrl = parse_url($_SERVER[\'HTTP_REFERER\']);
        parse_str($parsedUrl[\'query\'], $parsedQuery);

        if (isset($parsedQuery[\'amp;id\'])) {
            $docid = $parsedQuery[\'amp;id\'];
        } elseif (isset($parsedQuery[\'id\'])) {
            $docid = $parsedQuery[\'id\'];
        }
    }
}


$path = str_replace(\'{id}\', $docid, $pathTpl);
$fullpath = $modx->getOption(\'base_path\') . $path;

if (!file_exists($fullpath)) {
    mkdir($fullpath, 0755, true);
}


return $path;',
      'locked' => 0,
      'properties' => NULL,
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => '$pathTpl = $modx->getOption(\'pathTpl\', $scriptProperties, \'\');
$docid = $modx->getOption(\'docid\', $scriptProperties, \'\');

if (empty($docid) && $modx->getPlaceholder(\'docid\')) {
    $docid = $modx->getPlaceholder(\'docid\');
}
if (empty($docid)) {

    if (is_Object($modx->resource)) {
        $docid = $modx->resource->get(\'id\');
    } else {

        $parsedUrl = parse_url($_SERVER[\'HTTP_REFERER\']);
        parse_str($parsedUrl[\'query\'], $parsedQuery);

        if (isset($parsedQuery[\'amp;id\'])) {
            $docid = $parsedQuery[\'amp;id\'];
        } elseif (isset($parsedQuery[\'id\'])) {
            $docid = $parsedQuery[\'id\'];
        }
    }
}


$path = str_replace(\'{id}\', $docid, $pathTpl);
$fullpath = $modx->getOption(\'base_path\') . $path;

if (!file_exists($fullpath)) {
    mkdir($fullpath, 0755, true);
}


return $path;',
    ),
  ),
  'e40ce0c1056e7c428db7d988fb631e49' => 
  array (
    'criteria' => 
    array (
      'name' => 'migxImageUpload',
    ),
    'object' => 
    array (
      'id' => 15,
      'source' => 0,
      'property_preprocess' => 0,
      'name' => 'migxImageUpload',
      'description' => '',
      'editor_type' => 0,
      'category' => 6,
      'cache_type' => 0,
      'snippet' => 'return include $modx->getOption(\'core_path\').\'components/migx/model/imageupload/imageupload.php\';',
      'locked' => 0,
      'properties' => NULL,
      'moduleguid' => '',
      'static' => 0,
      'static_file' => '',
      'content' => 'return include $modx->getOption(\'core_path\').\'components/migx/model/imageupload/imageupload.php\';',
    ),
  ),
);