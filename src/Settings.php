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

namespace com\extremeidea\bidorbuy\storeintegrator\core;

/**
 * Class Settings
 *
 * @package com\extremeidea\bidorbuy\storeintegrator\core
 *
 * todo refactoring code to remove two public functions, now 62
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class Settings
{
    const NAME = 'bobsiSettings';
    const NAME_VERSION = 'version';
    const NAME_USERNAME = 'username';
    const NAME_PASSWORD = 'password';
    const NAME_PASSWORD_PREFIX = 'base64:';

    const NAME_CURRENCY = 'currency';
    const NAME_FILENAME = 'filename';
    const NAME_COMPRESS_LIBRARY = 'compressLibrary';
    const NAME_DEFAULT_STOCK_QUANTITY = 'defaultStockQuantity';
    const NAME_LOGGING_LEVEL = 'loggingLevel';
    const NAME_LOGGING_APPLICATION = 'loggingApplication';
    const NAME_LOGGING_APPLICATION_OPTION_ALL = 'all';
    const NAME_LOGGING_APPLICATION_OPTION_PHP = 'php';
    const NAME_LOGGING_APPLICATION_OPTION_EXTENSION = 'extension';

    const NAME_EXPORT_QUANTITY_MORE_THAN = 'exportQuantityMoreThan';
    const NAME_EXCLUDE_CATEGORIES = 'excludeCategories';
    const NAME_INCLUDE_ALLOW_OFFERS_CATEGORIES = 'includeAllowOffersCategories';
    const NAME_EXPORT_STATUSES = 'exportStatuses';
    const NAME_EXPORT_VISIBILITIES = 'exportVisibilities';

    const NAME_TOKEN_DOWNLOAD = 'tokenDownloadUrl';
    const NAME_TOKEN_EXPORT = 'tokenExportUrl';

    const NAME_LOGGING_FORM = 'loggingForm';
    const NAME_LOGGING_FORM_ACTION = 'loggingFormAction';
    const NAME_LOGGING_FORM_BUTTON = 'loggingFormButton';
    const NAME_LOGGING_FORM_FILENAME = 'loggingFormFilename';
    const NAME_LOGGING_FORM_ACTION_DOWNLOAD = 'download';
    const NAME_LOGGING_FORM_ACTION_REMOVE = 'remove';

    const PARAM_TOKEN = 't';
    const PARAM_CATEGORY = 'c';
    const PARAM_CALLBACK_EXPORT_PRODUCTS = 'callbackExportProducts';
    const PARAM_CALLBACK_GET_PRODUCTS = 'callbackGetProducts';
    const PARAM_CALLBACK_GET_BREADCRUMB = 'callbackGetBreadcrumb';
    const PARAM_CATEGORIES = 'categories';
    const PARAM_ALLOW_OFFERS_CATEGORIES = 'allowOffersCategories';
    const PARAM_ITEMS_PER_ITERATION = 'itemsPerIteration';
    const PARAM_ITERATION = 'iteration';
    const PARAM_REVISION = 'revision';
    const PARAM_CATEGORY_ID = 'categoryId';
    const PARAM_VARIATION_ID = 'variationId';
    const PARAM_IDS = 'ids';
    const PARAM_PRODUCT_STATUS = 'productStatus';
    const PARAM_TIME_START = 'timestart';
    const PARAM_CATEGORY_BREADCRUMB = 'categoryBreadcrumb';
    const PARAM_EXTENSIONS = 'extensions';

    const NAME_WORDINGS = self::NAME;
    const NAME_WORDINGS_TITLE = 'title';
    const NAME_WORDINGS_DESCRIPTION = 'description';
    const NAME_WORDINGS_VALIDATOR = 'validator';
    const NAME_WORDINGS_VALIDATOR_ERROR = 'validatorError';

    const NAME_EXPORT_CONFIGURATION = 'exportConfiguration';
    const NAME_EXPORT_CRITERIA = 'exportCriteria';
    const NAME_EXPORT_LINKS = 'exportLinks';
    const NAME_ADVANCED_SETTINGS = 'advanced';

    const NAME_EXPORT_URL = 'exportUrl';
    const NAME_DOWNLOAD_URL = 'downloadUrl';
    const NAME_BUTTON_EXPORT = 'exportTradefeed';
    const NAME_BUTTON_DOWNLOAD = 'downloadTradefeed';
    const NAME_BUTTON_RESET = 'resetExportUrls';
    const NAME_BUTTON_RESET_AUDIT = 'resetExportTables';
    const NAME_ACTION_RESET = 'resetTokens';
    const NAME_ACTION_RESET_EXPORT_TABLES = 'resetaudit';
    /*
     * Feature #3750
     */
    const NAME_EXPORT_PRODUCT_SUMMARY = 'ProductSummaryExport';
    const NAME_EXPORT_PRODUCT_DESCRIPTION = 'ProductDescriptionExport';
    /*
     * End Feature Block
     */

    public static $coreAssetsPath;
    public static $dataPath;
    public static $logsPath;

    public static $storeEmail = '';
    public static $storeName = '';

    private $settings;
    private $defaults;

    public function __construct()
    {
        $this->defaults = array(
            self::NAME_VERSION => '1.0',
            self::NAME_USERNAME => '',
            self::NAME_PASSWORD => '',
            self::NAME_CURRENCY => '',
            self::NAME_FILENAME => 'tradefeed',
            self::NAME_COMPRESS_LIBRARY => 'none',
            self::NAME_DEFAULT_STOCK_QUANTITY => 5,
            self::NAME_EXPORT_QUANTITY_MORE_THAN => 0,
            self::NAME_EXPORT_STATUSES => array(),
            self::NAME_EXPORT_VISIBILITIES => array(),
            self::NAME_EXCLUDE_CATEGORIES => array(),
            self::NAME_INCLUDE_ALLOW_OFFERS_CATEGORIES => array(),
            self::NAME_LOGGING_LEVEL => 'error',
            self::NAME_LOGGING_APPLICATION => self::NAME_LOGGING_APPLICATION_OPTION_EXTENSION,
            self::NAME_TOKEN_DOWNLOAD => self::generateToken(),
            self::NAME_TOKEN_EXPORT => self::generateToken(),
            /*
                        * Feature #3750
                        */
            self::NAME_EXPORT_PRODUCT_SUMMARY => true,
            self::NAME_EXPORT_PRODUCT_DESCRIPTION => true,

            /*
             * End Feature Block
             */

            self::NAME_WORDINGS => array(
                self::NAME_USERNAME => array(
                    self::NAME_WORDINGS_TITLE => 'Username',
                    self::NAME_WORDINGS_DESCRIPTION => 'Please specify the username if your platform is protected by 
                    <a href=\'http://en.wikipedia.org/wiki/Basic_access_authentication\' 
                    target=\'_blank\'>Basic Access Authentication</a>',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return true;
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateTrue'
                    ),
                ),
                self::NAME_PASSWORD => array(
                    self::NAME_WORDINGS_TITLE => 'Password',
                    self::NAME_WORDINGS_DESCRIPTION => 'Please specify the password if your platform is protected by 
                    <a href=\'http://en.wikipedia.org/wiki/Basic_access_authentication\' 
                    target=\'_blank\'>Basic Access Authentication</a>',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return true;
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateTrue'
                    ),
                ),
                self::NAME_CURRENCY => array(
                    self::NAME_WORDINGS_TITLE => 'Export currency',
                    self::NAME_WORDINGS_DESCRIPTION => 'If not selected, the default currency is used.',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return true;
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateTrue'
                    ),
                ),
                self::NAME_FILENAME => array(
                    self::NAME_WORDINGS_TITLE => 'Export filename',
                    self::NAME_WORDINGS_DESCRIPTION => '16 characters max. Must start with a letter.<br>
                    Can contain letters, digits, "-" and "_"',
                    //self::nameWordingsValidator => function ($value) {
                    //return !empty($value) && strlen($value)
                    // <= 16 && preg_match('/^[a-z0-9]+([a-z0-9-_]+)?$/iD', $value);
                    //},
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateNameFileName'
                    ),
                ),
                self::NAME_COMPRESS_LIBRARY => array(
                    self::NAME_WORDINGS_TITLE => 'Compress Tradefeed XML',
                    self::NAME_WORDINGS_DESCRIPTION => 'Choose a Compress Library to ' .
                        'compress destination Tradefeed XML',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return array_key_exists($value, Settings::getCompressLibraryOptions());
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateNameCompressLibrary'
                    ),
                ),
                self::NAME_DEFAULT_STOCK_QUANTITY => array(
                    self::NAME_WORDINGS_TITLE => 'Min quantity in stock',
                    self::NAME_WORDINGS_DESCRIPTION => 'Set minimum quantity if quantity management is turned OFF',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return is_numeric($value) && intval($value) >= 0;
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateNameDefaultStockQuantity'
                    ),
                ),
                self::NAME_EXPORT_QUANTITY_MORE_THAN => array(
                    self::NAME_WORDINGS_TITLE => 'Export products with available quantity more than',
                    self::NAME_WORDINGS_DESCRIPTION => 'Products with stock quantities lower than this value will be 
                                                        excluded from the XML feed',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return is_numeric($value) && intval($value) >= 0;
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateNameExportQuantityMoreThan'
                    ),
                ),
                self::NAME_EXPORT_STATUSES => array(
                    self::NAME_WORDINGS_TITLE => 'Export statuses',
                    self::NAME_WORDINGS_DESCRIPTION => '',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return is_array($value);
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateIsArray'
                    ),
                ),
                self::NAME_EXPORT_VISIBILITIES => array(
                    self::NAME_WORDINGS_TITLE => 'Export visibilities',
                    self::NAME_WORDINGS_DESCRIPTION => '',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return is_array($value);
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateIsArray'
                    ),
                ),
                self::NAME_LOGGING_LEVEL => array(
                    self::NAME_WORDINGS_TITLE => 'Logging Level',
                    self::NAME_WORDINGS_DESCRIPTION => 'A level describes the severity of a logging message. 
                        There are six levels, show here in descending order of severity',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return in_array($value, Settings::getLoggingLevelOptions());
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateNameLoggingLevel'
                    ),
                ),
                self::NAME_LOGGING_APPLICATION => array(
                    self::NAME_WORDINGS_TITLE => 'Logging only the certain types of errors',
                    self::NAME_WORDINGS_DESCRIPTION => 'The option describes, what kind of errors need to log. ' .
                        'All (logging all messages), Only PHP Errors (logging only php errors), ' .
                        'Only Store Integrator Errors (logging only Store integrator messages)',
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateNameLoggingApplication'
                    ),
                ),
                self::NAME_EXCLUDE_CATEGORIES => array(
                    self::NAME_WORDINGS_TITLE => 'Included Categories',
                    self::NAME_WORDINGS_DESCRIPTION => '',
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateIsArray'
                    ),
                ),
                self::NAME_INCLUDE_ALLOW_OFFERS_CATEGORIES => array(
                    self::NAME_WORDINGS_TITLE => 'Included Allow Offers Categories',
                    self::NAME_WORDINGS_DESCRIPTION => '',
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateIsArray'
                    ),
                ),
                self::NAME_EXPORT_CONFIGURATION => array(
                    self::NAME_WORDINGS_TITLE => 'Export Configuration',
                    self::NAME_WORDINGS_DESCRIPTION => '',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return true;
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateTrue'
                    ),
                ),
                self::NAME_EXPORT_CRITERIA => array(
                    self::NAME_WORDINGS_TITLE => 'Export Criteria',
                    self::NAME_WORDINGS_DESCRIPTION => '',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return true;
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateTrue'
                    ),
                ),
                self::NAME_EXPORT_LINKS => array(
                    self::NAME_WORDINGS_TITLE => 'Links',
                    self::NAME_WORDINGS_DESCRIPTION => '',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return true;
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateTrue'
                    ),
                ),
                self::NAME_EXPORT_URL => array(
                    self::NAME_WORDINGS_TITLE => 'Export',
                    self::NAME_WORDINGS_DESCRIPTION => '',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return is_string($value) && !empty($value);
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateNotEmpty'
                    ),
                ),
                self::NAME_DOWNLOAD_URL => array(
                    self::NAME_WORDINGS_TITLE => 'Download',
                    self::NAME_WORDINGS_DESCRIPTION => '',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return is_string($value) && !empty($value);
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateNotEmpty'
                    ),
                ),
                self::NAME_BUTTON_EXPORT => array(
                    self::NAME_WORDINGS_TITLE => 'Export Tradefeed',
                    self::NAME_WORDINGS_DESCRIPTION => '',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return true;
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        $this,
                        '__validate_true'
                    ),
                ),
                self::NAME_BUTTON_DOWNLOAD => array(
                    self::NAME_WORDINGS_TITLE => 'Download Tradefeed',
                    self::NAME_WORDINGS_DESCRIPTION => '',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return true;
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateTrue'
                    ),
                ),
                self::NAME_BUTTON_RESET => array(
                    self::NAME_WORDINGS_TITLE => 'Reset tokens',
                    self::NAME_WORDINGS_DESCRIPTION => '',
                    //                    self::nameWordingsValidator => function ($value) {
                    //                        return true;
                    //                    },
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateTrue'
                    ),
                ),
                self::NAME_ADVANCED_SETTINGS => array(
                    self::NAME_WORDINGS_TITLE => 'Advanced',
                    self::NAME_WORDINGS_DESCRIPTION => '',
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateTrue'
                    ),
                ),
                self::NAME_ACTION_RESET_EXPORT_TABLES => array(
                    self::NAME_WORDINGS_TITLE => 'Reset export data',
                    self::NAME_WORDINGS_DESCRIPTION => 'Clicking on this link will reset all exported data in your 
                    tradefeed. This is done by clearing all exported product data, before re-adding all products 
                    to the export and completing the query. Please note, you will still need to run the export 
                    link once this process completes in order to update the download file.',
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        '__validate_not_empty'
                    ),
                ),
                self::NAME_BUTTON_RESET_AUDIT => array(
                    self::NAME_WORDINGS_TITLE => 'Launch',
                    self::NAME_WORDINGS_DESCRIPTION => '',
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateTrue'
                    ),
                ),
                /*
                                * Feature #3750
                                */
                self::NAME_EXPORT_PRODUCT_SUMMARY => array(
                    self::NAME_WORDINGS_TITLE => 'Export Product Summary',
                    self::NAME_WORDINGS_DESCRIPTION => 'Check to export product summary to tradefeed',
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateIsBool'
                    ),
                ),

                self::NAME_EXPORT_PRODUCT_DESCRIPTION => array(
                    self::NAME_WORDINGS_TITLE => 'Export Product Description',
                    self::NAME_WORDINGS_DESCRIPTION => 'Check to export product description to tradefeed',
                    self::NAME_WORDINGS_VALIDATOR => array(
                        'com\extremeidea\bidorbuy\storeintegrator\core\Settings',
                        'validateIsBool'
                    ),
                ),
                /*
                                 * End Feature Block
                                 */
            ),
        );

        $this->settings = $this->defaults;
        unset($this->settings[self::NAME_WORDINGS]);
    }

    /**
     * Validate True
     *
     * @return bool
     */
    public static function validateTrue()
    {
        return true;
    }

    public static function validateIsArray($value)
    {
        return is_array($value);
    }

    public static function validateIsBool($value)
    {
        return is_bool($value);
    }

    public static function validateIsString($value)
    {
        return is_string($value);
    }

    public static function validateNotEmpty($value)
    {
        return is_string($value) && !empty($value);
    }

    public static function validateNameFileName($value)
    {
        return !empty($value) && strlen($value) <= 16 && preg_match('/^[a-z]+([a-z0-9-_]+)?$/iD', $value);
    }

    public static function validateNameCompressLibrary($value)
    {
        return array_key_exists($value, self::getCompressLibraryOptions());
    }

    public static function validateNameDefaultStockQuantity($value)
    {
        return is_numeric($value) && intval($value) >= 0;
    }

    public static function validateNameExportQuantityMoreThan($value)
    {
        return is_numeric($value) && intval($value) >= 0;
    }

    public static function validateNameLoggingLevel($value)
    {
        return in_array($value, self::getLoggingLevelOptions());
    }

    public static function validateNameLoggingApplication($value)
    {
        $availableOptions = array_keys(self::getLoggingApplicationOptions());
        return in_array($value, $availableOptions);
    }

    public function getUsername()
    {
        return $this->settings[self::NAME_USERNAME];
    }

    public function setUsername($Username)
    {
        $this->settings[self::NAME_USERNAME] = $Username;
    }

    public function getPassword()
    {
        $password = $this->settings[self::NAME_PASSWORD];

        if (!empty($password) && strpos($password, self::NAME_PASSWORD_PREFIX) == 0) {
            $length = strlen(self::NAME_PASSWORD_PREFIX);
            $password = base64_decode(substr($password, $length, strlen($password) - $length));
        }

        return $password;
    }

    /**
     * Set Password
     *
     * @param string $password password
     *
     * @return void
     */
    public function setPassword($password)
    {
        if (!empty($password) && strpos($password, self::NAME_PASSWORD_PREFIX) === false) {
            $this->settings[self::NAME_PASSWORD] = self::NAME_PASSWORD_PREFIX . base64_encode($password);
        }
    }

    public function getCurrency()
    {
        return $this->settings[self::NAME_CURRENCY];
    }

    public function getFilename()
    {
        return $this->settings[self::NAME_FILENAME];
    }

    /**
     * Get Protected Extension
     *
     * @return string
     */
    public function getProtectedExtension()
    {
        return '.dat';
    }

    /**
     * Get Default Extension
     *
     * @return string
     */
    public function getDefaultExtension()
    {
        $options = $this->getCompressLibraryOptions();

        return $options['none']['extension'] . $this->getProtectedExtension();
    }

    public function getCompressLibrary()
    {
        return $this->settings[self::NAME_COMPRESS_LIBRARY];
    }

    public function getExportQuantityMoreThan()
    {
        return $this->settings[self::NAME_EXPORT_QUANTITY_MORE_THAN];
    }

    public function setExportQuantityMoreThan($value)
    {
        $this->settings[self::NAME_EXPORT_QUANTITY_MORE_THAN] = intval($value);
    }

    public function getDefaultStockQuantity()
    {
        return $this->settings[self::NAME_DEFAULT_STOCK_QUANTITY];
    }

    public function setDefaultStockQuantity($value)
    {
        $this->settings[self::NAME_DEFAULT_STOCK_QUANTITY] = intval($value);
    }

    public function getExportStatuses()
    {
        return $this->settings[self::NAME_EXPORT_STATUSES];
    }

    public function setExportStatuses($value = array())
    {
        $wordings = $this->getDefaultWordings();

        $status = call_user_func($wordings[self::NAME_EXPORT_STATUSES][self::NAME_WORDINGS_VALIDATOR], $value);
        if ($status) {
            $this->settings[self::NAME_EXPORT_STATUSES] = $value;
        }

        return $status;
    }

    public function getExportVisibilities()
    {
        return $this->settings[self::NAME_EXPORT_VISIBILITIES];
    }

    public function setExportVisibilities($value = array())
    {
        $wordings = $this->getDefaultWordings();

        $status = call_user_func($wordings[self::NAME_EXPORT_VISIBILITIES][self::NAME_WORDINGS_VALIDATOR], $value);
        if ($status) {
            $this->settings[self::NAME_EXPORT_VISIBILITIES] = $value;
        }

        return $status;
    }

    public function getExcludeCategories()
    {
        return $this->settings[self::NAME_EXCLUDE_CATEGORIES];
    }

    public function getIncludeAllowOffersCategories()
    {
        return $this->settings[self::NAME_INCLUDE_ALLOW_OFFERS_CATEGORIES];
    }

    public function setExcludeCategories($value = array())
    {
        $wordings = $this->getDefaultWordings();

        $status = call_user_func($wordings[self::NAME_EXCLUDE_CATEGORIES][self::NAME_WORDINGS_VALIDATOR], $value);
        if ($status) {
            $this->settings[self::NAME_EXCLUDE_CATEGORIES] = $value;
        }

        return $status;
    }

    public function getLoggingLevel()
    {
        return $this->settings[self::NAME_LOGGING_LEVEL];
    }

    public function setLoggingLevel($loggingLevel)
    {
        $this->settings[self::NAME_LOGGING_LEVEL] = $loggingLevel;
    }

    public function getLoggingApplication()
    {
        return $this->settings[self::NAME_LOGGING_APPLICATION];
    }

    public function setLoggingApplication($loggingApp)
    {
        $this->settings[self::NAME_LOGGING_APPLICATION] = $loggingApp;
    }

    public function getTokenDownload()
    {
        return $this->settings[self::NAME_TOKEN_DOWNLOAD];
    }

    public function setTokenDownload($value)
    {
        $this->settings[self::NAME_TOKEN_DOWNLOAD] = $value;
    }

    public function getTokenExport()
    {
        return $this->settings[self::NAME_TOKEN_EXPORT];
    }

    public function setTokenExport($value)
    {
        $this->settings[self::NAME_TOKEN_EXPORT] = $value;
    }

    public static function getCompressLibraryOptions()
    {
        $value['none'] = array(
            'extension' => '.xml',
            'mime-type' => 'text/xml'
        );

        if (extension_loaded('zip')) {
            $value['zip'] = array(
                'extension' => '.zip',
                'mime-type' => 'application/zip'
            );
        }

        if (extension_loaded('zlib')) {
            $value['gzip'] = array(
                'extension' => '.xml.gz',
                'mime-type' => 'application/x-gzip'
            );
        }

        return $value;
    }

    public static function getLoggingLevelOptions()
    {
        return array(
            'all',
            'critical',
            'error',
            'warn',
            'info',
            'debug',
            'disable'
        );
    }

    public static function getLoggingApplicationOptions()
    {
        return array(
            self::NAME_LOGGING_APPLICATION_OPTION_ALL => 'All',
            self::NAME_LOGGING_APPLICATION_OPTION_PHP => 'Only PHP Errors',
            self::NAME_LOGGING_APPLICATION_OPTION_EXTENSION => 'Only Store Integrator Errors',
        );
    }

    public static function generateToken()
    {
        return md5(time() . rand(0, 100));
    }

    public function getOutputFile()
    {
        return self::$dataPath . '/' . $this->getFilename() . $this->getDefaultExtension();
    }

    public function getCategoryOutputFile($categoryId)
    {
        return self::$dataPath . '/' . $this->getFilename() . '.' . $categoryId . $this->getDefaultExtension();
    }

    public function getCategoryTemporaryOutputFile($categoryId)
    {
        return self::$dataPath . '/' . $this->getFilename() . '.' . $categoryId . $this->getProtectedExtension();
    }

    public function getCategoryOutputFilePattern($type = 'all')
    {
        switch ($type) {
            case 'completed':
                return "/^{$this->getFilename()}\.\d+({$this->getDefaultExtension()})$/i";
                break;
            case 'md5':
                return "/^{$this->getFilename()}\.[a-z0-9]+({$this->getDefaultExtension()})$/i";
                break;
            default:
                return "/^{$this->getFilename()}\.\d+({$this->getDefaultExtension()})"
                    . "|({$this->getProtectedExtension()})$/i";
        }
    }

    /**
     * Get Compress Output File
     *
     * @return string
     */
    public function getCompressOutputFile()
    {
        $options = $this->getCompressLibraryOptions();

        return self::$dataPath . '/' . $this->getFilename() . $options[$this->getCompressLibrary()]['extension']
            . $this->getProtectedExtension();
    }

    /*
     * Feature #3750
     */
    public function getExportProductSummary()
    {
        return $this->settings[self::NAME_EXPORT_PRODUCT_SUMMARY];
    }

    public function setExportProductSummary($value)
    {
        $this->settings[self::NAME_EXPORT_PRODUCT_SUMMARY] = $value;
    }

    public function getExportProductDescription()
    {
        return $this->settings[self::NAME_EXPORT_PRODUCT_DESCRIPTION];
    }

    public function setExportProductDescription($value)
    {
        $this->settings[self::NAME_EXPORT_PRODUCT_DESCRIPTION] = $value;
    }

    /*
     * End Feature Block
     */

    public function cleanProtectedExtension($file)
    {
        return str_replace($this->getProtectedExtension(), '', $file);
    }

    public function getDefaultWordings()
    {
        return $this->defaults[self::NAME_WORDINGS];
    }

    public function serialize($base64 = 0)
    {
        return $this->serialize2($this->settings, $base64);
    }

    public function serialize2($settings = array(), $base64 = 0)
    {
        $data = serialize($settings);
        if ($base64) {
            $data = base64_encode($data);
        }

        return $data;
    }

    public function unserialize($settings, $base64 = 0)
    {
        if ($base64) {
            $settings = base64_decode($settings);
        }

        $settings = unserialize($settings);

        $defaults = $this->defaults;
        unset($defaults[self::NAME_WORDINGS]);

        !is_array($settings) ? $settings = $defaults : $settings = array_merge($defaults, $settings);


        $password = $settings[self::NAME_PASSWORD];
        if (!empty($password) && strpos($password, self::NAME_PASSWORD_PREFIX) === false) {
            $settings[self::NAME_PASSWORD] = self::NAME_PASSWORD_PREFIX . base64_encode($password);
        }

        $this->settings = $settings;
    }
}

// phpcs:disable PSR1.Files.SideEffects
Settings::$coreAssetsPath= __DIR__ . '/../assets';
Settings::$dataPath = __DIR__ . '/../data';
Settings::$logsPath = __DIR__ . '/../logs';
// phpcs:enable PSR1.Files.SideEffects
