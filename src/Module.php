<?php
/**
 * Модуль веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Users;

/**
 * Модуль пользователей.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Users
 * @since 1.0
 */
class Module extends \Gm\Panel\Module\Module
{
    /**
     * {@inheritdoc}
     */
    public string $id = 'gm.be.users';

    /**
     * {@inheritdoc}
     */
    public function controllerMap(): array
    {
        return [
            'account' => 'AccountForm', // учётная запись пользователя
            'profile' => 'ProfileForm' // профиль пользователя
        ];
    }
}
