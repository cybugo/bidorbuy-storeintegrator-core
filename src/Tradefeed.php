<?php

/**
 * Copyright (c) 2014, 2015, 2016 Bidorbuy http://www.bidorbuy.co.za
 * This software is the proprietary information of Bidorbuy.
 *
 * All Rights Reserved.
 * Modification, redistribution and use in source and binary forms, with or without
 * modification are not permitted without prior written approval by the copyright
 * holder.
 *
 * Vendor: EXTREME IDEA LLC http://www.extreme-idea.com
 */

namespace com\extremeidea\bidorbuy\storeintegrator\core;

/**
 * @SuppressWarnings(PHPMD.ConstantNamingConventions)
 */
class Tradefeed
{
    const XML_VERSION = '<?xml version=\'1.0\' encoding=\'UTF-8\'?>';

    const NAME_ROOT = 'ROOT';
    const NAME_VERSION = 'Version';
    const NAME_USER_ID = 'UserId';
    const NAME_PLUGIN_VERSION = 'PluginVersion';
    const NAME_SCHEMA_VERSION = 'SchemaVersion';
    const NAME_EXPORT_CREATED = 'ExportCreated';
    const NAME_PRODUCTS = 'Products';

    const NAME_PRODUCT = 'Product';
    const NAME_PRODUCT_ID = 'ID';
    const NAME_PRODUCT_NAME = 'ProductName';
    const NAME_PRODUCT_CODE = 'ProductCode';
    const NAME_PRODUCT_GTIN = 'ProductGTIN';
    const NAME_PRODUCT_CATEGORY = 'Category';
    const NAME_PRODUCT_PRICE = 'Price';
    const NAME_PRODUCT_MARKET_PRICE = 'MarketPrice';
    const NAME_PRODUCT_AVAILABLE_QTY = 'AvailableQty';
    const NAME_PRODUCT_CONDITION = 'Condition';
    const NAME_PRODUCT_ATTRIBUTES = 'ProductAttributes';
    const NAME_PRODUCT_SHIPPING_CLASS = 'ShippingProductClass';
    const NAME_PRODUCT_IMAGES = 'Images';
    const NAME_PRODUCT_IMAGE_URL = 'ImageURL';
    const NAME_PRODUCT_SUMMARY = 'ProductSummary';
    const NAME_PRODUCT_DESCRIPTION = 'ProductDescription';

    const NAME_BASE_URL = 'BaseUrl';

    const NAME_PRODUCT_ATTR_WIDTH = 'Width';
    const NAME_PRODUCT_ATTR_HEIGHT = 'Height';
    const NAME_PRODUCT_ATTR_LENGTH = 'Length';
    const NAME_PRODUCT_ATTR_DEPTH = 'Depth';
    const NAME_PRODUCT_ATTR_WEIGHT = 'Weight';
    const NAME_PRODUCT_ATTR_SHIPPING_WEIGHT = 'ShippingWeight';

    const CONDITION_NEW = 0;
    const CONDITION_SECONDHAND = 1;
    const CONDITION_REFURBISHED = 2;

    const CATEGORY_NAME_DELIMITER = "|";
    const CATEGORY_ID_DELIMITER = "-";

    const NAME_EXCLUDED_ATTRIBUTES = 'nameExcludedAttributes';
    const NAME_PRODUCT_EXCLUDED_ATTRIBUTES = 'nameProductExcludedAttributes';
    const NAME_ATTRIBUTES_ORDER = 'nameAttributesOrder';

    // Max field size. See query `getInstallTradefeedTableQuery` method
    const NAME_PRODUCT_GTIN_MAX_FIELD_SIZE = 65;

    private static $versionInstance;

    public static function createStartRootTag()
    {
        return self::XML_VERSION . PHP_EOL . self::tag(self::NAME_ROOT, true);
    }

    public static function createEndRootTag()
    {
        return self::tag(self::NAME_ROOT, false);
    }

    public static function createStartProductsTag()
    {
        return self::tag(self::NAME_PRODUCTS, true, 1);
    }

    public static function createEndProductsTag()
    {
        return self::tag(self::NAME_PRODUCTS, false, 1);
    }

