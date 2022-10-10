<?php

namespace davidxu\sweetalert2;

use davidxu\sweetalert2\assets\SweetConfirmAsset;
use Yii;
use yii\bootstrap4\Widget;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use davidxu\sweetalert2\assets\SweetAlert2Asset;
use yii\web\JsExpression;

/**
 * SweetAlter2 widget renders a message from session flash.
 * If you are using session and config this as a toast, You can configure your main layout [views/layout/main.php] file as
 *
 * ```php
 * <section class="content">
 * <?php echo davidxu\sweetalter2\SweetAlter2::widget([
 *      // options => [
 *          // 'position' => 'top-end',
 *          // 'timerProgressBar' => true,
 *          // 'timer' = 2000,
 *      // ],
 * ]); ?>
 * <?= $content ?>
 * </section>
 * ```
 * Then in Controller/Action function, configure as the following [Example for actionUpdate]
 *  ```php
 * if ($model->load(Yii::$app->request->post()) && $model->save()) {
 *     Yii::$app->session->setFlash('success', 'Saved successfully');
 *     return $this->redirect(['index']);
 * }
 *  ```
 *
 * @see https://sweetalert2.github.io/
 * @package davidxu\sweetalert2
 * @property array $options Custom toast options
 */
class SweetAlert2 extends Widget
{
    const TYPE_INFO = 'info';
    const TYPE_ERROR = 'error';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_QUESTION = 'question';

    const INPUT_TYPE_TEXT = 'text';
    const INPUT_TYPE_EMAIL = 'email';
    const INPUT_TYPE_URL = 'url';
    const INPUT_TYPE_PASSWORD = 'password';
    const INPUT_TYPE_TEXTAREA = 'textarea';
    const INPUT_TYPE_SELECT = 'select';
    const INPUT_TYPE_RADIO = 'radio';
    const INPUT_TYPE_CHECKBOX = 'checkbox';
    const INPUT_TYPE_FILE = 'file';
    const INPUT_TYPE_RANGE = 'range';
    const INPUT_TYPE_NUMBER = 'number';
    const INPUT_TYPE_TEL = 'tel';

    /**
     * @var bool If use session flash, default to true
     */
    public $useSessionFlash = true;
    /**
     * @var string alert callback
     */
    public $callback = 'function() {}';

    /**
     * Common configuration.
     * $toast bool Whether or not an alert should be treated as a toast notification, fefault to true
     * $position string The toast position, default to 'top-end'
     * $timerProgressBar bool Whether timer progres bar shows, default to true
     * $timer int|null Auto close timer of the popup. Set in ms (milliseconds)
     * @var array $options Custom configuration
     */
    public $options = [
        'toast' => false,
        'position' => 'center',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->useSessionFlash) {
            $this->options = [
                'toast' => true,
                'position' => 'top-end',
                'timerProgressBar' => true,
                'timer' => 2000,
            ];
        }
        parent::init();
    }

    /**
     * @param array $options
     * @param array $mixin
     */
    public function initSwal(array $options, array $mixin)
    {
        $view = $this->getView();
        SweetAlert2Asset::register($view);
        SweetConfirmAsset::register($view);
        $options = ArrayHelper::merge($options, $mixin);
        $sweetAlert = new JsExpression('Swal.fire(' . Json::encode($options) . ')');
        $view->registerJs($sweetAlert, $view::POS_END);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (isset($this->options['id'])) {
            unset($this->options['id']);
        }

        if ($this->useSessionFlash) {
            $this->processFlashWidget($this->processFlashSession(Yii::$app->session));
        } else {
            $this->initSwal($this->getOptions());
        }
    }

    /**
     * @param $session bool|mixed|\yii\web\Session
     * @return array|bool
     */
    private function processFlashSession($session)
    {
        $flash = $session->getAllFlashes();
        if ($flash) {
            $type = $this->getType(array_keys($flash)[0]);
            $message = $flash[$type];
            $session->removeAllFlashes();
            return $message ? array_merge([
                'icon' => $type,
                'title' => $message,
            ], $this->options) : false;
        }
        return false;
    }

    /**
     * @param array $options
     */
    private function processFlashWidget($options)
    {
        if ($options) {
            $this->initSwal($this->getFlashMixin(), $options);
        }
    }

    /**
     * Get widget options
     *
     * @return string
     */
    public function getOptions()
    {
        if ($this->useSessionFlash) {
            $this->options = [
                'toast' => true,
                'position' => 'top-end',
                'timerProgressBar' => true,
                'timer' => 2000,
            ];
        }
        return $this->options;
    }

    /**
     * @param string $type
     * @return false|string
     */
    private function getType(string $type) {
        $typeArray = [
            self::TYPE_ERROR,
            self::TYPE_SUCCESS,
            self::TYPE_INFO,
            self::TYPE_QUESTION,
            self::TYPE_WARNING
        ];
        return in_array($type, $typeArray) ? $type : false;
    }

    private function getFlashMixin()
    {
        $mixin = [
            'showConfirmButton' => false,
            'didOpen' => '(toast) => {toast.addEventListener("mouseenter", Swal.stopTimer);toast.addEventListener("mouseleave", Swal.resumeTimer);}',
        ];
        return ArrayHelper::merge($this->options, $mixin);
    }
}
