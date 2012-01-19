<?php
/**
 * ZfcUser Configuration
 *
 * If you have a ./configs/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
$settings = array(
    /**
     * Database Abstraction
     *
     * Specify which of the database abstraction layers (Doctrine2 or Zend\Db)
     * that you would like ZfcUser to utilize.
     *
     * Accepted values: 'doctrine' or 'zend_db'
     */
    'db_abstraction' => 'zend_db',

    /**
     * PDO Connection DI alias
     *
     * If using Zend\Db, please specify the DI alias for the configured PDO
     * instance that ZfcUser should use.
     */
    'zend_db_pdo' => 'masterdb',

    /**
     * Doctrine Entity Manager DI alias
     *
     * If using Doctrine2, please specify the DI alias for the Doctrine entity
     * manager that ZfcUser should use. If using SpiffyDoctrine, this is
     * probably 'doctrine_em', which is the default value.
     */
    'doctrine_em' => 'doctrine_em',
    
    /**
     * Doctrine Document Manager DI alias
     *
     * If using Doctrine MongoDB, please specify the DI alias for the Doctrine document
     * manager that ZfcUser should use. If using DoctrineModule, this is
     * probably 'mongo_dm', which is the default value.
     */
    'mongo_dm' => 'mongo_dm',

    /**
     * User Model Entity Class
     *
     * Name of Entity class to use. Useful for using your own entity class
     * instead of the default one provided. Default is ZfcUser\Model\User.
     */
    'user_model_class' => 'ZfcUser\Model\User',

    /**
     * UserMeta Model Entity Class
     *
     * Name of Entity class to use. Useful for using your own entity class 
     * instead of the default one provided. Default is ZfcUser\Entity\UserMeta. 
     */
    'usermeta_model_class' => 'ZfcUser\Model\UserMeta',

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
     * Password Security
     *
     * DO NOT CHANGE THE PASSWORD HASH SETTINGS FROM THEIR DEFAULTS
     * Unless A) you have done sufficient research and fully understand exactly
     * what you are changing, AND B) you have a very specific reason to deviate
     * from the default settings and know what you're doing.
     *
     * The password hash settings may be changed at any time without
     * invalidating existing user accounts. Existing user passwords will be
     * re-hashed automatically on their next successful login.
     */

    /**
     * Password Hash Algorithm
     *
     * Name of the hashing algorithm to use for hashing. Supported
     * algorithms are blowfish, sha512, and sha256. Default is blowfish.
     *
     * Accepted values: 'blowfish', 'sha512', and 'sha256'
     */
    'password_hash_algorithm' => 'blowfish',

    /**
     * Blowfish Cost
     *
     * Only used if `password_hash_algorithm` is set to blowfish. The number
     * represents the base-2 logarithm of the iteration count used for hashing.
     * Default is 10 (about 10 hashes per second on an i5).
     *
     * Accepted values: integer between 4 and 31
     */
    'blowfish_cost' => 10,

    /**
     * SHA-512 Rounds
     *
     * Only used if `password_hash_algorithm` is set to sha512. The number
     * represents the iteration count used for hashing. Default is 5000.
     *
     * Accepted values: integer between 1000 and 999999999
     */
    'sha512_rounds' => 5000,

    /**
     * SHA-256 Rounds
     *
     * Only used if `password_hash_algorithm` is set to sha256. The number
     * represents the iteration count used for hashing. Default is 5000.
     *
     * Accepted values: integer between 1000 and 999999999
     */
    'sha256_rounds' => 5000,

    /**
     * End of ZfcUser configuration
     */
);

/**
 * You do not need to edit below this line
 */
switch(strtolower($settings['db_abstraction'])) {
    case 'doctrine':
        $userMapper     = 'ZfcUserDoctrineORM\Mapper\UserDoctrine';
        $userMetaMapper = 'ZfcUserDoctrineORM\Mapper\UserMetaDoctrine';
        break;
    case 'mongo':
        $userMapper     = 'ZfcUserDoctrineMongoODM\Mapper\UserMongoDB';
        $userMetaMapper = 'ZfcUserDoctrineMongoODM\Mapper\UserMetaMongoDB';
        break;
    default:
        $userMapper     = 'ZfcUser\Model\Mapper\UserZendDb';
        $userMetaMapper = 'ZfcUser\Model\Mapper\UserMetaZendDb';
        break; 
}
 
return array(
    'zfcuser' => $settings,
    'di' => array(
        'instance' => array(
            'alias' => array(
                'zfcuser_pdo'             => $settings['zend_db_pdo'],
                'zfcuser_doctrine_em'     => $settings['doctrine_em'],
                'zfcuser_mongo_dm'        => $settings['mongo_dm'],
                'zfcuser_user_mapper'     => $userMapper,
                'zfcuser_usermeta_mapper' => $userMetaMapper,
            ),
        ),
    ),
);