    public static function createVersionSection()
    {
        $output = self::section(self::NAME_USER_ID, 1, false, 2);
        $output .= self::section(self::NAME_PLUGIN_VERSION, self::getLivePluginVersion(), true, 2);
        $output .= self::section(self::NAME_SCHEMA_VERSION, '1.1', false, 2);
        $output .= self::section(self::NAME_EXPORT_CREATED, date('c'), false, 2);

        return self::section(self::NAME_VERSION, $output, false, 1);
    }

    public static function createProductSection(&$data, &$settings = array())
    {
        if (is_array($data)) {
            $data = self::prepareProductArray($data, $settings);

            return self::buildXmlViewProduct($data);
        }
    }

    public static function section($tag, $value, $cdata = 1, $tabCount = 0, $forceNewLine = 0, $mandatory = 0)
    {
        $section = !is_null($value) ? trim($value) : '';

        $tab = '';
        for ($i = 1; $i <= $tabCount; $i++) {
            $tab .= "\t";
        }

        return (strlen($section) == 0 && !$mandatory
            ? ''
            : ($tab . '<' . $tag . '>' . ($cdata
                    ? self::cdata($section)
                    : (substr($section, 0, 1) == '<'
                    || $forceNewLine ? PHP_EOL . $value . $tab : $value)) . '</' . $tag . '>') . PHP_EOL);
    }

    private static function tag($tag, $open = 1, $tabCount = 0)
    {
        $tab = '';
        for ($i = 1; $i <= $tabCount; $i++) {
            $tab .= "\t";
        }

        return $tab . '<' . ($open ? '' : '/') . $tag . '>' . PHP_EOL;
    }

    private static function cdata($value)
    {
        return is_numeric($value) ? $value : '<![CDATA[' . $value . ']]>';
    }

    public static function sanitize($value)
    {
        $value = preg_replace('/[^a-zA-Z0-9]/', '', $value);

        while (!empty($value) && !ctype_alpha(substr($value, 0, 1))) {
            $value = substr($value, 1, strlen($value));
        }

        return ucfirst($value);
    }

