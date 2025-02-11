<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Пакет русской локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Пользователи',
    '{description}' => 'Аккаунты пользователей Панели управления и веб-сайта',
    '{permissions}' => [
        'any'      => ['Полный доступ', 'Просмотр и внесение изменений в аккаунты пользователей'],
        'view'     => ['Просмотр', 'Просмотр аккаунтов пользователей'],
        'read'     => ['Чтение', 'Чтение аккаунтов пользователей'],
        'add'      => ['Добавление', 'Добавление аккаунтов пользователей'],
        'edit'     => ['Изменение', 'Изменение аккаунтов пользователей'],
        'delete'   => ['Удаление', 'Удаление аккаунтов пользователей'],
        'clear'    => ['Очистка', 'Удаление всех аккаунтов пользователей'],
        'account'  => ['Аккаунт', 'Изменение своего аккаунта']
    ],

    // Grid: контекстное меню записи
    'Edit account' => 'Редактировать учётную запись',
    'Edit personal data' => 'Редактировать личные данные',
    // Grid: поля
    'Photo' => 'Фото',
    'User' => 'Пользователь',
    'First name' => 'Имя',
    'Second name' => 'Фамилия',
    'Patronymic name' => 'Отчество',
    'Call name' => 'Обращение',
    'Call name - this is name indicated in messages and letters' => 'Обращение - это имя, указываемое в сообщениях и письмах',
    'User account - Active' => 'Учётная запись пользователя - Активна',
    'User account' => 'Учетная запись',
    'Name' => 'Имя',
    'Roles' => 'Роли',
    'Status' => 'Статус',
    'Account status' => 'Статус учетной записи',
    'Side' => 'Сторона',
    'Side (Website, Control panel) of user registration' => 'Сторона (Сайт, Панель управления) регистрации пользователя',
    'Signin' => 'Авторизация',
    'Date' => 'Дата',
    'Trial' => 'Попыток',
    'Signin trial' => 'Количество попыток авторизации пользователем',
    'Disabled date' => 'Дата блокировки',
    'Contact information' => 'Контактная информация',
    'Phone' => 'Телефон',
    'The phone number can be used to recover or verify account' => 'Номер телефона может использоваться для восстановления или подтверждения учетной записи',
    'E-mail' => 'Электронная почта',
    'E-mail can be used to recover account' => 'Электронная почта может использоваться для восстановления учетной записи',
    // Grid: статус пльзователя
    'Statuses' => [
        'Active' => 'Активный',
        'Disabled' => 'Заблокирован',
        'Temporarily disabled' => 'Временно заблокирован',
        'Waiting' => 'Ожидает проверки',
    ],
    // Grid: всплывающие сообщения / заголовок
    'Enabled' => 'Подключен',
    'Disabled' => 'Отключен',
    // Grid: всплывающие сообщения / текст
    'User account - enabled' => 'Аккаунт пользователя &laquo;<b>{0}</b>&raquo; - <b>подключен</b>.',
    'User account - disabled' => 'Аккаунт пользователя &laquo;<b>{0}</b>&raquo; - <b>отключен</b>.',
    // Grid: аудит записей
    'enabled user account {0}' => 'подключение аккаунта пользователя «<b>{0}</b>»',
    'disabled user account {0}' => 'отключение аккаунта пользователя «<b>{0}</b>»',

    // Form
    '{form.title}' => 'Добавление пользователя',
    '{form.titleTpl}' => 'Изменение учетной записи "{username}"',
    // Form: поля
    'Old account' => 'Текущая учетная запись',
    'Password' => 'Пароль',
    'Username' => 'Имя пользователя',
    'New account' => 'Новая учетная запись',
    'Personal data' => 'Личные данные',
    'Confirm' => 'Подтверждение пароля',
    'Role' => 'Роль пользователя',
    'Date of birth' => 'Дата рождения',
    'Gender' => 'Пол',
    'Woman' => 'женский',
    'Man' => 'мужской',
    'Type' => 'Тип',
    'The user account is created only for the Control Panel, and for the website on the side of the site' 
        => 'Учётная запись пользователя создаётся только для Панели управления, а для веб-сайта - на стороне сайта.',
    'information is used to protect the account' => 'информация используется для защиты вашей учётной записи',
    'Regional settings' => 'Региональные настройки',
    'Timezone' => 'Часовой пояс',
    'If no time zone is selected, the time zone from the regional settings' 
        => 'Если не будет выбран часовой пояс, то будет применяться часовой пояс из региональных настроек "Панель Конфигурации / Региональные настройки"',
    // Form: ошибки
    'Invalid parameter passed' => 'Неправильно передан параметр!',
    'Values of the fields do not match' => 'Значения полей "Пароль" и "Подтверждение пароля" не совпадают!',
    'A user with that name already exists' => 'Пользователь с таким именем уже существует!',
    'Incorrect password for your account' => 'Неправильный пароль вашей учётной записи',
    'Your account has been successfully changed' => 'Ваша учётная запись успешно изменена!',

    // ProfileForm
    '{profile.title}' => 'Добавление личных данных пользователя',
    '{profile.titleTpl}' => 'Изменение личных данных "{callName}"',

    // AccountForm
    '{account.title}' => 'Добавление учётной записи',
    '{account.titleTpl}' => 'Изменение учётной записи "{username}"'
];
