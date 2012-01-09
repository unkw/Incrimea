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
        'field' => 'edit-location',
        'label' => 'Месторасположение',
        'rules' => 'required'
    ),
    array(
        'field' => 'edit-resorts',
        'label' => 'Место отдыха',
        'rules' => 'is_natural_no_zero'
    ),
    array(
        'field' => 'edit-region',
        'label' => 'Регион',
        'rules' => 'is_natural_no_zero'
    ),
    array(
        'field' => 'edit-types',
        'label' => 'Тип',
        'rules' => 'is_natural_no_zero'
    ),
    array(
        'field' => 'edit-price',
        'label' => 'Цены от',
        'rules' => 'required|alpha_numeric'
    ),
    array(
        'field' => 'edit-beach-distance',
        'label' => 'Расстояние до пляжа',
        'rules' => 'required|alpha_numeric'
    ),
    array(
        'field' => 'edit-beach-type',
        'label' => 'Тип пляжа',
        'rules' => 'is_natural_no_zero'
    ),
    array(
        'field' => 'edit-room[]',
        'label' => '',
        'rules' => ''
    ),
    array(
        'field' => 'edit-infrastructure[]',
        'label' => '',
        'rules' => ''
    ),
    array(
        'field' => 'edit-service[]',
        'label' => '',
        'rules' => ''
    ),
    array(
        'field' => 'edit-entertainment[]',
        'label' => '',
        'rules' => ''
    ),
    array(
        'field' => 'edit-for-children[]',
        'label' => '',
        'rules' => ''
    ),
    array(
        'field' => 'edit-body',
        'label' => 'Текст',
        'rules' => 'required'
    ),
    array(
        'field' => 'edit-published',
        'label' => 'Опубликовано',
        'rules' => ''
    ),
    array(
        'field' => 'edit-priority',
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

