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
use Gm\Panel\Helper\ExtForm;
use Gm\Panel\Widget\EditWindow;
use Gm\Panel\Controller\FormController;

/**
 * Контроллер формы учётной записи пользователя.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Users\Controller
 * @since 1.0
 */
class AccountForm extends FormController
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultModel = 'UserForm';

    /**
     * {@inheritdoc}
     */
    public function createWidget(): EditWindow
    {
        /** @var \Gm\Backend\Users\Helper\Helper $helper */
        $helper = $this->module->getHelper();
        /** @var \Gm\Backend\Users\Model\UserRoleTags $roleTags */
        $roleTags = $this->module->getModel('UserRoleTags');

        /** @var \Gm\Panel\Widget\EditWindow $window */
        $window = parent::createWidget();

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->buttons = ExtForm::buttons(['help' => ['subject' => 'account'], 'save', 'delete', 'cancel']);
        $window->form->router['route'] = Gm::alias('@match', '/account');
        $window->form->autoScroll = true;
        $window->form->bodyPadding = 10;
        $window->form->layout = 'anchor';
        $window->form->defaults = [
            'labelWidth' => 150,
            'labelAlign' => 'right',
            'anchor'     => '100%'
        ];
        $window->form->loadJSONFile('/account-form', 'items', [
            '@comboStoreUrl' => [Gm::alias('@match', '/trigger/combo')],
            '@userStatuses'  => $helper->getUserStatuses(),
            '@roleTags'      => $roleTags->getAllTags(),
        ]);

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->title = '#{account.title}';
        $window->titleTpl = '#{account.titleTpl}';
        $window->width = 500;
        $window->height = 400;
        $window->layout = 'fit';
        $window->resizable = false;
        $window
            ->setNamespaceJS('Gm.be.users')
            ->addRequire('Gm.be.users.VTypes')
            ->addCss('/form.css');
        return $window;
    }
}
