<?php
$corePath = MODX_CORE_PATH.'components/shopkeeper/';

switch ($modx->event->name) {
    //tv input
    case 'OnTVInputRenderList':
        $modx->event->output($corePath.'elements/tv/input/');
    break;
    case 'OnTVInputPropertiesList':
        $modx->event->output($corePath.'elements/tv/inputproperties/');
    break;
    //tv output
    case 'OnTVOutputRenderList':
        $modx->event->output($corePath.'elements/tv/output/');
    break;
    case 'OnTVOutputRenderPropertiesList':
        $modx->event->output($corePath.'elements/tv/outputproperties/');
    break;
}

return;