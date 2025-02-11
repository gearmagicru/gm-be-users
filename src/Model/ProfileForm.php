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
 * Модель данных профиля пользователей.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\UserProfiles\Model
 * @since 1.0
 */
class ProfileForm extends FormModel
{
    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'tableName'  => '{{user_profile}}',
            'primaryKey' => 'id',
            'fields'     => [
                ['id'],
                ['user_id', 'alias' => 'userId'],
                ['first_name', 'alias' => 'firstName', 'label' => 'First name'],
                ['second_name', 'alias' => 'secondName', 'label' => 'Second name'],
                ['patronymic_name', 'alias' => 'patronymicName', 'label' => 'Patronymic name'],
                ['call_name', 'alias' => 'callName', 'label' => 'Call name'],
                ['photo'],
                ['gender', 'label' => 'Gender'],
                ['date_of_birth', 'alias' => 'dateOfBirth', 'label' => 'Date of birth'],
                ['phone', 'label' => 'Phone'],
                ['email', 'label' => 'E-mail'],
                ['timezone', 'alias' => 'timeZone',  'label' => 'Timezone']
            ],
            'uniqueFields' => ['email', 'phone'],
            // правила форматирования полей
            'formatterRules' => [
                [['firstName', 'secondName', 'patronymicName', 'callName', 'phone', 'email'], 'safe'],
                ['timeZone', 'combo'],
                ['gender', 'type' => ['int']],
                ['dateOfBirth', 'date'],
            ],
            // правила валидации полей
            'validationRules' => [
                [['callName', 'firstName', 'email'], 'notEmpty'],
                // обращение
                [
                    'callName',
                    'between',
                    'max' => 255, 'type' => 'string'
                ],
                // имя
                [
                    'firstName',
                    'between',
                    'max' => 100, 'type' => 'string'
                ],
                // email
                [
                    'email',
                    'between',
                    'max' => 100, 'type' => 'string'
                ]
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
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                /** @var \Gm\Panel\Controller\FormController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            });
    }

    /**
     * {@inheritdoc}
     */
    public function processing(): void
    {
        parent::processing();

        /** @var \Gm\Panel\User\UserIdentity userIdentity  */
        $userIdentity = Gm::userIdentity();
        /** @var \Gm\Panel\User\UserProfile $source  */
        $source = $userIdentity->getProfile()
            ->getPicture()
                ->defineSource($this->photo, (int) $this->gender);
        // если нет фото, тогда по умолчанию
        if ($source['default']) {
            $attributes = [
                'class'         => 'g-icon-svg g-icon_' . $source['name'],
                'data-uploaded' => 0
            ];
        // если фото указано
        } else {
            $attributes = [
                'class'         => '',
                'src'           => $source['url'] . '?_dc=' . time(),
                'data-uploaded' => 1
            ];
        }
        $this->response()
            ->meta
                ->cmdElement('g-profile__photo', 'set', [$attributes]);
    }

    /**
     * Связывает профиль пользователя с его учётной записью.
     * 
     * @param FormModel $user Учётная запись пользователя.
     * 
     * @return void
     */
    public function bindUser(FormModel $user): void
    {
        // если пользователь добавлен, то $user->id === null
        $this->userId = $user->id ?: $user->getResult();
    }
}
