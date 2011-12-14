<?php

$config = array();

$config['validation_new'] = array(
    array(
        'field' => 'edit-title',
        'label' => 'Заголовок',
        'rules' => 'required|max_length[70]'
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
);

$config['validation_edit'] = array_merge($config['validation_new'], array(
    array(
        'field' => 'edit-resort',
        'label' => 'Место отдыха',
        'rules' => 'required'
    ),
));

