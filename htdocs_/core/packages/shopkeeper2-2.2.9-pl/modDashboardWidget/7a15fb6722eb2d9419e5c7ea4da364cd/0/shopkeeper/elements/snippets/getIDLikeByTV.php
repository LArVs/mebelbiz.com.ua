<?php

/*
 
[[getIDLikeByTV?tmplvarid=`5`&like=`:[[*id]]:`]]

*/

if(!isset($tmplvarid)) $tmplvarid = 1;
if(!isset($like)) $like = '';

$ids_arr = array();

$query = $modx->newQuery('modTemplateVarResource');
$query->select($modx->getSelectColumns('modTemplateVarResource','modTemplateVarResource','',array('id','contentid')));
$query->where(array(
    'tmplvarid' => intval($tmplvarid),
    'value:LIKE' => '%'.$like.'%'
));
$query->sortby('contentid','ASC');

$results = $modx->getCollection('modTemplateVarResource',$query);

if($results){
    foreach ($results as $result) {
        array_push($ids_arr,$result->get('contentid'));
    }
}

return implode(',',$ids_arr);

?>