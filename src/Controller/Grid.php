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
use Gm\Helper\Url;
use Gm\Panel\Widget\TabGrid;
use Gm\Panel\Helper\ExtGrid;
use Gm\Panel\Helper\ExtCombo;
use Gm\Panel\Helper\HtmlGrid;
use Gm\Panel\Data\Model\FormModel;
use Gm\Panel\Helper\HtmlNavigator as HtmlNav;
use Gm\Panel\Controller\GridController;

/**
 * Контроллер сетки пользователей.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Users\Controller
 * @since 1.0
 */
class Grid extends GridController
{
    /**
     * {@inheritdoc}
     */
    public function translateAction(mixed $params, string $default = null): ?string
    {
        switch ($this->actionName) {
            // изменение записи по указанному идентификатору
            case 'update':
                /** @var FormModel $model */
                $model = $this->lastDataModel;
                if ($model instanceof FormModel) {
                    return $this->module->t(($model->enabled > 0 ? 'disabled' :  'enabled') . ' user account {0}', [$model->username]);
                }

            default:
                return parent::translateAction($params, $default);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): TabGrid
    {
        /** @var \Gm\Backend\User\Helper\Helper $helper */
        $helper = $this->module->getHelper();

        /** @var \Gm\Panel\Widget\TabGrid $tab Сетка данных (Gm.view.grid.Grid GmJS) */
        $tab = parent::createWidget();

        // столбцы (Gm.view.grid.Grid.columns GmJS)
        $tab->grid->columns = [
            ExtGrid::columnNumberer(),
            ExtGrid::columnAction(),
            [
                'text'      => '#Photo',
                'dataIndex' => 'photoImg',
                'tdCls'     => 'g-grid-cell_img g-grid-cell_img_round',
                'maxWidth'  => 128,
                'hidden'    => true,
                'width'     => 64
            ],
            [
                'text'     => '#User',
                'sortable' => false,
                'columns'   => [
                    [
                        'text'      => '#Second name',
                        'tooltip'   => '#Second name',
                        'dataIndex' => 'secondName',
                        'cellTip'   => '{secondName}',
                        'filter'    => ['type' => 'string'],
                        'hidden'    => true,
                        'width'     => 150
                    ],
                    [
                        'text'    => ExtGrid::columnInfoIcon($this->t('First name')),
                        'cellTip' => HtmlGrid::tags([
                            [
                                'div',
                                [
                                    '{photoImg}',
                                    HtmlGrid::header('{firstName}'),
                                    HtmlGrid::fieldLabel($this->t('Call name'), '{callName}'),
                                    HtmlGrid::tplIf('secondName', HtmlGrid::fieldLabel($this->t('Second name'), '{secondName}'), ''),
                                    HtmlGrid::tplIf('patronymicName', HtmlGrid::fieldLabel($this->t('Patronymic name'), '{patronymicName}'), ''),
                                    HtmlGrid::fieldLabel(
                                        $this->t('Gender'),
                                        HtmlGrid::tplIf('gender==1', $this->t('Man'), $this->t('Woman'))
                                    ),
                                    HtmlGrid::tplIf(
                                        'dateOfBirth',
                                        HtmlGrid::fieldLabel($this->t('Date of birth'), '{dateOfBirth:date("' . Gm::$app->formatter->formatWithoutPrefix('dateFormat') . '")}'),
                                        ''
                                    ),
                                    HtmlGrid::tag('fieldset', [
                                        HtmlGrid::legend($this->t('User account')),
                                        HtmlGrid::fieldLabel($this->t('Username'), '{username}'),
                                        HtmlGrid::fieldLabel($this->t('Status'), '{status}'),
                                    ]),
                                    HtmlGrid::tag('fieldset', [
                                        HtmlGrid::legend($this->t('Signin')),
                                        HtmlGrid::fieldLabel($this->t('Date'), '{visitedDate:date("' . Gm::$app->formatter->formatWithoutPrefix('dateTimeFormat') . '")}'),
                                        HtmlGrid::fieldLabel($this->t('Signin trial'), '{visitedTrial}'),
                                        HtmlGrid::tplIf(
                                            'visitedDisabled',
                                            HtmlGrid::fieldLabel($this->t('Disabled date'), '{visitedDisabled:date("' . Gm::$app->formatter->formatWithoutPrefix('dateTimeFormat') . '")}'),
                                            ''
                                        )
                                    ]),
                                    HtmlGrid::tag('fieldset', [
                                        HtmlGrid::legend($this->t('Contact information')),
                                        HtmlGrid::tplIf(
                                            'phone',
                                            HtmlGrid::fieldLabel($this->t('Phone'), '{phone}'),
                                            ''
                                        ),
                                        HtmlGrid::fieldLabel('E-mail', '{email}')
                                    ])
                                ],
                                ['class' => 'g-grid-celltip__img']
                            ]
                        ]),
                        'dataIndex' => 'firstName',
                        'filter'    => ['type' => 'string'],
                        'width'     => 150
                    ],
                    [
                        'text'      => '#Patronymic name',
                        'tooltip'   => '#Patronymic name',
                        'dataIndex' => 'patronymicName',
                        'cellTip'   => '{patronymicName}',
                        'filter'    => ['type' => 'string'],
                        'hidden'    => true,
                        'width'     => 150
                    ],
                    [
                        'text'      => '#Call name',
                        'tooltip'   => '#Call name',
                        'dataIndex' => 'callName',
                        'cellTip'   => '{callName}',
                        'tooltip'    => '#Call name - this is name indicated in messages and letters',
                        'filter'    => ['type' => 'string'],
                        'width'     => 180
                    ]
                ]
            ],
            [
                'text'      => ExtGrid::columnIcon('g-icon-m_accessible', 'svg'),
                'xtype'     => 'g-gridcolumn-switch',
                'tooltip'   => '#User account - Active',
                'dataIndex' => 'enabled'
            ],
            [
                'text'     => '#User account',
                'sortable' => false,
                'columns'  => [
                    [
                        'text'      => '#Name',
                        'tooltip'   => '#Username',
                        'dataIndex' => 'username',
                        'cellTip'   => '{username}',
                        'filter'    => ['type' => 'string'],
                        'width'     => 130
                    ],
                    [
                        'xtype'      => 'templatecolumn',
                        'text'       => '#Roles',
                        'dataIndex'  => 'roles',
                        'sortable'   => false,
                        'tpl'        => HtmlGrid::tpl(
                            '<div>' . ExtGrid::renderIcon('g-icon_size_16 g-icon_gridcolumn-user-roles', 'svg') . ' {.}</div>',
                            ['for' => 'roles']
                        ),
                        'supplement' => true,
                        'width'      => 200
                    ],
                    [
                        'text'      => '#Status',
                        'tooltip'   => '#Account status',
                        'dataIndex' => 'status',
                        'cellTip'   => '{status}',
                        'width'     => 110
                    ],
                    [
                        'text'      => '#Side',
                        'tooltip'   => '#Side (Website, Control panel) of user registration',
                        'dataIndex' => 'sideName',
                        'cellTip'   => '{sideName}',
                        'width'     => 110
                    ],
                ]
            ],
            [
                'text'     => '#Signin',
                'sortable' => false,
                'columns'  => [
                    [
                        'xtype'     => 'datecolumn',
                        'text'      => '#Date',
                        'dataIndex' => 'visitedDate',
                        'format'    =>  Gm::$app->formatter->formatWithoutPrefix('dateTimeFormat'),
                        'filter'    => ['type' => 'date', 'dateFormat' => 'Y-m-d'],
                        'width'     => 145
                    ],
                    [
                        'text'      => '#Trial',
                        'tooltip'   => '#Signin trial',
                        'dataIndex' => 'visitedTrial',
                        'filter'    => ['type' => 'numeric', 'active' => false],
                        'hidden'    => true,
                        'width'     => 80
                    ],
                    [
                        'xtype'     => 'datecolumn',
                        'text'      => '#Disabled date',
                        'dataIndex' => 'visitedDisabled',
                        'format'    =>  Gm::$app->formatter->formatWithoutPrefix('dateTimeFormat'),
                        'filter'    => ['type' => 'date', 'dateFormat' => 'Y-m-d'],
                        'width'     => 145
                    ]
                ]
            ],
            [
                'text'      => '#Contact information',
                'sortable'  => false,
                'columns'   => [
                    [
                        'text'      => '#Phone',
                        'hidden'    => false,
                        'dataIndex' => 'phone',
                        'tooltip'   => '#The phone number can be used to recover or verify account',
                        'filter'    => ['type' => 'string'],
                        'width'     => 150
                    ],
                    [
                        'xtype'     => 'templatecolumn',
                        'hidden'    => false,
                        'text'      => 'E-mail',
                        'tooltip'   => '#E-mail can be used to recover account',
                        'dataIndex' => 'email',
                        'filter'    => ['type' => 'string'],
                        'tpl'       => '<tpl if="email"><a class="g-grid-cell_mail" href="mailto:{email}" target="_blank">{email}</a></tpl>',
                        'width'     => 150
                    ]
                ]
            ]
        ];

        // панель инструментов (Gm.view.grid.Grid.tbar GmJS)
        $tab->grid->tbar = [
            'padding' => 1,
            'items'   => ExtGrid::buttonGroups([
                'edit' => [
                    'items' => [
                        'add'    => [
                            'iconCls' => 'g-icon-svg g-icon_user-add',
                            'caching' => false
                        ],
                        'delete' => [
                            'iconCls' => 'g-icon-svg g-icon_user-delete',
                        ],
                        'cleanup',
                        '-',
                        'edit',
                        'select',
                        '-',
                        'refresh'
                    ]
                ],
                'columns',
                'search' => [
                    'items' => [
                        'help',
                        'search',
                        'filter' => [
                            'form'    => [
                                'cls'      => 'g-popupform-filter',
                                'width'    => 400,
                                'height'   => 'auto',
                                'action'   => Url::toMatch('grid/filter'),
                                'defaults' => ['labelWidth' => 130],
                                'items'    => [
                                    ExtCombo::local(
                                        '#Status',
                                        'status',
                                        [
                                            'fields' => ['id', 'name'],
                                            'data'   => $helper->getUserStatuses(true)
                                        ]
                                    ),
                                    ExtGrid::fieldsetAudit()
                                ]
                            ]
                        ]
                    ]
                ]
            ])
        ];

        // контекстное меню записи (Gm.view.grid.Grid.popupMenu GmJS)
        $tab->grid->popupMenu = [
            'items' => [
                [
                    'text'        => '#Edit account',
                    'iconCls'     => 'g-icon-svg g-icon-m_edit g-icon-m_color_default',
                    'handlerArgs' => [
                        'route'   => Gm::alias('@match', '/account/view/{id}'),
                        'pattern' => 'grid.popupMenu.activeRecord'
                    ],
                    'handler' => 'loadWidget'
                ],
                '-',
                [
                    'text'        => '#Edit personal data',
                    'iconCls'     => 'g-icon-svg g-icon-m_edit g-icon-m_color_default',
                    'handlerArgs' => [
                        'route'   => Gm::alias('@match', '/profile/view/{pid}'),
                        'pattern' => 'grid.popupMenu.activeRecord'
                    ],
                    'handler' => 'loadWidget'
                ]
            ]
        ];

        // сортировка таблицы
        $tab->grid->sorters = [
            ['property' => 'name', 'direction' => 'ASC']
        ];

        // 2-й клик по строке сетки
        $tab->grid->rowDblClickConfig = [
            'allow' => true,
            'route' => Gm::alias('@match', '/account/view/{id}')
        ];

        // количество строк в сетке
        $tab->grid->store->pageSize = 50;
        // поле аудита записи
        $tab->grid->logField = 'name';
        // плагины сетки
        $tab->grid->plugins = 'gridfilters';
        // класс CSS применяемый к элементу body сетки
        $tab->grid->bodyCls = 'g-grid_background';

        // панель навигации (Gm.view.navigator.Info GmJS)
        $tab->navigator->info['tpl'] = HtmlNav::tags([
            HtmlNav::image('{photoImg}', ['border' => true, 'round' => true, 'tag' => true]),
            HtmlNav::header('{firstName}'),
            HtmlNav::fieldLabel($this->t('Call name'), '{callName}'),
            HtmlNav::tplIf('secondName', HtmlNav::fieldLabel($this->t('Second name'), '{secondName}'), ''),
            HtmlNav::tplIf('patronymicName', HtmlNav::fieldLabel($this->t('Patronymic name'), '{patronymicName}'), ''),
            HtmlNav::fieldLabel(
                $this->t('Gender'),
                HtmlNav::tplIf('gender==1', $this->t('Man'), $this->t('Woman'))
            ),
            HtmlNav::tplIf(
                'dateOfBirth',
                HtmlNav::fieldLabel($this->t('Date of birth'), '{dateOfBirth:date("' . Gm::$app->formatter->formatWithoutPrefix('dateFormat') . '")}'),
                ''
            ),
            ['fieldset', [
                HtmlNav::legend($this->t('User account')),
                HtmlNav::fieldLabel($this->t('Username'), '{username}'),
                HtmlNav::fieldLabel($this->t('Status'), '{status}')]
            ],
            ['fieldset', [
                HtmlNav::legend($this->t('Signin')),
                HtmlNav::fieldLabel($this->t('Date'), '{visitedDate:date("' . Gm::$app->formatter->formatWithoutPrefix('dateTimeFormat') . '")}'),
                HtmlNav::fieldLabel($this->t('Signin trial'), '{visitedTrial}'),
                HtmlNav::tplIf(
                    'visitedDisabled',
                    HtmlNav::fieldLabel($this->t('Disabled date'), '{visitedDisabled:date("' . Gm::$app->formatter->formatWithoutPrefix('dateTimeFormat') . '")}'),
                    ''
                )]
            ],
            ['fieldset', [
                HtmlNav::legend($this->t('Contact information')),
                HtmlNav::tplIf(
                    'phone',
                    HtmlNav::fieldLabel($this->t('Phone'), '{phone}'),
                    ''
                ),
                HtmlNav::fieldLabel('E-mail', HtmlNav::a('{email}', 'mailto:{email}'))]
            ],
            HtmlNav::tplIf('enabled>0',
                HtmlNav::tag('fieldset',
                    [
                        HtmlNav::widgetButton(
                            $this->t('Edit account'),
                            ['route' => Gm::alias('@match', '/account/view/{id}'), 'long' => true],
                            ['title' => $this->t('Edit account')]
                        ),
                        HtmlNav::widgetButton(
                            $this->t('Edit personal data'),
                            ['route' => Gm::alias('@match', '/profile/view/{pid}'), 'long' => true],
                            ['title' => $this->t('Edit personal data')]
                        )
                    ]
                ), ''
            )
        ]);

        $tab
            ->addCss('/grid.css')
            ->addRequire('Gm.view.grid.column.Switch');
        return $tab;
    }
}
