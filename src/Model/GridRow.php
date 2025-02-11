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
use Gm\Panel\Data\Model\FormModel;

/**
 * Модель данных профиля записи пользователя.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Users\Model
 * @since 1.0
 */
class GridRow extends FormModel
{
    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'lockRows'   => true,
            'useAudit'   => true,
            'tableName'  => '{{user}}',
            'primaryKey' => 'id',
            'fields' => [
                ['id'],
                ['username'],
                ['status', 'alias' => 'enabled']
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_SAVE, function ($isInsert, $columns, $result, $message) {
                if ($message['success']) {
                    $message['message'] = $this->module->t('User account - ' . ($this->enabled > 0 ? 'disabled' : 'enabled'), [$this->username]);
                    $message['title']   = $this->t($this->enabled > 0 ? 'Disabled' : 'Enabled');
                }
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
            });
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave(bool $isInsert): bool
    {
        $this->enabled = (int) $this->enabled;
        if ($this->enabled > 0)
            $this->enabled = 0;
        else
            $this->enabled = 1;

        return parent::beforeSave($isInsert);
    }
}