    /**
     * Prepare product data
     *
     * @param array $data     array with data from storeintegrator platform
     * @param array $settings array
     *
     * @return mixed
     */
    public static function prepareProductArray(&$data, &$settings = array())
    {

        $defaults = array(
            self::NAME_PRODUCT_GTIN => '',
            self::NAME_PRODUCT_CATEGORY => null,
            self::NAME_PRODUCT_PRICE => null,
            self::NAME_PRODUCT_MARKET_PRICE => null,
            self::NAME_PRODUCT_AVAILABLE_QTY => null,
            self::NAME_PRODUCT_IMAGES => array(),
            self::NAME_PRODUCT_SUMMARY => null,
            self::NAME_PRODUCT_DESCRIPTION => null,
        );

        $data = array_merge($defaults, $data);

        $data[self::NAME_PRODUCT_GTIN] = ($data[self::NAME_PRODUCT_GTIN] > self::NAME_PRODUCT_GTIN_MAX_FIELD_SIZE)
            ? substr($data[self::NAME_PRODUCT_GTIN], 0, self::NAME_PRODUCT_GTIN_MAX_FIELD_SIZE)
            : $data[self::NAME_PRODUCT_GTIN];

        $nameExcludedAttributes =
            isset($settings[self::NAME_EXCLUDED_ATTRIBUTES]) ? $settings[self::NAME_EXCLUDED_ATTRIBUTES]
                : array();
        $items = isset($settings[self::NAME_ATTRIBUTES_ORDER])
            ? array_values($settings[self::NAME_ATTRIBUTES_ORDER]) : array();

        $nameAttributesOrder = array_fill_keys($items, '');

        // Dynamic product title and custom attributes
        $attributes = '';

        if (isset($data[self::NAME_PRODUCT_ATTRIBUTES]) && !empty($data[self::NAME_PRODUCT_ATTRIBUTES])) {
            foreach ($data[self::NAME_PRODUCT_ATTRIBUTES] as $name => $value) {
                // Some platforms allows to set many attributes with an identical name
                // Each one is coming inside a separate array to avoid the problem
                if (is_array($value)) {
                    if (!isset($value['name']) || !isset($value['value'])) {
                        // Defect #3718 Notice: Undefined index:
                        continue;
                    }
                }

                $label = self::sanitize(self::getAttrName($name, $value));
                if (strlen($label) > 0) {
                    $nameAttributesOrder[] = array($label => ucfirst(self::getAttrValue($value)));
                }
            }
            $data[self::NAME_PRODUCT_EXCLUDED_ATTRIBUTES] =
                isset($data[self::NAME_PRODUCT_EXCLUDED_ATTRIBUTES]) ? $data[self::NAME_PRODUCT_EXCLUDED_ATTRIBUTES]
                    : array();
            $data[self::NAME_PRODUCT_NAME] .= self::getTitleAppendix(
                $data[self::NAME_PRODUCT_ATTRIBUTES],
                array_merge($nameExcludedAttributes, $data[self::NAME_PRODUCT_EXCLUDED_ATTRIBUTES])
            );
        }

        foreach ($nameAttributesOrder as $v) {
            list($k, $v) = each($v);
            if (self::isMeasurable($k)) {
                $value = self::formatPrice($v);
                $units = self::getUnits(self::getAttrValue($v));

                // Don't show 0 values
                $v = doubleval($value) > 0 ? $value . $units : '';
            }

            $attributes .= self::section($k, $v, true, 4);
        }

        $data[self::NAME_PRODUCT_ATTRIBUTES] = $attributes;

        $data[self::NAME_PRODUCT_PRICE] =
            isset($data[self::NAME_PRODUCT_PRICE]) && strlen($data[self::NAME_PRODUCT_PRICE]) > 0
                ? self::formatPrice($data[self::NAME_PRODUCT_PRICE]) : null;

        $data[self::NAME_PRODUCT_MARKET_PRICE] =
            isset($data[self::NAME_PRODUCT_MARKET_PRICE]) && strlen($data[self::NAME_PRODUCT_MARKET_PRICE]) > 0
                ? self::formatPrice($data[self::NAME_PRODUCT_MARKET_PRICE]) : null;

        $data[self::NAME_PRODUCT_AVAILABLE_QTY] =
            isset($data[self::NAME_PRODUCT_AVAILABLE_QTY]) && strlen($data[self::NAME_PRODUCT_AVAILABLE_QTY]) > 0
                ? intval(ceil($data[self::NAME_PRODUCT_AVAILABLE_QTY])) : null;

        /* Product Condition */
        $data[self::NAME_PRODUCT_CONDITION] = isset($data[self::NAME_PRODUCT_CONDITION])
            ? $data[self::NAME_PRODUCT_CONDITION] : self::CONDITION_SECONDHAND;

        $data[self::NAME_PRODUCT_CONDITION] = self::setProductCondition($data[self::NAME_PRODUCT_CONDITION]);
        /********************/

        $data[self::NAME_PRODUCT_IMAGE_URL] = isset($data[self::NAME_PRODUCT_IMAGE_URL])
            ? self::escapeImageUrl($data[self::NAME_PRODUCT_IMAGE_URL]) : null;

        /* FEATURE 3909*/
        //self::excludeImagesWithHttp(self::escapeImageUrl($data[self::nameProductImageURL])) : null;
        /* END FEATURE 3909*/

        /* Images section */
        if (!isset($data[self::NAME_PRODUCT_IMAGES]) || !is_array($data[self::NAME_PRODUCT_IMAGES])) {
            $data[self::NAME_PRODUCT_IMAGES] = array();
        }

        foreach ($data[self::NAME_PRODUCT_IMAGES] as &$image) {
            $image = self::escapeImageUrl($image);
        }

        $baseURL = isset($data[self::NAME_BASE_URL]) ? $data[self::NAME_BASE_URL] : '';

        $images = array_unique(
            array_merge(
                $data[self::NAME_PRODUCT_IMAGES],
                self::getImagesFromDescription($data[self::NAME_PRODUCT_SUMMARY], $baseURL),
                self::getImagesFromDescription($data[self::NAME_PRODUCT_DESCRIPTION], $baseURL)
            )
        );
        /* FEATURE 3909*/
        //$data[self::nameProductImages] = self::excludeImagesWithHttp($images);
        /* END 3909   */
        $data[self::NAME_PRODUCT_IMAGES] = $images;

        self::swapSummaryDescription($data[self::NAME_PRODUCT_SUMMARY], $data[self::NAME_PRODUCT_DESCRIPTION]);

        return $data;
    }

