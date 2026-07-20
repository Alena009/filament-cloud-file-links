<?php

return [
    'empty' => 'No file links yet.',

    'columns' => [
        'actions' => 'Actions',
    ],

    'fields' => [
        'file' => [
            'label' => 'File / Link',
        ],
        'name' => [
            'label' => 'Link name',
        ],
        'url' => [
            'label' => 'Cloud URL',
        ],
    ],

    'actions' => [
        'add' => [
            'label' => 'Add file link',
            'submit' => 'Add',
        ],
        'edit' => [
            'label' => 'Edit file link',
            'submit' => 'Save',
        ],
        'delete' => [
            'modal_heading' => 'Delete file link',
            'modal_description' => 'Are you sure you want to delete this file link?',
            'modal_submit' => 'Delete',
            'modal_cancel' => 'Cancel',
        ],
    ],
];
