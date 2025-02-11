<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Users\Model;

use Gm\Panel\Data\Model\Combo\TagComboModel;

/**
 * Модель данных выпадающего списка тегов ролей пользователей.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Users\Model
 * @since 1.0
 */
class UserRoleTags extends TagComboModel
{
    /**
     * Идентификатор пользователя.
     * 
     * @var int|int
     */
    public ?int $userId = null;

    /**
     * Возвращает все идентификаторы тегов.
     *
     * @return array
     */
    public function getAllTags(): array
    {
        /** @var \Gm\Db\Adapter\Adapter $db */
       $db = $this->getDb();

        /** @var \Gm\Db\Sql\Select $select */
        $select = $db
            ->select('{{role}}')
            ->columns(['id', 'name']);
        return $db->createCommand($select)->queryToCombo('id', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function getTags(bool $toString = false): string|array
    {
        /** @var \Gm\Db\Adapter\Adapter $db */
       $db = $this->getDb();

       /** @var \Gm\Db\Sql\Select $select */
        $select = $db
            ->select('{{user_roles}}')
            ->columns(['role_id'])
            ->where(['user_id' => $this->userId]);

        $rows = $db->createCommand($select)->queryColumn();
        if ($toString)
            return implode(',', $rows);
        else
            return $rows;
    }

    /**
     * {@inheritdoc}
     */
    public function addTags(array $tags): void
    {
        foreach ($tags as $id) {
            $this->insertRecord(
                ['user_id' => $this->userId, 'role_id' => $id],
                '{{user_roles}}'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteTags(array $tags): false|int
    {
        return $this->deleteRecord(
            ['user_id' => $this->userId, 'role_id' => $tags],
            '{{user_roles}}'
        );
    }
}
