<?php
/**
 * EXHIBIT A. Common Public Attribution License Version 1.0
 * The contents of this file are subject to the Common Public Attribution License Version 1.0 (the “License”);
 * you may not use this file except in compliance with the License. You may obtain a copy of the License at
 * http://www.oxwall.org/license. The License is based on the Mozilla Public License Version 1.1
 * but Sections 14 and 15 have been added to cover use of software over a computer network and provide for
 * limited attribution for the Original Developer. In addition, Exhibit A has been modified to be consistent
 * with Exhibit B. Software distributed under the License is distributed on an “AS IS” basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for the specific language
 * governing rights and limitations under the License. The Original Code is Oxwall software.
 * The Initial Developer of the Original Code is Oxwall Foundation (http://www.oxwall.org/foundation).
 * All portions of the code written by Oxwall Foundation are Copyright (c) 2011. All Rights Reserved.

 * EXHIBIT B. Attribution Information
 * Attribution Copyright Notice: Copyright 2011 Oxwall Foundation. All rights reserved.
 * Attribution Phrase (not exceeding 10 words): Powered by Oxwall community software
 * Attribution URL: http://www.oxwall.org/
 * Graphic Image as provided in the Covered Code.
 * Display of Attribution Information is required in Larger Works which are defined in the CPAL as a work
 * which combines Covered Code or portions thereof with code not governed by the terms of the CPAL.
 */
class LOCALIZATION_CMP_ConsoleSwitchLanguage extends BASE_CMP_ConsoleDropdownHover
{
    /**
     * Constructor.
     *
     */
    public function __construct() {
        parent::__construct();
        
        $service = LOCALIZATION_BOL_LanguageService::getInstance();
        if($service->countActiveLanguages() <= 1) {
            $this->setVisible(false);
            return;
        }
        
        $config = OW::getConfig();
        $pluginKey = LOCALIZATION_BOL_Service::KEY;
        
        $languages = $service->getLanguages();
        $currentLanguage = $service->getCurrent();
        $defaultLanguage = $service->findDefault();
        $active_languages = array();
        
        $urlLanguage = (int)$config->getValue($pluginKey, 'urlLanguage');
        $displayLanguage = (int)$config->getValue($pluginKey, 'displayLanguage');
        if($urlLanguage) {
            $router = OW::getRouter();
            $uri = $router->getUri();
            if($urlLanguage == 1) {
                $baseUrl = OW_URL_HOME;
            } else {
                $parseUrl = parse_url(OW_URL_HOME);
                $baseUrl = $parseUrl['host'];
                $schemeUrl = $parseUrl['scheme'];
            }
        }

        foreach($languages as $id => $language) {
            if($language->getStatus() == 'active') {
                $result = $service->getIsoCode($language->getTag());
                
                if($currentLanguage->getId() == $language->getId()) {
                    if(in_array($displayLanguage, array(2, 4))) {
                        $this->assign('label', $result['country']);
                    }
                    if(in_array($displayLanguage, array(3, 5))) {
                        $this->assign('label', $language->getLabel());
                    }
                    if(in_array($displayLanguage, array(1, 4, 5))) {
                        $this->assign('icon', $result['code']);
                    }
                } else {
                    if($urlLanguage) {
                        if($defaultLanguage->getId() == $language->getId()) {
                            $url = OW_URL_HOME;
                        } else {
                            if($urlLanguage == 1) {
                                $url = $baseUrl . $result['code'] . DS . $uri;
                            } else {
                                $url = $schemeUrl . '://' . $result['code'] . '.' . $baseUrl . DS . $uri;
                            }
                        }
                    } else {
                        $url = OW::getRequest()->buildUrlQueryString(null, array("language_id" => $language->getId()));
                    }
                    
                    $icon = null;
                    $label = null;
                    if(in_array($displayLanguage, array(2, 4))) {
                        $label = $result['country'];
                    }
                    if(in_array($displayLanguage, array(3, 5))) {
                        $label = $language->getLabel();
                    }
                    if(in_array($displayLanguage, array(1, 4, 5))) {
                        $icon = $result['code'];
                    }
                    $active_languages[] = array(
                        'id' => $language->getId(),
                        'label' => $label,
                        'order' => $language->getOrder(),
                        'tag' => $language->getTag(),
                        'icon' => $icon,
                        'url' => $url,
                        'is_current' => false
                    );
                }
            }
        }        

        function sortActiveLanguages($lang1, $lang2) {
            return ($lang1['order'] < $lang2['order']) ? -1 : 1;
        }
        usort($active_languages, 'sortActiveLanguages');

        $switchLanguage = new LOCALIZATION_CMP_SwitchLanguage($active_languages);
        $this->setContent($switchLanguage->render());
    }

    protected function initJs() {
        $js = UTIL_JsGenerator::newInstance();
        $js->addScript('OW.Console.addItem(new OW_ConsoleDropdownHover({$uniqId}, {$contentIniqId}), {$key});', array(
            'key' => $this->getKey(),
            'uniqId' => $this->consoleItem->getUniqId(),
            'contentIniqId' => $this->consoleItem->getContentUniqId()
        ));

        OW::getDocument()->addOnloadScript($js);
    }

}
