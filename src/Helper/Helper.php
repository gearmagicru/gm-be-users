<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Users\Helper;

use Gm;
use Gm\Data\Model\DataModel;
use Gm\Panel\Helper\ExtCombo;

/**
 * Помощник пользователей.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Users\Helper
 * @since 1.0
 */
class Helper extends DataModel
{
    /**
     * Возвращает статусы пользователей.
     * 
     * @param bool $withNoneItems Если true, будет добавлен элемент "[ без выбора ]".
     * 
     * @return array
     */
    public function getUserStatuses(bool $withNoneItems = false): array
    {
        $names = $this->t('Statuses');
        $items = [];
        if ($withNoneItems) {
            $items = ExtCombo::noneItem(true);
        }
        foreach(Gm::$app->user->statuses as $code => $name) {
            $items[] = [
                $code,
                $names[$name] ?? $name
            ];
        }
        return $items;
    }

    /**
     * Присоединить аккаунт к профилю пользователя.
     * 
     * @param int $userId Идентификатор пользователя.
     * @param int $profileId Идентификатор профиля пользователя.
     * 
     * @return false|int
     */
    public function bindUserProfile(int $userId, int $profileId): false|int
    {
        return $this->updateRecord(
            array('user_id' => $userId),
            array('id' => $profileId),
            '{{user_profile}}'
        );
    }

    /**
     * Отсоединить аккаунт профиля пользователя.
     * 
     * @param int $userId Идентификатор пользователя.
     * 
     * @return false|int
     */
    public function unbindUserProfile(int $userId): false|int
    {
        $condition = ['_lock' => 0];
        if ($userId)
            $condition['user_id'] = $userId;

        return $this->updateRecord(
            ['user_id' => null],
            $condition,
            '{{user_profile}}'
        );
    }
}
