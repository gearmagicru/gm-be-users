<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации модуля.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c] 2015 Этот файл является частью модуля веб-приложения GearMagic. Web-студия
 * @license https://gearmagic.ru/license/
 */

return [
    'translator' => [
        'locale'   => 'auto',
        'patterns' => [
            'text' => [
                'basePath' => __DIR__ . '/../lang',
                'pattern'  => 'text-%s.php'
            ]
        ],
        'autoload' => ['text'],
        'external' => [BACKEND]
    ],

    'accessRules' => [
        // для авторизованных пользователей Панели управления
        [ // разрешение "Полный доступ" (any: view, read, add, update, delete, clear, account)
            'allow',
            'permission'  => 'any',
            'controllers' => [
                'Me'          => ['view', 'data', 'update'], // изменение своей учётной записи
                'Form'        => ['data', 'view', 'add'], // добавление пользователя
                'AccountForm' => ['data', 'view', 'update', 'delete'], // изменение учётной записи
                'ProfileForm' => ['data', 'view', 'update'],  // изменение профиля пользователя
                'Grid'        => ['data', 'supplement', 'view', 'update', 'delete', 'clear', 'filter'],
                'Trigger'     => ['combo'],
                'Search'      => ['data', 'view', 'update']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Просмотр" (view)
            'allow',
            'permission'  => 'view',
            'controllers' => [
                'Me'          => ['view', 'data'], // изменение своей учётной записи
                'Form'        => ['data', 'view'], // добавление пользователя
                'AccountForm' => ['data', 'view'], // изменение учётной записи
                'ProfileForm' => ['data', 'view'],  // изменение профиля пользователя
                'Grid'        => ['data', 'supplement', 'view', 'filter'],
                'Trigger'     => ['combo'],
                'Search'      => ['data', 'view']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Чтение" (read)
            'allow',
            'permission'  => 'read',
            'controllers' => [
                'Me'          => ['data'], // изменение своей учётной записи
                'Form'        => ['data'], // добавление пользователя
                'AccountForm' => ['data'], // изменение учётной записи
                'ProfileForm' => ['data'],  // изменение профиля пользователя
                'Grid'        => ['data', 'supplement'],
                'Trigger'     => ['combo']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Добавление" (add)
            'allow',
            'permission'  => 'add',
            'controllers' => [
                'Form' => ['add'],
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Изменение" (edit)
            'allow',
            'permission'  => 'edit',
            'controllers' => [
                'Grid' => ['update'],
                'Form' => ['update'],
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Удаление" (delete)
            'allow',
            'permission'  => 'delete',
            'controllers' => [
                'Grid' => ['delete'],
                'Form' => ['delete'],
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Очистка" (clear)
            'allow',
            'permission'  => 'clear',
            'controllers' => [
                'Grid' => ['clear'],
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Аккаунт" (account)
            'allow',
            'permission'  => 'account',
            'controllers' => [
                'Me'          => ['data', 'view', 'update'],
                'ProfileForm' => ['data', 'view', 'update']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Информация о модуле" (info)
            'allow',
            'permission'  => 'info',
            'controllers' => ['Info'],
            'users'       => ['@backend']
        ],
        [ // разрешение "Настройка модуля" (settings)
            'allow',
            'permission'  => 'settings',
            'controllers' => ['Settings'],
            'users'       => ['@backend']
        ],
        [ // для всех остальных, доступа нет
            'deny'
        ]
    ],

    'viewManager' => [
        'id'          => 'gm-users-{name}',
        'useTheme'    => true,
        'useLocalize' => true,
        'viewMap'     => [
            // информации о модуле
            'info' => [
                'viewFile'      => '//backend/module-info.phtml', 
                'forceLocalize' => true
            ]
        ]
    ]
];
