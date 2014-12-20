<?php
/**
 * @link https://github.com/gromver/yii2-platform-basic.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-platform-basic/blob/master/LICENSE
 * @package yii2-platform-basic
 * @version 1.0.0
 */

namespace gromver\platform\basic\modules\main;

use gromver\modulequery\ModuleQuery;
use gromver\platform\basic\components\MenuManager;
use gromver\platform\basic\interfaces\MenuItemRoutesInterface;
use gromver\platform\basic\main\models\Table;
use gromver\platform\basic\interfaces\DesktopInterface;
use gromver\platform\basic\menu\models\MenuItem;
use Yii;
use yii\base\BootstrapInterface;
use yii\caching\ExpressionDependency;
use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package yii2-platform-basic
 * @author Gayazov Roman <gromver5@gmail.com>
 *
 * @property string $siteName
 * @property bool $isEditMode
 */
class Module extends \yii\base\Module implements BootstrapInterface, DesktopInterface, MenuItemRoutesInterface
{
    const SESSION_KEY_MODE = '__grom_mode';

    const MODE_EDIT = 'edit';
    const MODE_VIEW = 'view';

    public $controllerNamespace = '\gromver\platform\basic\modules\main\controllers';
    public $paramsPath = '@app/config/grom';
    // todo fixthis
    public $blockModules = ['news', 'page', 'tag', 'user'];   //список модулей к которым нельзя попасть на прямую(grom/post/..., grom/page/...)
    public $desktopOrder = 1;

    private $_mode;

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->set($this->id, $this);

        Table::bootstrap();

        Yii::$container->set('gromver\models\fields\EditorField', [
            'controller' => 'grom/media/manager'
        ]);
        Yii::$container->set('gromver\models\fields\MediaField', [
            'controller' => 'grom/media/manager'
        ]);
        Yii::$container->set('gromver\modulequery\ModuleQuery', [
            'cache' => $app->cache,
            'cacheDependency' => new ExpressionDependency(['expression' => '\Yii::$app->getModulesHash()'])
        ]);
        Yii::$container->set('gromver\platform\basic\components\MenuMap', [
            'cache' => $app->cache,
            'cacheDependency' => Table::dependency(MenuItem::tableName())
        ]);

        /** @var MenuManager $manager */
        $manager = \Yii::createObject(MenuManager::className());
        $rules = [$manager];
        if (is_array($this->blockModules) && count($this->blockModules)) {
            $rules['grom/<module:(' . implode('|', $this->blockModules). ')><path:(/.*)?>'] = 'grom/default/page-not-found'; //блокируем доступ к контент модулям напрямую
        }

        $app->urlManager->addRules($rules, false); //вставляем в начало списка

        $app->set('menuManager', $manager);

        ModuleQuery::instance()->implement('\gromver\platform\common\interfaces\BootstrapInterface')->invoke('bootstrap', [$app]);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initI18N();

        $params = @include Yii::getAlias($this->paramsPath . '/params.php');

        if (is_array($params)) {
            $this->params = ArrayHelper::merge($params, $this->params);
        }

        // устанавливает мета описание сайта по умолчанию
        $view = Yii::$app->getView();
        $view->title = @$this->params['title'];
        if (!empty($this->params['keywords'])) {
            $view->registerMetaTag(['name' => 'keywords', 'content' => $this->params['keywords']], 'keywords');
        }
        if (!empty($this->params['description'])) {
            $view->registerMetaTag(['name' => 'description', 'content' => $this->params['description']], 'description');
        }
        if (!empty($this->params['robots'])) {
            $view->registerMetaTag(['name' => 'robots', 'content' => $this->params['robots']], 'robots');
        }
    }

    public function initI18N()
    {
        Yii::$app->i18n->translations['gromver.*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@gromver/platform/backend/messages',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getDesktopItem()
    {
        return [
            'label' => Yii::t('gromver.platform', 'System'),
            'links' => [
                ['label' => Yii::t('gromver.platform', 'Desktop'), 'url' => ['/grom/default/index']],
                ['label' => Yii::t('gromver.platform', 'System Configuration'), 'url' => ['/grom/default/params']],
                ['label' => Yii::t('gromver.platform', 'Flush Cache'), 'url' => ['/grom/default/flush-cache']],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMenuItemRoutes()
    {
        return [
            'label' => Yii::t('gromver.platform', 'System'),
            'items' => [
                //['label' => Yii::t('gromver.platform', 'Sitemap'), 'route' => 'grom/default/sitemap'/*, 'icon' => '<i class="glyphicon glyphicon-cog"></i>'*/],
                ['label' => Yii::t('gromver.platform', 'Contact Form'), 'route' => 'grom/default/contact'/*, 'icon' => '<i class="glyphicon glyphicon-cog"></i>'*/]
            ]
        ];
    }

    public function setMode($mode, $saveInSession = true)
    {
        $this->_mode = in_array($mode, self::modes()) ? $mode : self::MODE_VIEW;

        if ($saveInSession) {
            Yii::$app->session->set(self::SESSION_KEY_MODE, $mode);
        }
    }

    public function getMode()
    {
        if(!isset($this->_mode)) {
            $this->setMode(Yii::$app->session->get(self::SESSION_KEY_MODE, self::MODE_VIEW));
        }

        return $this->_mode;
    }

    public function getIsEditMode()
    {
        return $this->getMode() === self::MODE_EDIT;
    }

    public static function modes()
    {
        return [self::MODE_VIEW, self::MODE_EDIT];
    }

    public function getSiteName()
    {
        return !empty($this->params['siteName']) ? $this->params['siteName'] : Yii::$app->name;
    }
}
