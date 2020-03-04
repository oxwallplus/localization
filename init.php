<?php
/*
 * @version 2.0.0
 * @copyright Copyright (C) 2016 ArtMedia. All rights reserved.
 * @license OSCL, see http://www.oxwallplus.com/oscl
 * @website http://artmedia.biz.pl
 * @author Arkadiusz Tobiasz
 * @email kontakt@artmedia.biz.pl
 */

$key = LOCALIZATION_BOL_Service::KEY;

$router = OW::getRouter();
$router->addRoute(new OW_Route($key.'.admin', 'admin/settings/localization', 'LOCALIZATION_CTRL_Admin', 'index'));

LOCALIZATION_CLASS_EventHandler::getInstance()->init();