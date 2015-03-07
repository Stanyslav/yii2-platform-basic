<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 07.03.15
 * Time: 12:08
 */

namespace gromver\platform\basic\widgets;


use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\Html;

class HtmlEditor extends WidgetPersonal {
    const EDITOR_CKEDITOR_BASIC = 'ckeditor_basic';
    const EDITOR_CKEDITOR_STANDARD = 'ckeditor_standard';
    const EDITOR_CKEDITOR_FULL = 'ckeditor_full';
    const EDITOR_TEXTAREA = 'textarea';
    /**
     * @var Model the data model that this widget is associated with.
     * @ignore
     */
    public $model;
    /**
     * @var string the model attribute that this widget is associated with.
     * @ignore
     */
    public $attribute;
    /**
     * @var string the input name. This must be set if [[model]] and [[attribute]] are not set.
     * @ignore
     */
    public $name;
    /**
     * @var string the input value.
     * @ignore
     */
    public $value;
    /**
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     * @ignore
     */
    public $options = [];
    /**
     * @var string
     * @type list
     * @items editorLabels
     */
    public $editor = self::EDITOR_CKEDITOR_STANDARD;


    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        if ($this->name === null && !$this->hasModel()) {
            throw new InvalidConfigException("Either 'name', or 'model' and 'attribute' properties must be specified.");
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }
        parent::init();
    }

    /**
     * @return boolean whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return $this->model instanceof Model && $this->attribute !== null;
    }

    protected function launch()
    {
        echo 'HtmlEditorWidget coming soon!';
        switch ($this->editor) {
            case self::EDITOR_CKEDITOR_BASIC:
                echo \mihaildev\ckeditor\CKEditor::widget([
                    'model' => $this->model,
                    'attribute' => $this->attribute,
                    'name' => $this->name,
                    'value' => $this->value,
                    'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions('grom/media/manager', [
                        'filebrowserBrowseUrl' => ['/grom/menu/backend/item/ckeditor-select'],
                        'extraPlugins' => 'codesnippet',
                        'preset' => 'basic',
                        'tabSpaces' => 4
                    ])
                ]);
                break;
            case self::EDITOR_CKEDITOR_STANDARD:
                echo \mihaildev\ckeditor\CKEditor::widget([
                    'model' => $this->model,
                    'attribute' => $this->attribute,
                    'name' => $this->name,
                    'value' => $this->value,
                    'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions('grom/media/manager', [
                        'filebrowserBrowseUrl' => ['/grom/menu/backend/item/ckeditor-select'],
                        'extraPlugins' => 'codesnippet',
                        'preset' => 'standard',
                        'tabSpaces' => 4
                    ])
                ]);
                break;
            case self::EDITOR_CKEDITOR_FULL:
                echo \mihaildev\ckeditor\CKEditor::widget([
                    'model' => $this->model,
                    'attribute' => $this->attribute,
                    'name' => $this->name,
                    'value' => $this->value,
                    'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions('grom/media/manager', [
                        'filebrowserBrowseUrl' => ['/grom/menu/backend/item/ckeditor-select'],
                        'extraPlugins' => 'codesnippet',
                        'preset' => 'full',
                        'tabSpaces' => 4
                    ])
                ]);
                break;
            default:
                echo $this->hasModel() ? Html::activeTextarea($this->model, $this->attribute) : Html::textarea($this->name, $this->value);
        }
    }

    public static function editorLabels()
    {
        return [
            self::EDITOR_CKEDITOR_BASIC => 'ElFinder basic',
            self::EDITOR_CKEDITOR_STANDARD => 'ElFinder standard',
            self::EDITOR_CKEDITOR_FULL => 'ElFinder full',
            self::EDITOR_TEXTAREA => 'Textarea',
        ];
    }
} 