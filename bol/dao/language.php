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

/**
 * Singleton. Language Data Access Object
 *
 * @author Aybat Duyshokov <duyshokov@gmail.com>
 * @package ow_system_plugins.base.bol
 * @since 1.0
 */
class LOCALIZATION_DAO_Language extends OW_BaseDao
{
    const CACHE_TAG_ALL_LANGUAGES = 'languages.find.all';
    const CACHE_TIME = '86400';
    /**
     * Class instance
     *
     * @var BOL_LanguageDao
     */
    private static $classInstance;

    /**
     * Returns class instance
     *
     * @return BOL_LanguageDao
     */
    public static function getInstance() {
        if(!isset(self::$classInstance))
            self::$classInstance = new self();

        return self::$classInstance;
    }

    /**
     * Class constructor
     *
     */
    protected function __construct()
    {
        parent::__construct();
    }
    
    /**
     * @see OW_BaseDao::getDtoClassName()
     *
     */
    public function getDtoClassName() {
        return 'LOCALIZATION_DTO_Language';
    }

    /**
     * @see OW_BaseDao::getTableName()
     *
     */
    public function getTableName() {
        return OW_DB_PREFIX . 'base_language';
    }

    /**
     * Enter description here...
     *
     * @param string $tag
     * @return BOL_Language
     */
    public function findByTag($tag) {
        $example = new OW_Example();
        $example->andFieldEqual('tag', trim($tag));

        return $this->findObjectByExample($example);
    }
    
    public function findByIsoCode($isoCode) {
        $example = new OW_Example();
        $example->andFieldLike('tag', $isoCode.'%');

        return $this->findObjectByExample($example);
    }

    public function findMaxOrder() {
        return $this->dbo->queryForColumn('SELECT MAX(`order`) FROM ' . $this->getTableName());
    }

    public function getCurrent() {
        $example = new OW_Example();
        $example->setOrder('`order` ASC')->setLimitClause(0, 1);

        return $this->findObjectByExample($example, self::CACHE_TIME, array(self::CACHE_TAG_ALL_LANGUAGES));
    }

    public function countActiveLanguages() {
        $example = new OW_Example();
        $example->andFieldEqual('status', 'active');

        return $this->countByExample($example, self::CACHE_TIME, array(self::CACHE_TAG_ALL_LANGUAGES));
    }

    public function findActiveList() {
        $example = new OW_Example();
        $example->andFieldEqual('status', 'active');

        return $this->findListByExample($example, self::CACHE_TIME, array(self::CACHE_TAG_ALL_LANGUAGES));
    }
    
    public function findAll($cacheLifeTime = 0, $tags = array()) {
        return parent::findAll(self::CACHE_TIME, array(self::CACHE_TAG_ALL_LANGUAGES));
    }
    
    public function clearCache() {
        OW::getCacheManager()->clean(array(self::CACHE_TAG_ALL_LANGUAGES));
    }
}