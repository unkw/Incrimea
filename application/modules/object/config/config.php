<?php

$config = array();

// Валидация
$config['validation_new'] = array(
    array(
        'field' => 'edit-title',
        'label' => 'Заголовок',
        'rules' => 'required|max_length[70]'
    ),
    array(
        'field' => 'edit-resorts',
        'label' => 'Место отдыха',
        'rules' => 'is_natural_no_zero'
    ),
    array(
        'field' => 'edit-types',
        'label' => 'Тип',
        'rules' => 'is_natural_no_zero'
    ),
    array(
        'field' => 'edit-min-price',
        'label' => 'Цены от',
        'rules' => 'required|alpha_numeric'
    ),
    array(
        'field' => 'edit-beach',
        'label' => '',
        'rules' => ''
    ),
    array(
        'field' => 'edit-number-fund',
        'label' => 'Номерной фонд',
        'rules' => 'required'
    ),
    array(
        'field' => 'edit-body',
        'label' => 'Текст',
        'rules' => 'required'
    ),
    array(
        'field' => 'edit-structure[]',
        'label' => '',
        'rules' => ''
    ),
    array(
        'field' => 'edit-status',
        'label' => 'Статус',
        'rules' => ''
    ),
    array(
        'field' => 'edit-sticky',
        'label' => 'Закреплять вверху списков',
        'rules' => ''
    ),
);

// Загрузка файла
$config['upload'] = array(
    'upload_path'   => 'images/object/large',
    'allowed_types' => 'jpg|jpeg|png|gif',
    'max_size'      => '2000',
    'max_width'     => 0,
    'max_height'    => 0,
);

// Основные настройки либы Image
$config['image_lib'] = array(
    'image_library'  => 'gd2',
    'quality'        => '85%',
    'maintain_ratio' => TRUE,
);

// Размеры изображений
$config['image_large'] = array(
    'width'  => 640,
    'height' => 480
);
$config['image_medium'] = array(
    'width'  => 360,
    'height' => 270
);
$config['thumb'] = array(
    'width'  => 120,
    'height' => 90
);

