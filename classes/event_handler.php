<?php
/*
 * @version 2.0.0
 * @copyright Copyright (C) 2016 ArtMedia. All rights reserved.
 * @license OSCL, see http://www.oxwallplus.com/oscl
 * @website http://artmedia.biz.pl
 * @author Arkadiusz Tobiasz
 * @email kontakt@artmedia.biz.pl
 */

class LOCALIZATION_CLASS_EventHandler
{
    private $key;
    private $eventManager;
    
    private static $classInstance;

    public static function getInstance() {
        if(self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }
    
    public function __construct() {
        $this->key = LOCALIZATION_BOL_Service::KEY;
        $this->eventManager = OW::getEventManager();
    }
    
    public function genericInit() {
        $this->eventManager->bind(OW_EventManager::ON_FINALIZE, [$this, 'onFinalize']);
    }

    public function init() {
        $this->genericInit();
    }
    
    public function onFinalize() {
        $document = OW::getDocument();
        $document->addStyleSheet(OW::getPluginManager()->getPlugin($this->key)->getStaticCssUrl() . 'famfamfam-flags.css', 'all', -100);
    }

}
