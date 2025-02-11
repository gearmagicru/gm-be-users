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
use Gm\Panel\Helper\ExtForm;
use Gm\Panel\Helper\ExtCombo;
use Gm\Panel\Widget\EditWindow;
use Gm\Panel\Controller\FormController;

/**
 * Контроллер формы профиля пользователя.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Users\Controller
 * @since 1.0
 */
class ProfileForm extends FormController
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultModel = 'ProfileForm';

    /**
     * {@inheritdoc}
     */
    public function createWidget(): EditWindow
    {
        /** @var EditWindow $window */
        $window = parent::createWidget();

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->buttons = ExtForm::buttons(['help' => ['subject' => 'profile'], 'save', 'cancel']);
        $window->form->router['route'] = Gm::alias('@match', '/profile');
        $window->form->autoScroll = true;
        $window->form->bodyPadding = 5;
        $window->form->layout = 'anchor';
        $window->form->controller = 'gm-be-users-form';
        $window->form->loadJSONFile('/profile-form', 'items', [
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
        $window->title = '#{profile.title}';
        $window->titleTpl = '#{profile.titleTpl}';
        $window->width = 500;
        $window->autoHeight = true;
        $window->resizable = false;
        $window
            ->setNamespaceJS('Gm.be.users')
            ->addRequire('Gm.be.users.FormController')
            ->addCss('/form.css');
        return $window;
    }
}
