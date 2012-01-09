<?php

$config = array();

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
        'field' => 'edit-body',
        'label' => 'Текст',
        'rules' => 'required'
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
    array(
        'field' => 'edit-image',
        'label' => '',
        'rules' => ''
    ),
    array(
        'field' => 'edit-thumb',
        'label' => '',
        'rules' => ''
    ),
    array(
        'field' => 'edit-image-desc',
        'label' => 'Описание изображения',
        'rules' => ''
    ),
);

// Загрузка файла
$config['upload'] = array(
    'upload_path'   => 'images/article',
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
$config['image_main'] = array(
    'width'  => 400,
    'height' => 300
);
$config['thumb'] = array(
    'width'  => 120,
    'height' => 90
);