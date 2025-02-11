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
use Gm\User\User;
use Gm\Helper\Html;
use Gm\Panel\Data\Model\GridModel;
use Gm\Panel\User\UserProfilePicture;

/**
 * Модель данных списка пользователей.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Users\Model
 * @since 1.0
 */
class Grid extends GridModel
{
    /**
     * {@inheritdoc}
     */
    public bool $collectRowsId = true;

    /**
     * Статусы пользователей.
     * 
     * @see Grid::init()
     * 
     * @var array
     */
    protected array $statuses = [];

    /**
     * Изображение профиля пользователя.
     * 
     * @var UserProfilePicture
     */
    protected UserProfilePicture $picture;

    /**
     * Сторона регистрации пользователя.
     *
     * @var array
     */
    protected array $sideNames = [];

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'lockRows'   => true,
            'useAudit'   => true,
            'tableName'  => '{{user}}',
            'tableAlias' => 'user',
            'primaryKey' => 'id',
            'fields'     => [
                ['pid'],
                ['photo'],
                ['gender'],
                ['date_of_birth', 'alias' => 'dateOfBirth'],
                ['alias' => 'photoImg', 'direct' => 'photo', 'render' => 'photoImg'],
                ['second_name', 'alias' => 'secondName', 'direct' => 'profile.second_name'],
                ['first_name', 'alias' => 'firstName', 'direct' => 'profile.first_name'],
                ['patronymic_name', 'alias' => 'patronymicName', 'direct' => 'profile.patronymic_name'],
                ['call_name', 'alias' => 'callName', 'direct' => 'profile.call_name'],
                ['enabled'],
                ['username'],
                ['status'],
                ['visited_date', 'alias' => 'visitedDate', 'filterType' => 'datetime'],
                ['visited_disabled', 'alias' => 'visitedDisabled'],
                ['visited_trial', 'alias' => 'visitedTrial'],
                ['phone', 'direct' => 'profile.phone'],
                ['email', 'direct' => 'profile.email'],
                // динамическое поля
                ['sideName', 'direct' => 'user.side']
            ],
            'dependencies' => [
                'deleteAll' => [
                   '{{user_roles}}'   => 'DELETE {{user_roles}} FROM {{user_roles}}, {{user}} WHERE {{user_roles}}.user_id={{user}}.id AND {{user}}._lock<>1',
                   '{{user_profile}}' => 'DELETE {{user_profile}} FROM {{user_profile}}, {{user}} WHERE {{user_profile}}.user_id={{user}}.id AND {{user}}._lock<>1'
                ],
                'delete'    => [
                    '{{user_roles}}'   => ['user_id' => 'id'],
                    '{{user_profile}}' => ['user_id' => 'id']
                ]
            ],
            'filter' => [
                'status' => ['operator' => '='],
                'logUser' => ['operator' => '='],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        // статусы пользователя
        $this->statuses = $this->module->t('Statuses');
        $this->sideNames = [
            Gm::t(BACKEND, BACKEND_NAME),
            Gm::t(BACKEND, FRONTEND_NAME)
        ];

        /** @var null|\Gm\Panel\User\UserProfile $profile */
        $profile = Gm::userIdentity()->getProfile();
        $this->picture = $profile ? $profile->getPicture() : null;
        $this
            ->on(self::EVENT_AFTER_DELETE, function ($someRecords, $result, $message) {
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                /** @var \Gm\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            })
            ->on(self::EVENT_AFTER_SET_FILTER, function ($filter) {
                /** @var \Gm\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            });
    }

    /**
     * Возвращает перевода статуса пользователя.
     * 
     * @param int $status Идентификатор статуса пользователя.
     * 
     * @return string|int Если идентификатор определен, возвратит перевод.
     */
    protected function translateStatus(int $code): string|int
    {
        if (isset(Gm::$app->user->statuses[$code])) {
            $name = Gm::$app->user->statuses[$code];
            return $this->statuses[$name] ?? $name;
        }
        return $code;
    }

    /**
     * Возвращает тег изображения.
     * 
     * @param mixed $value Значение поля установленного методом {@see \Gm\Panel\Data\GridModel::maskedFetchRow()}.
     * @param array $row Имена полей с их значениями полученные методом {@see \Gm\Panel\Data\GridModel::fetchRow()}.
     * @param array $options Настройки полей из раздела менеджера данных {@see \Gm\Data\DataManager::$fieldOptions}.
     * 
     * @return string
     */
    public function photoImg(mixed $value, array $row, array $options): string
    {
        if ($this->picture) {
            $source = $this->picture->defineSource($row['photo'], (int) $row['gender']);
            if ($source['default'])
                return '<img class="g-icon-svg g-icon_' . $source['name'] . '" src="' . Html::imgDataSrc() . '">';
            else
                return '<img src="' . $source['url'] . '">';
        } else
            return '';
    }

    /**
     * {@inheritdoc}
     */
    public function fetchRow(array $row): array
    {
        // сторона регистрации пользователя
        $row['sideName'] = $this->sideNames[(int) $row['side']];
        // если аккаунт доступен
        if ($row['_lock'] > 0)
            $row['enabled'] = -1;
        else
            $row['enabled'] = $row['status'] > User::STATUS_ACTIVE ? 0 : 1;
        // статус пользователя
        $row['status'] = $this->translateStatus($row['status']);
        // выставить дате часовой пояс пользователя
        if (!empty($row['visited_date'])) {
            $row['visited_date'] = Gm::$app->formatter->toDateTimeZone($row['visited_date'], 'Y-m-d H:i:s', false, Gm::$app->dataTimeZone, Gm::$app->user->getTimeZone());
        }
        return $row;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareRow(array &$row): void
    {
        // заголовок контекстного меню записи
        $row['popupMenuTitle'] = $row['username'];
    }

    /**
     * {@inheritdoc}
     */
    public function getRows(): array
    {
        /**
         * представление для ролей:
         * 
         * CREATE VIEW `{{user_role_names}}` AS SELECT `urole`.`user_id`, GROUP_CONCAT(`role`.`name` SEPARATOR ', ') `names` FROM `{{user_roles}}` `urole` JOIN `{{role}}` `role` ON (`role`.`id`=`urole`.`role_id`) GROUP BY `urole`.`user_id`
         */
        return $this->selectBySql(
            'SELECT SQL_CALC_FOUND_ROWS `profile`.*,`profile`.`id` `pid`, `user`.* '
          . 'FROM `{{user}}` `user` '
          . 'JOIN `{{user_profile}}` `profile` ON `profile`.`user_id`=`user`.`id` '
        );
    }


    /**
     * {@inheritdoc}
     */
    public function getSupplementRows(): array
    {
        $rows = [];
        // если есь записи
        if ($this->rowsId) {
            /** @var \Gm\Db\Adapter\Adapter $db */
            $db = $this->getDb();
            // элементы (роли пользователей) главного меню
            /** @var \Gm\Db\Adapter\Driver\AbstractCommand $command */
            $command = $db->createCommand(
                'SELECT `urole`.`user_id`, `role`.`name` '
              . 'FROM {{role}} `role` '
              . 'JOIN {{user_roles}} `urole` ON `urole`.`role_id`=`role`.`id` '
              . 'WHERE `urole`.`user_id` IN (:users)'
            );
            $command
                ->bindValues([':users' => $this->rowsId])
                ->execute();
            while ($row = $command->fetch()) {
                $id = $row['user_id'];
                if (!isset($rows[$id])) {
                    $rows[$id] = [
                        'id' => $id, 'roles' => []
                    ];
                }
                $rows[$id]['roles'][] = $row['name'];
            }
        }
        return array_values($rows);
    }
}
