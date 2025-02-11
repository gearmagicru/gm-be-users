<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации Карты SQL-запросов.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'drop'   => ['{{user}}', '{{user_roles}}', '{{user_profile}}'],
    'create' => [
        '{{user}}' => function () {
            return "CREATE TABLE `{{user}}` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `username` varchar(50) NOT NULL DEFAULT '',
                `password` text NOT NULL,
                `visited_date` datetime DEFAULT NULL,
                `visited_disabled` datetime DEFAULT NULL,
                `visited_trial` tinyint(1) unsigned DEFAULT '0',
                `visited_device` text,
                `status` int(11) unsigned DEFAULT '0',
                `process` datetime DEFAULT NULL,
                `settings` text,
                `side` tinyint(1) unsigned DEFAULT '0',
                `_updated_date` datetime DEFAULT NULL,
                `_updated_user` int(11) unsigned DEFAULT NULL,
                `_created_date` datetime DEFAULT NULL,
                `_created_user` int(11) unsigned DEFAULT NULL,
                `_lock` tinyint(1) unsigned DEFAULT '0',
                PRIMARY KEY (`id`)
                ) ENGINE={engine} 
                DEFAULT CHARSET={charset} COLLATE {collate}";
        },

        '{{user_roles}}' => function () {
            return "CREATE TABLE `{{user_roles}}` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` int(11) unsigned DEFAULT NULL,
                `role_id` int(11) unsigned DEFAULT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE={engine} 
                DEFAULT CHARSET={charset} COLLATE {collate}";
        },

        '{{user_profile}}' => function () {
            return "CREATE TABLE `{{user_profile}}` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` int(11) unsigned DEFAULT NULL,
                `first_name` varchar(100) DEFAULT NULL,
                `second_name` varchar(100) DEFAULT NULL,
                `patronymic_name` varchar(100) DEFAULT NULL,
                `call_name` varchar(255) DEFAULT NULL,
                `photo` varchar(255) DEFAULT NULL,
                `gender` tinyint(1) unsigned DEFAULT '0',
                `date_of_birth` date DEFAULT NULL,
                `phone` varchar(20) DEFAULT NULL,
                `email` varchar(100) DEFAULT NULL,
                `side` tinyint(1) unsigned DEFAULT '0',
                `timezone` varchar(100) DEFAULT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE={engine} 
                DEFAULT CHARSET={charset} COLLATE {collate}";
        }
    ],

    'run' => [
        'install'   => ['drop', 'create'],
        'uninstall' => ['drop']
    ]
];