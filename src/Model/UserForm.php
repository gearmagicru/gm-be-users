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
use Gm\Helper\Json;
use Gm\Panel\Data\Model\FormModel;

/**
 * Модель данных формы пользователя.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Users\Model
 * @since 1.0
 */
class UserForm extends FormModel
{
    /**
     * Поле "Роль пользователя".
     * 
     * @see UserForm::rolesValidate()
     * 
     * @var array
     */
    public array $roles = [];

    /**
     * Модель данных выпадающего списка тегов ролей.
     * 
     * @see UserForm::getRoleTags()
     * 
     * @var UserRoleTags
     */
    public UserRoleTags $roleTags;

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
                ['id'],
                ['username', 'label' => 'Username'],
                ['password', 'label' => 'Password'],
                ['status', 'label' => 'Status']
            ],
            'dependencies' => [
                'delete' => [
                    '{{user_roles}}'   => ['user_id' => 'id'],
                    '{{user_profile}}' => ['user_id' => 'id']
                ]
            ],
            // правила валидации полей
            'validationRules' => [
                [['username', 'password', 'status'], 'notEmpty'],
                // пароль
                [
                    'password',
                    'between',
                    'min' => 8, 'max' => 40, 'type' => 'string'
                ],
               // имя пользователя
               [
                'username',
                'between',
                'min' => 3, 'max' => 50, 'type' => 'string'
                ],
            ]
        ];
    }

    /**
     * Возвращает модель данных выпадающего списка тегов ролей.
     * 
     * @param int $userId
     * 
     * @return UserRoleTags
     */
    public function getRoleTags(int $userId): UserRoleTags
    {
        if (!isset($this->roleTags)) {
            $this->roleTags = $this->module->getModel('UserRoleTags');
        }
        $this->roleTags->userId = $userId;
        return $this->roleTags;
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
            })
            ->on(self::EVENT_AFTER_DELETE, function ($result, $message) {
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

        // скрыть пароль
        $this->unsetAttribute('password');
        // собрать все роли пользователя в строку
        $this->addAttribute('roles', $this->getRoleTags($this->getIdentifier())->getTags(true));
    }

    /**
     * Валидация аккаунта пользователя.
     * 
     * @param int $userId Идентификатор пользователя.
     * 
     * @return bool Если значение `true`, аккаунт пользователя прошел валидацию.
     */
    protected function userValidate(int $userId): bool
    {
        $db = $this->getDb();

        $select = $db
            ->select('{{user}}')
            ->columns(['*'])
            ->where(['username' => $this->username]);
        if ($userId)
            $select->where('id<>' . $userId);
        $row = $db->createCommand($select)->queryOne();
        if (!empty($row['id'])) {
            $this->addError($this->t('A user with that name already exists'));
            return false;
        }
        return true;
    }

    /**
     * Валидация ролей пользователя.
     * 
     * @return bool Если значение `true`, валидацию роли пользователя прошли успешно.
     */
    protected function rolesValidate(): bool
    {
        $roles = $this->getUnsafeAttribute('roles');
        if (empty($roles)) {
            $this->addError($this->errorFormatMsg(\Gm::t('app', "Value is required and can't be empty"), 'Role'));
            return false;
        }
        if ($roles) {
            $this->roles = Json::decode($roles);
            if ($error = Json::error()) {
                $this->addError($this->t($error));
                return false;
            }
        }
        return true;
    }

    /**
     * Валидация пароля пользователя.
     * 
     * @return bool Если значение `true`, пароль пользователя прошел валидацию.
     */
    protected function passwordValidate(): bool
    {
        $passwordConfirm = $this->getUnsafeAttribute('passwordConfirm');
        if (empty($passwordConfirm)) {
            $this->addError($this->errorFormatMsg(\Gm::t('app', "Value is required and can't be empty"), 'Confirm'));
            return false;
        }
        if (strcmp($this->password, $passwordConfirm) !== 0) {
            $this->addError($this->t('Values of the fields do not match'));
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function afterValidate(bool $isValid): bool
    {
        if ($isValid) {
            // проверка пользователя
            if (!$this->userValidate($this->getIdentifier()))
                return false;
            // проверка ролей
            if (!$this->rolesValidate())
                return false;
            // проверка пароля
            if (!$this->passwordValidate())
                return false;
        }
        return $isValid;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave(bool $isInsert): bool
    {
        if (User::STATUS_TEMPORARILY_DISABLED == $this->status) {
            $this->visitedDisabled = date('Y-m-d H:i:S');
        }
        // генерация пароля
        $this->password = Gm::$app->encrypter->encodePassword($this->password);

        return parent::beforeSave($isInsert);
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave(
        bool $isInsert, 
        array $columns = null, 
        false|int|string|null $result = null
    ): void
    {
        // сохранить роли пользователя
        $this->getRoleTags($isInsert ? $result : $this->getIdentifier())
                    ->saveTags($this->roles);

        parent::afterSave($isInsert, $columns, $result);
    }
}
