<?php
// ./config/autoload/database.config.php
return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                'masterdb' => 'PDO',
            ),
            'masterdb' => array(
                'parameters' => array(
                    'dsn'            => 'mysql:dbname=Nennius;host=localhost',
                    'username'       => 'rob',
                    'passwd'         => '123456',
                    'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''),
                ),
            ),
        ),
    ),
);