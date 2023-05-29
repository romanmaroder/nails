<?php

return [
    'bsVersion' => '4.x',
    'adminEmail' => 'roma12041985@yandex.ru',
    'telegramToken'=>'1903642753:AAFmzbm0X8khTV-AQmO5HzASFC0I4kX_6mY',
    'phonePattern'=>'/\+?([0-9]{2})(\W[0-9]{3}\W)([0-9]{3})-([0-9]{2})-([0-9]{2})/',
    'hail812/yii2-adminlte3' => [
        'pluginMap' => [
            'datatables' => [
                'js' => 'datatables/jquery.dataTables.min.js'
            ],
            'datatables-bs4' => [
                'css' => 'datatables-bs4/css/dataTables.bootstrap4.min.css',
                'js' => 'datatables-bs4/js/dataTables.bootstrap4.min.js'
            ],
            'datatables-responsive' => [
                'css' => 'datatables-responsive/css/responsive.bootstrap4.min.css',
                'js' => [
                    'datatables-responsive/js/dataTables.responsive.min.js',
                    'datatables-responsive/js/responsive.bootstrap4.min.js'
                ]
            ],
            'datatables-buttons' => [
                'css' => 'datatables-buttons/css/buttons.bootstrap4.min.css',
                'js' => [
                    'datatables-buttons/js/dataTables.buttons.min.js',
                    'datatables-buttons/js/buttons.bootstrap4.min.js',
                    'datatables-buttons/js/buttons.html5.min.js',
                    'datatables-buttons/js/buttons.colVis.min.js'
                ]
            ],
            'sweetalert2-theme-bootstrap-4' => [
                'css' => 'sweetalert2-theme-bootstrap-4/bootstrap-4.min.css'
            ],
            'sweetalert2' => [
                'css' => 'sweetalert2/sweetalert2.min.css',
                'js' => 'sweetalert2/sweetalert2.all.js'
            ],
            'toastr' => [
                'css' => 'toastr/toastr.min.css',
                'js' => 'toastr/toastr.min.js',
            ],
            'ekko-lightbox' => [
                'css' => 'ekko-lightbox/ekko-lightbox.css',
                'js' => 'ekko-lightbox/ekko-lightbox.min.js'
            ],
            'filterizr' => [
                'js' => 'filterizr/jquery.filterizr.min.js'
            ]
        ]
    ]
];