<?php
/**
 * Load Javascript
 */

use OCP\Util;

$eventDispatcher = \OC::$server->getEventDispatcher();
$eventDispatcher->addListener('OCA\Files::loadAdditionalScripts', function(){
    Util::addScript('printer', 'printer.tabview' );
    Util::addScript('printer', 'printer.plugin' );
});
