<?php return array(
    'root' => array(
        'name' => 'fena/fena-payment-gateway',
        'pretty_version' => 'dev-master',
        'version' => 'dev-master',
        'reference' => '9a80daa8cc68fc5cd6475c193d3d447465910f73',
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'fena/fena-payment-gateway' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => '9a80daa8cc68fc5cd6475c193d3d447465910f73',
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'fena/php-payment-sdk' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => 'a73fa593076d7893e502de235ce57fb117d87080',
            'type' => 'library',
            'install_path' => __DIR__ . '/../fena/php-payment-sdk',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
    ),
);
