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

$config['upload'] = array(
    'upload_path'   => 'images/temp',
    'allowed_types' => 'jpg|jpeg|png|gif',
    'max_size'      => '2000',
    'max_width'     => 0,
    'max_height'    => 0,
);

$config['image_lib'] = array(
    'image_library'  => 'gd2',
    'quality'        => '85%',
    'maintain_ratio' => TRUE,
    'width'          => 400,
    'height'         => 300,
);