    /** Process ProductSummary and ProductDescriptions in accordance with a client's rules.
     *
     * @param string $summary
     * @param string $description
     */
    public static function swapSummaryDescription(&$summary = '', &$description = '')
    {
        $summary = !isset($summary) || strlen($summary) == 0 ? $description : $summary;
        $description = !isset($description) || strlen($description) == 0 ? $summary : $description;

        $summary = self::encode2utf8($summary);
        $description = self::encode2utf8($description);
        $fullDescription = $description;

        if (strlen($summary) > 0) {
            $isUTF8 = mb_detect_encoding($summary, 'utf-8');
            $description = self::subString($description, 0, 8000, $isUTF8);
            $stripped = self::removeHtmlCharacters($summary);
            $summary = self::subString(
                (strlen($stripped) > 0 ? $stripped : self::removeHtmlCharacters($description)),
                0,
                500,
                $isUTF8
            );
        }

        /*
         * return full description
         * @string
         */

        return $fullDescription;
    }

    /**
     * Helper function
     *
     * @param string  $string   string
     * @param integer $start    start pos
     * @param integer $length   max length
     * @param string  $encoding encoding
     *
     * @return mixed
     */
    private static function subString($string, $start, $length, $encoding)
    {
        if ($encoding) {
            $string = mb_substr($string, $start, $length, $encoding);
            $result = mb_strlen($string) == $length ?
                $string = mb_substr($string, $start, mb_strrpos($string, ' ', 0, $encoding), $encoding) : $string;

            return $result;
        }

        $string = substr($string, $start, $length);
        $result = strlen($string) == $length ? $string = substr($string, $start, strrpos($string, ' ', 0)) : $string;

        return $result;
    }

    /**
     * Format Price
     *
     * @param string $value value
     *
     * @return string
     */
    public static function formatPrice($value)
    {
        $value = preg_replace('/[^0-9.-]/', '', $value);

        return !is_numeric($value) ? '' : number_format($value, 2, '.', '');
    }

    public static function encode2utf8($string)
    {
        $isUTF8 = mb_check_encoding($string, 'utf-8');
        if (!$isUTF8) {
            $tmpString = str_split($string);

            $string = '';
            foreach ($tmpString as &$char) {
                $string .= utf8_encode($char);
            }
        }

        return preg_replace('/[\x00-\x1F\x7F-\xA0]/u', '', $string);
    }

    public static function removeHtmlCharacters($string)
    {
        $translationTable = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);

        foreach ($translationTable as $char => $entity) {
            $from[] = $entity;
            $to[] = mb_convert_encoding($entity, "UTF-8", "HTML-ENTITIES");
            $from[] = '&#' . ord($char) . ';';
            $to[] = mb_convert_encoding($entity, "UTF-8", "HTML-ENTITIES");
        }

        $clear = str_replace($from, $to, $string);
        $clear = filter_var($clear, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_NO_ENCODE_QUOTES);
        $clear = preg_replace("/(&[#0-9a-zA-Z]+;)/", '', $clear);

