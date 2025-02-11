<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Users\Model;

use Gm;
use Gm\Db\ActiveRecord;

/**
 * Модель данных пользователя.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Users\Model
 * @since 1.0
 */
class User extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function primaryKey(): string
    {
        return 'id';
    }

    /**
     * {@inheritdoc}
     */
    public function tableName(): string
    {
        return '{{user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'id' => 'id',
            'username' => 'username',
            'password' => 'password',
            'visitedDate' => 'visited_date',
            'visitedDisabled' => 'visited_disabled',
            'visitedTrial' => 'visited_trial',
            'visitedDevice' => 'visited_device',
            'status' => 'status',
            'process' => 'process',
            'settings' => 'settings',
            'side' => 'side',
            'updatedDate' => '_updated_date',
            'updatedUser' => '_updated_user',
            'createdDate' => '_created_date',
            'createdUser' => '_created_user',
            'lock' => '_lock'
        ];
    }
}
