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

namespace Com\ExtremeIdea\Bidorbuy\StoreIntegrator\Core\Http;

/**
 * Class ParameterBag
 *
 * @package com\extremeidea\bidorbuy\storeintegrator\core\http
 */
class ParameterBag
{

    /**
     * Container
     *
     * @var array $parameters contains super-global variable
     */
    protected $parameters;

    /**
     * ParameterBag constructor.
     *
     * @param array $parameters
     *
     * @return void
     */
    public function __construct($parameters = array())
    {
        $this->parameters = $parameters;
    }

    /**
     * Returns the parameters.
     *
     * @return array An array of parameters
     */
    public function all()
    {
        return $this->parameters;
    }

    /**
     * Returns a parameter by name.
     *
     * @param string $key     The key
     * @param mixed  $default The default value if the parameter key does not exist
     *
     * @return mixed
     */
    public function get($key)
    {
        return array_key_exists($key, $this->parameters) ? $this->parameters[$key] : null;
    }

    /**
     * Sets a parameter by name.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     *
     * @return void
     */
    public function set($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     * Delete key from array
     *
     * @param string $key key in array
     *
     * @return void
     */
    public function remove($key)
    {
        unset($this->parameters[$key]);
    }
}
