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
 * Модель данных профиля пользователя.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Users\Model
 * @since 1.0
 */
class Me extends FormModel
{
    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'lockRows'   => false, // чтобы пользователь смог сам поменять пароль
            'useAudit'   => true,
            'tableName'  => '{{user}}',
            'tableAlias' => 'user',
            'primaryKey' => 'id',
            'fields'     => [
                ['id'],
                ['username', 'label' => 'Username'],
                ['password', 'label' => 'Password']
            ],
            // правила валидации полей
            'validationRules' => [
                [['username', 'password'], 'notEmpty'],
                // имя пользователя
                [
                    'username',
                    'between',
                    'min' => 3, 'max' => 50, 'type' => 'string'
                ],
                // пароль
                [
                    'password',
                    'between',
                    'min' => 8, 'max' => 40, 'type' => 'string'
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
            ->on(self::EVENT_BEFORE_SAVE, function ($isInsert, &$canSave) {
                $this->password = Gm::$app->encrypter->encodePassword($this->password);
            })
            ->on(self::EVENT_AFTER_SAVE, function ($isInsert, $columns, $result, $message) {
                if ($message['success']) {
                    $message['message'] = $this->t('Your account has been successfully changed');
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
    public function processing(): void
    {
        parent::processing();

        // скрыть пароль
        $this->unsetAttribute('password');
    }

    /**
     * Валидация пароля пользователя (подтверждение).
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
     * Валидация аккаунта пользователя.
     * 
     * @return bool Если true, аккаунт пользователя прошел валидацию.
     */
    protected function accountValidate(): bool
    {
        $passwordReset = $this->getUnsafeAttribute('passwordReset');
        if (empty($passwordReset)) {
            $this->addError($this->errorFormatMsg(\Gm::t('app', "Value is required and can't be empty"), 'Confirm'));
            return false;
        }
        /** @var \Gm\Panel\User\UserIdentity $identity */
        $identity = Gm::userIdentity();

        /** @var null|array $me Пароля нет в кэше, только в базе */
        $me = $identity->findOne(['id' => $identity->getId()]);
        if (empty($me)) {
            $this->addError($this->t('Incorrect password for your account'));
            return false;
        }
        // проверка старого пароля аккаунта пользователя
        $error = !Gm::$app->encrypter->verifyPassword($me['password'], $passwordReset);
        if ($error) {
            $this->addError($this->t('Incorrect password for your account'));
            return false;
        }
        // поиск аккаунта с новым именем пользователя
        $account = $identity->findOne(['username' => $this->username]);
        if ($account) {
            // если найденный аккаунт не является аккаунтом пользователя
            // который его меняет
            if ($account['id'] != $identity->getId()) {
                $this->addError($this->t('A user with that name already exists'));
                return false;
            }
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function afterValidate(bool $isValid): bool
    {
        if ($isValid) {
            // проверка пароля
            if (!$this->passwordValidate())
                return false;
            if (!$this->accountValidate())
                return false;
        }
        return $isValid;
    }

    /**
     * {@inheritdoc}
     */
    public function get(mixed $identifier = null): ?static
    {
        if ($identifier === null) {
            $identifier = Gm::$app->user->getId();
        }
        return $this->selectByPk($identifier);
    }
}
