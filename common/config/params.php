<?php

return [
    'adminEmail'   => 'admmin@yandex.ru',
    'supportEmail' => 'admmin@yandex.ru',
    'senderEmail'  => 'noreply@example.com',
    'senderName'   => 'Example.com mailer',

    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength'        => 8,

    'maxLimitNews' => 5, //лимит новостей на странице

    'maxFileSize' => 1024 * 1024 * 2.5, // Размер загружаемого файла 2mb
    'storagePath' => '@frontend/web/uploads/',
    'storageUri'  => '/uploads/', //http://nails.com/uploads/c1/d3/78df89d65d.jpg

    'error'        => [
        'service'          => 'Услуга не указана',
        'date-range'       => 'Не выбран промежуток дат',
        'error'            => 'Произошла ошибка',
        'access-is-denied' => 'У Вас нет доступа'
    ],
    'sms-location' => [
        'address'    => '. Наш адрес: ул.Раздольная д.11, подъезд 4, кв.142, этаж 9. ',
        'entry'      => ' у Вас запись. Вы будете?',
        'entry-next' => '. У Вас следующая запись '
    ],

    'avatarPicture'      => [
        'maxWidth'  => 300,
        'maxHeight' => 300
    ],
    'photoPicture'       => [
        'maxWidth'  => 450,
        'maxHeight' => 450
    ],
    'certificatePicture' => [
        'maxWidth'  => 450,
        'maxHeight' => 450
    ],
    'postPicture'        => [
        'maxWidth'  => 1024,
        'maxHeight' => 'auto'
    ],
    'postPreview'        => [
        'maxWidth'  => 1024,
        'maxHeight' => 1024
    ],
    /*'hail812/yii2-adminlte3' => [
        'pluginMap' => [
            'chart'=>[
                'css'=>'chart.js/Chart.css',
                'js'=>'chart.js/Chart.js'
            ],
            'datatables'                    => [
                'js' => 'datatables/jquery.dataTables.min.js'
            ],
            'bs-custom-file-input'          => [
                'js' => 'bs-custom-file-input/bs-custom-file-input.min.js'
            ],
            'datatables-bs4'                => [
                'css' => 'datatables-bs4/css/dataTables.bootstrap4.min.css',
                'js'  => 'datatables-bs4/js/dataTables.bootstrap4.min.js'
            ],
            'datatables-responsive'         => [
                'css' => 'datatables-responsive/css/responsive.bootstrap4.min.css',
                'js'  => [
                    'datatables-responsive/js/dataTables.responsive.min.js',
                    'datatables-responsive/js/responsive.bootstrap4.min.js'
                ]
            ],
            'datatables-buttons'            => [
                'css' => 'datatables-buttons/css/buttons.bootstrap4.min.css',
                'js'  => [
                    'datatables-buttons/js/dataTables.buttons.min.js',
                    'datatables-buttons/js/buttons.bootstrap4.min.js',
                    'datatables-buttons/js/buttons.html5.min.js',
                    'datatables-buttons/js/buttons.colVis.min.js'
                ]
            ],
            'sweetalert2-theme-bootstrap-4' => [
                'css' => 'sweetalert2-theme-bootstrap-4/bootstrap-4.min.css'
            ],
            'sweetalert2'                   => [
                'css' => 'sweetalert2/sweetalert2.min.css',
                'js'  => 'sweetalert2/sweetalert2.all.js'
            ],
            'toastr'                        => [
                'css' => 'toastr/toastr.min.css',
                'js'  => 'toastr/toastr.min.js',
            ],
            'ekko-lightbox'                 => [
                'css' => 'ekko-lightbox/ekko-lightbox.css',
                'js'  => 'ekko-lightbox/ekko-lightbox.min.js'
            ],
            'filterizr'                     => [
                'js' => 'filterizr/jquery.filterizr.min.js'
            ],
            'summernote'=>[
                'css'=>'summernote/summernote-bs4.min.css',
                'js'=>[
                    'summernote/summernote-bs4.min.js',
                    'summernote/lang/summernote-ru-Ru.js'
                ]
            ],
            'codemirror'=>[
                'css'=>[
                    'codemirror/codemirror.css',
                    'codemirror/theme/monokai.css',
                ],
                'js'=>[
                    'codemirror/codemirror.js',
                    'codemirror/mode/css/css.js',
                    'codemirror/mode/xml/xml.js',
                    'codemirror/mode/htmlmixed/htmlmixed.js'
                ]
            ],
        ]
    ],*/

];
