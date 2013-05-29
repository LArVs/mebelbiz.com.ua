<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderParamEdit extends modTemplateVarInputRender {
    public function process($value,array $params = array()) {
        $resource_id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0);
        if(!$resource_id && isset($_POST['ident'])) $resource_id = $_POST['ident'];
        $this->setPlaceholder('resource_tv_id',$this->tv->get('id').'_'.$resource_id);
        $this->setPlaceholder('tv_source',$this->tv->get('source'));
    }
    public function getTemplate() {
        return MODX_CORE_PATH.'components/shopkeeper/elements/tv/tpl/input_param-edit.tpl';
    }
}
return 'modTemplateVarInputRenderParamEdit';