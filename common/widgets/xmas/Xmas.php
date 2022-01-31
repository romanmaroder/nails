<?php


namespace common\widgets\xmas;


use common\widgets\xmas\assets\XmasAsset;
use yii\base\Widget;

/**
 * The Xmas widget renders the picture with the given parameters as an array ['width'=>' ','height'=>' ','src'=>' '].
 *
 *
 * All parameters are optional.
 *
 *
 * ```php
 *
 * Xmas::widget([])
 *
 * ```
 */
class Xmas extends Widget
{

    /**
     * @var null|string
     * Picture width
     * If the parameter is not specified, the width is set to 100%.
     */
    public ?string $width = null;
    /**
     * @var null|string
     * Picture height
     * If the parameter is not specified, the height is set to 100%.
     */
    public ?string  $height = null;

    /**
     * @var null|string
     * The path to the picture. If the parameter is not specified, the default picture is set
     */
    public ?string  $src = null;

    /**
     * @var string
     * Default picture
     */
    public const DEFAULT_SRC = '/img/widget.jpg';

    public function init()
    {

        if ($this->width === null) {
            $this->width = '100%';
        }
        if ($this->height === null) {
            $this->height = 'auto';
        }
        if ($this->src === null){
            $this->src = self::DEFAULT_SRC;
        }
        parent::init();
    }
    public function run(): string
    {
        XmasAsset::register($this->view);
        return $this->render('xmas',['width'=>$this->width,'height'=>$this->height,'src'=> $this->src]);
    }
}