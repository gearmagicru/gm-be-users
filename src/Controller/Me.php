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
use Gm\Panel\Widget\Form;
use Gm\Panel\Helper\ExtForm;
use Gm\Panel\Widget\EditWindow;
use Gm\Panel\Controller\FormController;

/**
 * Контроллер формы пользователя.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Users\Controller
 * @since 1.0
 */
class Me extends FormController
{
    /**
     * {@inheritDoc}
     */
    protected string $defaultModel = 'Me';

    /**
     * {@inheritdoc}
     */
    public function translateAction(mixed $params, string $default = null): ?string
    {
        /** @var \Gm\Panel\User\UserIdentity $identity */
        $identity = Gm::userIdentity();
        if ($identity) {
            $params->queryId = $identity->getId();
        }
        return parent::translateAction($params, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): EditWindow
    {
        /** @var EditWindow $window */
        $window = parent::createWidget();
        
        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->title = '#{form.title}';
        $window->titleTpl = '#{form.titleTpl}';
        $window->iconCls = 'g-icon-svg g-icon-m_edit';
        $window->width = 460;
        $window->autoHeight = true;
        $window->layout = 'fit';
        $window->resizable = false;

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->bodyPadding = 5;
        $window->form->autoScroll = true;
        $window->form->router->setAll([
            'id'    => Gm::$app->user->getId(),
            'route' => Gm::alias('@match', '/me'),
            'state' => Form::STATE_CUSTOM,
            'rules' => [
                'update' => '{route}/update',
                'data'   => '{route}/data'
            ]
        ]);
        $window->form->buttons = ExtForm::buttons(['help', 'save', 'cancel']);
        $window->form->loadJSONFile('/me-account-form', 'items');
        $window
            ->setNamespaceJS('Gm.be.users')
            ->addRequire('Gm.be.users.VTypes');
        return $window;
    }
}
