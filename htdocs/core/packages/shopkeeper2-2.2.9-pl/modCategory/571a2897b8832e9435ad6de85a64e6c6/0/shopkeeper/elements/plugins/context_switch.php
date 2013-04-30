<?php

/*
 plugin contextSwitch
 System event: OnHandleRequest
*/

if($modx->context->get('key') == 'mgr') return;

if($modx->event->name=='OnHandleRequest'){
    
    $cntxt_param = $modx->getOption('context_param_alias', null, 'c');
    $request_param_id = $modx->getOption('request_param_id', null, 'id');
    $friendly_urls = $modx->getOption('friendly_urls', null, true);
    $catalog_id = $modx->getOption('catalog_id',$scriptProperties,0);
    $context_key = $modx->getOption('context_key',$scriptProperties,'catalog');
    $site_start = $modx->getOption('site_start', null, 1);
    $request_uri = $_SERVER['REQUEST_URI'];
    $request_uri = substr($request_uri,0,1)=='/' ? substr($request_uri,1) : $request_uri;
    
    if($modx->config['friendly_urls']){
        
        if($request_uri){
        
            $base_dir_alias = current(explode('/',$request_uri));
            
            $context_child_ids = $modx->getChildIds(0,1,array('context'=>$context_key));
            
            $context_top_urls = array();
            foreach($context_child_ids as $id){
                $temp_url = basename($modx->makeURL($id,$context_key,'','abs'));
                array_push($context_top_urls,$temp_url);
            }
            
            if(in_array($base_dir_alias,$context_top_urls)){
                $modx->reloadContext($context_key);
                $modx->switchContext($context_key);
                $modx->config['site_start'] = $site_start;
                $modx->setPlaceholder('context_key',$context_key);
            }
        
        }
        
    }else{
        
        $requestId = !empty($_REQUEST[$request_param_id]) && is_numeric($_REQUEST[$request_param_id]) ? $_REQUEST[$request_param_id] : 0;
        if(!$requestId) return;
        $resource = $modx->getObject('modResource',$requestId);
        if($resource && $resource->get('context_key') == $context_key){
            $modx->reloadContext($context_key);
            $modx->switchContext($context_key);
            $modx->config['site_start'] = $site_start;
            $modx->setPlaceholder('context_key',$context_key);
        }
        
    }

}