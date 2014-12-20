<?php
/**
 * @link https://github.com/gromver/yii2-platform-basic.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-platform-basic/blob/master/LICENSE
 * @package yii2-platform-basic
 * @version 1.0.0
 */

namespace gromver\platform\basic\interfaces;

/**
 * Interface MenuRouterInterface
 * Используется модулями, для предоставления своего роутера пунктов меню
 * @package yii2-platform-basic
 * @author Gayazov Roman <gromver5@gmail.com>
 */
interface MenuRouterInterface {
    /**
     * @return string | \gromver\platform\basic\components\MenuRouter
     */
    public function getMenuRouter();
} 