        return trim($clear);
    }

    /**
     * Escape image url
     *
     * @param string $imageUrl url
     *
     * @return string
     */
    private static function escapeImageUrl($imageUrl)
    {
        if (isset($imageUrl) && strlen($imageUrl) > 0) {
            $urlComponents = parse_url($imageUrl);
            $imageUrl = self::joinUrl($urlComponents, true);
        }

        return $imageUrl;
    }

    /**
     * Join URL
     *
     * @param string $parts  parts
     * @param string $encode encode
     *
     * @return string
     */
    private static function joinUrl($parts, $encode)
    {
        // TODO: add brackets for each condition

        if ($encode) {
            if (isset($parts['user'])) {
                $parts['user'] = rawurlencode($parts['user']);
            }

            if (isset($parts['pass'])) {
                $parts['pass'] = rawurlencode($parts['pass']);
            }

            if (isset($parts['host']) && !preg_match('!^(\[[\da-f.:]+\]])|([\da-f.:]+)$!ui', $parts['host'])) {
                $parts['host'] = rawurlencode($parts['host']);
            }

            if (!empty($parts['path'])) {
                $parts['path'] = preg_replace('!%2F!ui', '/', rawurlencode($parts['path']));
            }

            if (isset($parts['query'])) {
                $params = explode('=', $parts['query']);
                foreach ($params as &$v) {
                    $v = rawurlencode($v);
                }

                $parts['query'] = implode('=', $params);
            }

            if (isset($parts['fragment'])) {
                $parts['fragment'] = rawurlencode($parts['fragment']);
            }
        }

        $url = '';
        if (!empty($parts['scheme'])) {
            $url .= $parts['scheme'] . ':';
        }

        if (isset($parts['host'])) {
            $url .= '//';

            if (isset($parts['user'])) {
                $url .= $parts['user'];
                if (isset($parts['pass'])) {
                    $url .= ':' . $parts['pass'];
                }
                $url .= '@';
            }

            $url .= (preg_match('!^[\da-f]*:[\da-f.:]+$!ui', $parts['host'])) ? '[' . $parts['host'] . ']'
                : $parts['host'];

            if (isset($parts['port'])) {
                $url .= ':' . $parts['port'];
            }
            if (!empty($parts['path']) && $parts['path'][0] != '/') {
                $url .= '/';
            }
        }

        if (!empty($parts['path'])) {
            $url .= $parts['path'];
        }

        if (isset($parts['query'])) {
            $url .= '?' . $parts['query'];
        }

        if (isset($parts['fragment'])) {
            $url .= '#' . $parts['fragment'];
        }

        return $url;
    }

    /**
     * Returns string with attributes values in order to to use in the product title
     *
     * @param array $attributes         attributes
     * @param array $excludedAttributes exclude attributes
     *
     * @return string
     */
    public static function getTitleAppendix($attributes, $excludedAttributes = array())
    {
        $titleAppendix = array();
        array_walk($excludedAttributes, array(
            __CLASS__,
            'getAttrName'
        ));
        $excludedAttributes = array_map(array(
            __CLASS__,
            'sanitize'
        ), $excludedAttributes);
        foreach ($attributes as $name => $value) {
            $name = self::sanitize(self::getAttrName($name, $value));
            if (!in_array($name, $excludedAttributes) && strlen(trim(self::getAttrValue($value))) > 0) {
                if (self::isMeasurable($name)) {
                    $number = self::formatPrice(self::getAttrValue($value));
                    $number = fmod(doubleval($number), 1) > 0 ? $number : intval($number);

                    $units = self::getUnits(self::getAttrValue($value));

                    // Don't add 0 values to Title
                    if (doubleval($number) <= 0) {
                        continue;
                    }

                    $value = $number . $units;
                }

                $titleAppendix[] = array(ucfirst($name) => ucfirst(self::getAttrValue($value)));
            }
        }

        //Only one weight type (Weight or ShippingWeight) should come into a title.
        // ShippingWeight should be added in case when Weight is not set.
        $issetWeight = false;
        $issetShippingWeight = false;
        $shippingWeightKey = null;
        foreach ($titleAppendix as $key => $value) {
            if (array_key_exists(self::NAME_PRODUCT_ATTR_WEIGHT, $value)) {
                $issetWeight = true;
            }
            if (array_key_exists(self::NAME_PRODUCT_ATTR_SHIPPING_WEIGHT, $value)) {
                $issetShippingWeight = true;
                $shippingWeightKey = $key;
            }
        }

        if ($issetShippingWeight && $issetWeight && isset($shippingWeightKey) && is_numeric($shippingWeightKey)) {
            unset($titleAppendix[$shippingWeightKey]);
        }

        if (!empty($titleAppendix)) {
            return ' - ' . implode(' ', array_map('array_pop', $titleAppendix));
        }

        return '';
    }

    /**
     * Returns name of attribute. Some platforms allows to set many attributes with an identical name.
     * Each one is coming inside a separate array to avoid the problem
     *
     * @param mixed $name  name
     * @param array $value value
     *
     * @return string Returns name of attribute as a string
     */
    public static function getAttrName($name, $value)
    {
        if (is_array($value)) {
            $name = isset($value['name']) ? $value['name'] : '';
        }

        return $name;
    }

    /**
     * Returns scalar value of attribute. Some platforms allows to set many attributes with an identical name.
     * Each one is coming inside a separate array to avoid the problem
     *
     * @param mixed $value value
     *
     * @return mixed Returns scalar value of attribute
     */
    public static function getAttrValue($value)
    {
        if (is_array($value)) {
            return isset($value['value']) ? $value['value'] : '';
        }

        return $value;
    }

    /**
     * Returns units of measurable value (weight, length, width etc)
     *
     * @param string $value $value is a string like "10.698 kg"
     *
     * @return string Units. For example: kg, lbs, m, km etc
     */
    public static function getUnits($value)
    {
        return preg_replace('/[^a-zA-Z]/', '', (string)$value);
    }

    public static function getImagesFromDescription($desc, $base_url = '')
    {
        $desc = self::encode2utf8($desc);
        $images = array();
        $pattern = '/<img[^\>\<]+\>/';

        $isUTF8 = mb_detect_encoding($desc, 'utf-8');
        $matches = array();

        if ($isUTF8) {
            $pattern .= 'u'; /* u - means UTF-8 support. */
        }

        if (self::strPosition($desc, '<img', $isUTF8) !== false) {
            preg_match_all($pattern, $desc, $matches);
        }

        if (!empty($matches)) {
            libxml_use_internal_errors(true);
            foreach ($matches[0] as $img) {
                //We need to parse each <img> separately because DOMDocument() parses corrupted tags unobviously
                // and unexpectedly if we are parsing whole $description.
                $doc = new \DOMDocument();
                $doc->loadHTML('<?xml encoding="UTF-8">' . $img);

                $tags = $doc->getElementsByTagName('img');

                foreach ($tags as $tag) {
                    $tmpImage = $tag->getAttribute('src');
                    $images[] = strpos($tmpImage, 'data:image') === false ? $tmpImage : null;
                }
            }
        }

        if (!empty($images)) {
            foreach ($images as $k => $i) {
                if (strpos(strtolower($i), 'http') !== 0 && !empty($base_url)) {
                    $i = $base_url . '/' . $i;
                }
                $images[$k] = self::escapeImageUrl($i);
            }
        }

        return $images;
    }

    /**
     * String Position
     *
     * @param string $haystack haystack
     * @param string $needle   needle
     * @param mixed  $encoding encoding
     *
     * @return mixed
     */
    private static function strPosition($haystack, $needle, $encoding)
    {
        if ($encoding) {
            return mb_strpos($haystack, $needle);
        }

        return strpos($haystack, $needle);
    }

    /**
     * Is Measurable
     *
     * @param string $valueName Value Name
     *
     * @return mixed
     */
    public static function isMeasurable($valueName)
    {
        return in_array(ucfirst($valueName), array(
            self::NAME_PRODUCT_ATTR_WIDTH,
            self::NAME_PRODUCT_ATTR_HEIGHT,
            self::NAME_PRODUCT_ATTR_LENGTH,
            self::NAME_PRODUCT_ATTR_DEPTH,
            self::NAME_PRODUCT_ATTR_WEIGHT,
            self::NAME_PRODUCT_ATTR_SHIPPING_WEIGHT
        ));
    }

    /**
     * Build Xml View Product
     *
     * @param array $data data
     *
     * @return string
     */
    private static function buildXmlViewProduct(&$data)
    {
        $output = self::section(self::NAME_PRODUCT_ID, $data[self::NAME_PRODUCT_ID], true, 3);
        $output .= self::section(self::NAME_PRODUCT_CODE, $data[self::NAME_PRODUCT_CODE], true, 3);
        $output .= self::section(self::NAME_PRODUCT_GTIN, $data[self::NAME_PRODUCT_GTIN], false, 3, false, true);
        $output .= self::section(self::NAME_PRODUCT_NAME, $data[self::NAME_PRODUCT_NAME], true, 3);
        $output .= self::section(self::NAME_PRODUCT_CATEGORY, $data[self::NAME_PRODUCT_CATEGORY], true, 3);
        $output .= self::section(self::NAME_PRODUCT_PRICE, $data[self::NAME_PRODUCT_PRICE], true, 3);
        $output .= self::section(self::NAME_PRODUCT_MARKET_PRICE, $data[self::NAME_PRODUCT_MARKET_PRICE], true, 3);
        $output .= self::section(self::NAME_PRODUCT_AVAILABLE_QTY, $data[self::NAME_PRODUCT_AVAILABLE_QTY], true, 3);
        $output .= self::section(self::NAME_PRODUCT_CONDITION, $data[self::NAME_PRODUCT_CONDITION], false, 3);

        if (isset($data[self::NAME_PRODUCT_IMAGE_URL])) {
            $output .= self::section(self::NAME_PRODUCT_IMAGE_URL, $data[self::NAME_PRODUCT_IMAGE_URL], true, 3);
        }

        if (isset($data[self::NAME_PRODUCT_IMAGES]) && is_array($data[self::NAME_PRODUCT_IMAGES])) {
            $outputImages = '';
            foreach ($data[self::NAME_PRODUCT_IMAGES] as $image) {
                $outputImages .= self::section(self::NAME_PRODUCT_IMAGE_URL, $image, true, 4);
            }
            $output .= self::section(self::NAME_PRODUCT_IMAGES, $outputImages, false, 3);
        }

        $summary = !empty($data[self::NAME_PRODUCT_SUMMARY])
            ? $data[self::NAME_PRODUCT_SUMMARY] : $data[self::NAME_PRODUCT_NAME];

        $output .= self::section(self::NAME_PRODUCT_SUMMARY, $summary, true, 3);

        $strippedDescription = strip_tags($data[self::NAME_PRODUCT_DESCRIPTION], '<img>');
        $output .= self::section(
            self::NAME_PRODUCT_DESCRIPTION,
            !empty($strippedDescription) ? $data[self::NAME_PRODUCT_DESCRIPTION] : $data[self::NAME_PRODUCT_NAME],
            true,
            3
        );

        if (isset($data[self::NAME_PRODUCT_SHIPPING_CLASS])) {
            $output .= self::section(
                self::NAME_PRODUCT_SHIPPING_CLASS,
                $data[self::NAME_PRODUCT_SHIPPING_CLASS],
                true,
                3
            );
        }

        $output .= self::section(self::NAME_PRODUCT_ATTRIBUTES, $data[self::NAME_PRODUCT_ATTRIBUTES], false, 3);

        return self::section(self::NAME_PRODUCT, $output, false, 2);
    }

    public static function getLivePluginVersion()
    {

        if (is_null(self::$versionInstance)) {
            self::$versionInstance = new Version();
        }

        return self::$versionInstance->getLivePluginVersion();
    }

    /**
     * Exclude images witch use http protocol
     *
     * @param mixed $images full url, array or string
     *
     * @return mixed array or string
     */
    public static function excludeImagesWithHttp($images)
    {

        if (is_string($images)) {
            return self::isHttps($images) ? $images : '';
        }

        foreach ($images as $key => $image) {
            if (!self::isHttps($image)) {
                unset($images[$key]);
            }
        }

        return $images;
    }

    /**
     * Set product condition
     *
     * @param integer $condition product condition(0-New, 1-Refurbished, 2-Secondhand)
     *
     * @return string
     */
    protected static function setProductCondition($condition)
    {

        switch (intval($condition)) {
            case self::CONDITION_NEW:
                $result = 'New';
                break;
            case self::CONDITION_REFURBISHED:
                $result = 'Refurbished';
                break;
            case self::CONDITION_SECONDHAND:
            default:
                $result = 'Secondhand';
        }

        return $result;
    }

    /**
     * END FEATURE 3909
     */
}
