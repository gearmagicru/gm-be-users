<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Пакет английской (британской) локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Users',
    '{description}' => 'Control Panel and Website User Accounts',
    '{permissions}' => [
        'any'      => ['Full access', 'View and make changes to user accounts'],
        'view'     => ['View', 'View user accounts'],
        'read'     => ['Reading', 'Reading user accounts'],
        'add'      => ['Adding', 'Adding user accounts'],
        'edit'     => ['Editing', 'Editing user accounts'],
        'delete'   => ['Deleting', 'Deleting user accounts'],
        'clear'    => ['Clear', 'Deleting all user accounts'],
        'account'  => ['Account', 'Change your account']
    ],

    // Grid: контекстное меню записи
    'Edit account' => 'Edit account',
    'Edit personal data' => 'Edit personal data',
    // Grid: поля
    'Photo' => 'Photo',
    'User' => 'User',
    'First name' => 'First name',
    'Second name' => 'Second name',
    'Patronymic name' => 'Patronymic name',
    'Call name' => 'Call name',
    'Call name - this is name indicated in messages and letters' => 'Call name - this is name indicated in messages and letters',
    'User account - Active' => 'User account - Active',
    'User account' => 'User account',
    'Name' => 'Name',
    'Roles' => 'Roles',
    'Status' => 'Status',
    'Account status' => 'Account status',
    'Side' => 'Side',
    'Side (Website, Control panel) of user registration' => 'Side (Website, Control panel) of user registration',
    'Signin' => 'Signin',
    'Date' => 'Date',
    'Trial' => 'Trial',
    'Signin trial' => 'Signin trial',
    'Disabled date' => 'Disabled date',
    'Contact information' => 'Contact information',
    'Phone' => 'Phone',
    'The phone number can be used to recover or verify account' => 'The phone number can be used to recover or verify account',
    'E-mail' => 'E-mail',
    'E-mail can be used to recover account' => 'E-mail can be used to recover account',
    // Grid: статус пльзователя
    'Statuses' => [
        'Active' => 'Active',
        'Disabled' => 'Disabled',
        'Temporarily disabled' => 'Temporarily disabled',
        'Waiting' => 'Waiting',
    ],
    // Grid: всплывающие сообщения / заголовок
    'Enabled' => 'Подключен',
    'Disabled' => 'Отключен',
    // Grid: всплывающие сообщения / текст
    'User account - enabled' => 'User account &laquo;<b>{0}</b>&raquo; - <b>enabled</b>.',
    'User account - disabled' => 'User account &laquo;<b>{0}</b>&raquo; - <b>disabled</b>.',
    // Grid: аудит записей
    'enabled user account {0}' => 'enabled user account «<b>{0}</b>»',
    'disabled user account {0}' => 'disabled user account «<b>{0}</b>»',

    // Form
    '{form.title}' => 'Adding a user',
    '{form.titleTpl}' => 'Account change "{username}"',
    // Form: поля
    'Old account' => 'Old account',
    'Password' => 'Password',
    'Username' => 'Username',
    'New account' => 'New account',
    'Personal data' => 'Personal data',
    'Confirm' => 'Confirm',
    'Role' => 'Role',
    'Date of birth' => 'Date of birth',
    'Gender' => 'Gender',
    'Woman' => 'woman',
    'Man' => 'man',
    'Type' => 'Type',
    'The user account is created only for the Control Panel, and for the website on the side of the site' 
        => 'The user account is created only for the Control Panel, and for the website on the side of the site.',
    'information is used to protect the account' => 'information is used to protect the account',
    'Regional settings' => 'Regional settings',
    'Timezone' => 'Timezone',
    'If no time zone is selected, the time zone from the regional settings' 
        => 'If no time zone is selected, the time zone from the regional settings "Configuration Panel / Regional settings" will be used',
    // Form: ошибки
    'Invalid parameter passed' => 'Invalid parameter passed!',
    'Values of the fields do not match' => 'The values of the "Password" and "Password confirmation" fields do not match!',
    'A user with that name already exists' => 'A user with that name already exists!',
    'Incorrect password for your account' => 'Incorrect password for your account',
    'Your account has been successfully changed' => 'Your account has been successfully changed!',

    // ProfileForm
    '{profile.title}' => 'Adding user personal information',
    '{profile.titleTpl}' => 'Change of personal data "{callName}"',

    // AccountForm
    '{account.title}' => 'Adding an account',
    '{account.titleTpl}' => 'Account change "{username}"'
];
