<?php /*
 * #%L
 * Bidorbuy http://www.bidorbuy.co.za
 * %%
 * Copyright (C) 2014 - 2018 Bidorbuy http://www.bidorbuy.co.za
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

namespace com\extremeidea\bidorbuy\storeintegrator\core\http;

/**
 * Class HttpRequest
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class Request
{

    /**
     * Super-global $_SERVER warped in class
     *
     * @var ServerBag $server super-global $_SERVER
     */
    public $server;

    /**
     * Request constructor.
     *
     * @return static
     */
    public function __construct()
    {

        $this->server = new ServerBag($_SERVER);
    }
}
