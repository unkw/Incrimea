<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['profile_validation_edit'] = array(
    array(
        'field' => 'edit-name',
        'label' => 'Имя пользователя',
        'rules' => 'required|min_length[3]|max_length[15]|alpha_dash|callback_check_username'
    ),

    array(
        'field' => 'edit-email',
        'label' => 'Почтовый адрес',
        'rules' => 'required|valid_email|callback_check_email'
    ),
    array(
        'field' => 'edit-role',
        'label' => 'Роль',
        'rules' => 'required|is_natural'
    ),
    array(
        'field' => 'edit-status',
        'label' => 'Статус',
        'rules' => 'required|is_natural'
    ),

);

$config['profile_validation_add'] = array_merge(
    $config['profile_validation_edit'], array(
        array(
            'field' => 'edit-pass',
            'label' => 'Пароль',
            'rules' => 'required'
        ),
        array(
            'field' => 'edit-passconf',
            'label' => 'Подтверждение пароля',
            'rules' => 'required|matches[edit-pass]'
        ),
    )
);
