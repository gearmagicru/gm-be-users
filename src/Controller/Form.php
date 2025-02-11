<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Users\Controller;

use Gm;
use Gm\Helper\Html;
use Gm\Panel\Http\Response;
use Gm\Panel\Helper\ExtCombo;
use Gm\Panel\Widget\EditWindow;
use Gm\Panel\Controller\FormController;

/**
 * Контроллер формы добавления пользователя.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Users\Controller
 * @since 1.0
 */
class Form extends FormController
{
    /**
     * {@inheritdoc}
     */
    public function createWidget(): EditWindow
    {
        /** @var \Gm\Backend\Users\Helper\Helper $helper */
        $helper = $this->module->getHelper();
        /** @var \Gm\Backend\Users\Model\UserRoleTags $roleTags */
        $roleTags = $this->module->getModel('UserRoleTags');

        /** @var EditWindow $window */
        $window = parent::createWidget();

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->autoScroll = true;
        $window->form->controller = 'gm-be-users-form';
        $window->form->loadJSONFile('/form', 'items', [
            '@comboStoreUrl' => [Gm::alias('@match', '/trigger/combo')],
            '@userStatuses'  => $helper->getUserStatuses(),
            '@roleTags'      => $roleTags->getAllTags(),
            // фото
            '@photo' => Html::tag(
                'div',
                Html::tag(
                    'img',
                    '',
                    [
                        'id'            => 'g-users__photo',
                        'class'         => 'g-icon-svg g-icon_user-none',
                        'src'           => Html::imgDataSrc(),
                        'data-src'      => Html::imgDataSrc(),
                        'data-uploaded' => 0
                    ]
                ),
                ['class' => 'g-users__photo']
            ),
            '@comboTimeZones' => ExtCombo::timezones('#Timezone', 'timeZone', true, [
                'tooltip' => '#If no time zone is selected, the time zone from the regional settings'
            ])
        ]);

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->width = 500;
        $window->height = 620;
        $window->layout = 'fit';
        $window->resizable = false;
        $window
            ->setNamespaceJS('Gm.be.users')
            ->addRequire('Gm.be.users.FormController')
            ->addRequire('Gm.be.users.VTypes')
            ->addCss('/form.css');
        return $window;
    }

    /**
     * {@inheritdoc}
     */
    public function addAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();
        /** @var \Gm\Http\Request $request */
        $request  = Gm::$app->request;

        /** @var \Gm\Backend\Users\Model\UserForm $modUser Модель данных формы аккаунта пользователя */
        $modUser = $this->getModel('UserForm');
        if ($modUser === false) {
            $response
                ->meta->error(Gm::t('app', 'Could not defined data model "{0}"', ['UserForm']));
            return $response;
        }

        /** @var \Gm\Backend\Users\Model\ProfileForm $modProfile Модель данных формы профиля пользователя */
        $modProfile = $this->getModel('ProfileForm');
        if ($modProfile === false) {
            $response
                ->meta->error(Gm::t('app', 'Could not defined data model "{0}"', ['ProfileForm']));
            return $response;
        }

        if ($this->useAppEvents) {
            Gm::$app->doEvent($this->makeAppEventName(), [$this, [$modUser, $modProfile]]);
        }

        // загрузка атрибутов в модель из запроса
        if (!$modUser->load($request->getPost())) {
            $response
                ->meta->error(Gm::t(BACKEND, 'No data to perform action'));
        }
        if (!$modProfile->load($request->getPost())) {
            $response
                ->meta->error(Gm::t(BACKEND, 'No data to perform action'));
        }

        // валидация атрибутов модели
        if (!$modUser->validate()) {
            $response
                ->meta->error(Gm::t(BACKEND, 'Error filling out form fields: {0}', [$modUser->getError()]));
            return $response;
        }
        if (!$modProfile->validate()) {
            $response
                ->meta->error(Gm::t(BACKEND, 'Error filling out form fields: {0}', [$modProfile->getError()]));
            return $response;
        }

        // сохранение атрибутов модели
        if (!$modUser->save()) {
            $response
                ->meta->error(
                    $modUser->hasErrors() ? $modUser->getError() : Gm::t(BACKEND, 'Could not add data')
                );
            return $response;
        }

        $modProfile->bindUser($modUser);

        // сохранение атрибутов модели
        if (!$modProfile->save()) {
            $modUser->delete();
            $response
                ->meta->error(
                    $modUser->hasErrors() ? $modUser->getError() : Gm::t(BACKEND, 'Could not add data')
                );
            return $response;
        }

        if ($this->useAppEvents) {
            Gm::$app->doEvent($this->makeAppEventName('After'), [$this, [$modUser, $modProfile]]);
        }
        return $response;
    }
}
