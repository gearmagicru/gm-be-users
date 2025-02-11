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
use Gm\Db\Sql;
use Gm\Helper\Html;
use Gm\Panel\User\UserProfilePicture;
use Gm\Panel\Data\Model\Combo\ComboModel;

/**
 * Модель данных выпадающего списка пользователей.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Users\Model
 * @since 1.0
 */
class UserCombo extends ComboModel
{
    /**
     * Каталог загрузки изображений.
     * 
     * @var string
     */
    protected string $uploadDir;

    /**
     * URL загрузки изображений.
     * 
     * @var string
     */
    protected string $uploadUrl;

    /**
     * Изображение профиля.
     * 
     * @var UserProfilePicture
     */
    protected UserProfilePicture $picture;

    /**
     * Содержимое изображение (пустышка в base64).
     * 
     * @var string
     */
    protected string $imgDataSrc;

    /**
     * Принадлежность пользователя к сайту или Панели управления.
     * 
     * Варианты:
     * - null, сайт или Панель управления;
     * - 1, сайт;
     * - 2, панель управления.
     * 
     * @var int|null
     */
    protected ?int $side = null;

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        // принадлежность пользователя
        $side = Gm::$app->request->getQuery('side', null);
        if ($side !== null) {
            $this->side = (int) $side;
        }

        $this->uploadDir  = Gm::alias('@upload', '/profile/');
        $this->uploadUrl  = Gm::alias('@upload::', '/profile/');
        $this->picture    = Gm::userIdentity()->getProfile()->getPicture();
        $this->imgDataSrc = Html::imgDataSrc();
    }

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'tableName'  => '{{user_profile}}',
            'primaryKey' => 'id',
            'searchBy'   => 'call_name',
            'order'      => ['call_name' => 'ASC'],
            'fields'     => [
                ['user_id'],
                ['call_name'],
                ['gender'],
                ['photo']
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildFilter(Sql\AbstractSql $operator): void
    {
        if ($this->search) {
            $operator->where->like($this->dataManager->searchBy, '%' . $this->search . '%');
        }
        // если пользователи сайта или Панели управления, иначе все
        if ($this->side !== null) {
            $operator->where(['side' => $this->side]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fetchRow(array $row): array
    {
        $source = $this->picture->defineSource($row['photo'], (int) $row['gender']);
        if ($source['default'])
            $row['photo'] = '<img class="g-boundlist-img g-icon-svg g-icon_' . $source['name'] . '" src="' . $this->imgDataSrc . '">';
        else
            $row['photo'] = '<img class="g-boundlist-img g-boundlist-img_round" src="' . $source['url'] . '">';
        return $row;
    }

    /**
     * {@inheritdoc}
     */
    public function afterFetchRow(array $row, array &$rows): void
    {
        $rows[] = [$row['user_id'], $row['call_name'], $row['photo']];
    }
}