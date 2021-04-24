<?php
/*
 * @version 2.0.0
 * @copyright Copyright (C) 2016 ArtMedia. All rights reserved.
 * @license OSCL, see http://www.oxwallplus.com/oscl
 * @website http://artmedia.biz.pl
 * @author Arkadiusz Tobiasz
 * @email kontakt@artmedia.biz.pl
 */

class LOCALIZATION_FORM_Settings extends Form
{    
    private $key;
    private $language;
    
    public function __construct() {
        $this->key = LOCALIZATION_BOL_Service::KEY;
        parent::__construct('settings');
        
        $this->language = OW::getLanguage();
        
        $detectLanguage = new ELEMENT_Checkbox('detectLanguage');
        $detectLanguage->setSwitch();
        $detectLanguage->setLabel($this->language->text($this->key, 'detect_language_from_browser'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($detectLanguage);
        
        $urlLanguage = new ELEMENT_Select('urlLanguage');
        $urlLanguage->setOptions(array(
            0 => $this->language->text($this->key, 'parameter'),
            1 => $this->language->text($this->key, 'in_domain'),
            2 => $this->language->text($this->key, 'subdomain'),
        ));
        $urlLanguage->setRequired();
        $urlLanguage->setDescription($this->language->text($this->key, 'display_type_desc'));
        $urlLanguage->setLabel($this->language->text($this->key, 'display_type'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($urlLanguage);
        
        $displayLanguage = new ELEMENT_Select('displayLanguage');
        $displayLanguage->setOptions(array(
            1 => $this->language->text($this->key, 'flag'),
            2 => $this->language->text($this->key, 'iso_code'),
            3 => $this->language->text($this->key, 'language_name'),
            4 => $this->language->text($this->key, 'flag').' + '.$this->language->text($this->key, 'iso_code'),
            5 => $this->language->text($this->key, 'flag').' + '.$this->language->text($this->key, 'language_name'),
        ));
        $displayLanguage->setRequired();
        $displayLanguage->setLabel($this->language->text($this->key, 'display_language'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($displayLanguage);
        
        $siteTimezone = new ELEMENT_Select('siteTimezone');
        $siteTimezone->setRequired(true);
        $siteTimezone->setSelect2();
        $siteTimezone->setLabel($this->language->text($this->key, 'timezone'), array('class' => 'col-sm-4 col-form-label'));
        $siteTimezone->setOptions(UTIL_DateTime::getTimezones());
        $this->addElement($siteTimezone);        
        
        $dateFormat = new ELEMENT_Text('dateFormat');
        $dateFormat->setHasInvitation($this->language->text($this->key, 'date_format_invitation'));
        $dateFormat->setDescription($this->language->text($this->key, 'date_format_description'));
        $dateFormat->setLabel($this->language->text($this->key, 'date_format'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($dateFormat);
        
        $timeFormat = new ELEMENT_Text('timeFormat');
        $timeFormat->setHasInvitation($this->language->text($this->key, 'time_format_invitation'));
        $timeFormat->setDescription($this->language->text($this->key, 'time_format_description'));
        $timeFormat->setLabel($this->language->text($this->key, 'time_format'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($timeFormat);
        
        $relativeTime = new ELEMENT_Checkbox('relativeTime');
        $relativeTime->setSwitch();
        $relativeTime->setDescription($this->language->text($this->key, 'relative_time_desc'));
        $relativeTime->setLabel($this->language->text($this->key, 'relative_time'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($relativeTime);
        
        $relativeTimeRange = new ELEMENT_Select('relativeTimeRange');
        $relativeTimeRange->setOptions(array(
            2 => sprintf($this->language->text('core', 'days'), 2),
            30 => sprintf($this->language->text('core', 'days'), 30),
            90 => sprintf($this->language->text('core', 'days'), 90),
            180 => sprintf($this->language->text('core', 'days'), 180),
            365 => sprintf($this->language->text('core', 'days'), 365),
        ));
        $relativeTimeRange->setRequired();
        $relativeTimeRange->setDescription($this->language->text($this->key, 'relative_time_range_desc'));
        $relativeTimeRange->setLabel($this->language->text($this->key, 'relative_time_range'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($relativeTimeRange);

        $militaryTime = new ELEMENT_Checkbox('militaryTime');
        $militaryTime->setSwitch();
        $militaryTime->setLabel($this->language->text($this->key, 'military_time'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($militaryTime);

        $options = array();
        $currencies = LOCALIZATION_BOL_Service::getInstance()->getCurrencies();
        foreach($currencies as $code => $name) {
            $options[$code] = $name.' ('.$code.')';
        }
        $defaultCurrency = new ELEMENT_Select('defaultCurrency');
        $defaultCurrency->setOptions($options);
        $defaultCurrency->setSelect2();
        $defaultCurrency->setRequired();
        $defaultCurrency->setLabel($this->language->text($this->key, 'default_currency'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($defaultCurrency);
        
        $submit = new ELEMENT_Button('submit');
        $submit->setValue($this->language->text('base', 'edit_button'));
        $this->addElement($submit);
    }
    
    public function processForm($data) {        
        unset($data['form_name']);
        unset($data['csrf_token']);
        
        $config = OW::getConfig();
        
        foreach($data as $name => $value) {
            if(is_int($value)) {
                $value = (int)$value;
            }
            $config->saveConfig($this->key, $name, $value);
        }
        return array('status' => 'success', 'message' => $this->language->text('admin', 'main_settings_updated'));
        
    }
}

