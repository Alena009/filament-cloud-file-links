<?php

return [
    'empty' => 'Пока нет ссылок на файлы.',

    'columns' => [
        'actions' => 'Действия',
    ],

    'fields' => [
        'file' => [
            'label' => 'Файл / Ссылка',
        ],
        'name' => [
            'label' => 'Имя ссылки',
        ],
        'url' => [
            'label' => 'URL в облаке',
        ],
    ],

    'actions' => [
        'add' => [
            'label' => 'Добавить ссылку на файл',
            'submit' => 'Добавить',
        ],
        'edit' => [
            'label' => 'Редактировать ссылку',
            'submit' => 'Сохранить',
        ],
        'delete' => [
            'modal_heading' => 'Удалить ссылку',
            'modal_description' => 'Вы уверены, что хотите удалить эту ссылку на файл?',
            'modal_submit' => 'Удалить',
            'modal_cancel' => 'Отмена',
        ],
    ],
];
