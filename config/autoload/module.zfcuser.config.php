<?php
/**
 * ZfcUser Configuration
 *
 * If you have a ./configs/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
$settings = array(
    /**
     * PDO Connection DI alias
     *
     * If using Zend\Db, please specify the DI alias for the configured PDO
     * instance that ZfcUser should use.
     */
    'zend_db_pdo' => 'masterdb',

    /**
     * Enable Username
     *
     * Enables username field on the registration form, and allows users to log
     * in using their username OR email address. Default is false.
     *
     * Accepted values: boolean true or false
     */
    'enable_username' => false,

    /**
     * Enable Display Name
     *
     * Enables a display name field on the registration form, which is persisted
     * in the database. Default value is false.
     *
     * Accepted values: boolean true or false
     */
    'enable_display_name' => true,

    /**
     * Require Activation
     *
     * Require that the user verify their email address to activate their
     * account. Default value is false. (Note, this doesn't actually work yet,
     * but defaults an 'active' field in the DB to 0.)
     *
     * Accepted values: boolean true or false
     */
    'require_activation' => false,

    /**
     * Login After Registration
     *
     * Automatically logs the user in after they successfully register. Default
     * value is false.
     *
     * Accepted values: boolean true or false
     */
    'login_after_registration' => true,

    /**
     * Registration Form Captcha
     *
     * Determines if a captcha should be utilized on the user registration form.
     * Default value is true. (Note, right now this only utilizes a weak
     * Zend\Text\Figlet CAPTCHA, but I have plans to make all Zend\Captcha
     * adapters work.)
     */
    'registration_form_captcha' => true,

    /**
     * Use Redirect Parameter If Present
     *
     * Upon successful authentication, check for a 'redirect' POST parameter
     *
     * Accepted values: boolean true or false
     */
    'use_redirect_parameter_if_present' => true,

    /**
     * End of ZfcUser configuration
     */
);

/**
 * You do not need to edit below this line
 */
return array(
    'zfcuser' => $settings,
    'di' => array(
        'instance' => array(
            'alias' => array(
                'zfcuser_pdo'             => $settings['zend_db_pdo'],
            ),
        ),
    ),
);
