<?php /*
 * #%L
 * Bidorbuy http://www.bidorbuy.co.za
 * %%
 * Copyright (C) 2014 - 2019 Bidorbuy http://www.bidorbuy.co.za
 * %%
 * This software is the proprietary information of Bidorbuy.
 *
 * All Rights Reserved.
 * Modification, redistribution and use in source and binary forms, with or without
 * modification are not permitted without prior written approval by the copyright
 * holder.
 *
 * Vendor: EXTREME IDEA LLC http://www.extreme-idea.com
 * #L%
 */ ?>
<?php

namespace Com\ExtremeIdea\Bidorbuy\StoreIntegrator\Core;

/**
 * Class StaticHolder
 */
class StaticHolder
{

    /**
     * Core Object
     *
     * @var Core $bidorbuyStoreIntegrator
     *
     * @return Core
     */
    private static $bidorbuyStoreIntegrator = null;

    /**
     * Warnings Object
     *
     * @var Warnings $warnings contains system and bussines warnings from Warnings class
     */
    public static $warnings = null;


    /**
     * Core Signleton
     *
     * @return Core
     */
    public static function &getBidorbuyStoreIntegrator()
    {
        if (null === static::$bidorbuyStoreIntegrator) {
            static::$bidorbuyStoreIntegrator = new Core();
        }

        return self::$bidorbuyStoreIntegrator;
    }

    /**
     * Warning Signleton
     *
     * @return Warnings
     */
    public static function getWarnings()
    {
        if (null === static::$warnings) {
            static::$warnings = new Warnings();
        }

        return self::$warnings;
    }
}
