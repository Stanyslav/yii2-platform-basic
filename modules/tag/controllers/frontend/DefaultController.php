<?php
/**
 * @link https://github.com/gromver/yii2-platform-basic.git#readme
 * @copyright Copyright (c) Gayazov Roman, 2014
 * @license https://github.com/gromver/yii2-platform-basic/blob/master/LICENSE
 * @package yii2-platform-basic
 * @version 1.0.0
 */

namespace gromver\platform\basic\modules\tag\controllers\frontend;


use gromver\platform\basic\modules\tag\models\Tag;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * Class DefaultController
 * @package yii2-platform-basic
 * @author Gayazov Roman <gromver5@gmail.com>
 */
class DefaultController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->loadModel($id)
        ]);
    }

    public function loadModel($id)
    {
        if(!($model = Tag::findOne($id))) {
            throw new NotFoundHttpException(Yii::t('gromver.platform', 'The requested tag does not exist.'));
        }

        return $model;
    }
}
