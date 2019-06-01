<?php/** * Created by PhpStorm. * User: Arty * Date: 26.07.2017 * Time: 16:30 *//** * */class HPhotos extends CWidget {    /**     * @var ArDirHotels     */    public $hotel;    /**     * @var int     */    public $price;    /**     * @var string     */    public $currency;    /**     * Init     */    public function init(){        parent::init();    }    /**     * Run     */    public function run(){        $this->widget(            'application.extensions.fancybox.EFancyBox', [                'target'=>'.fancy-inline',                'config'=> [                    'closeBtn' => true,                    'onComplete' =>  new CJavaScriptExpression(                        'function(links, index, target){                            $("a.thumbnail img").attr("src", target.href);                            $("a.thumbnail").attr("img_id", $(links[index]).attr("id"));                        }'                    )                ]            ]        );        $images = $this->hotel->images();        $has_real_images = file_exists($this->hotel->image(0, false));        ?><div class="row" id="<?=$this->getId()?>">            <div class="col-md-12 col-sm-12 col-xs-12">                <a href="javascript://" img_id="a_id_0" class="thumbnail">                    <? if( $this->price && $this->currency) {?>                        <div class="tour-labels-container">                            <div class="tour-price t-price-label"><?=TSearch\TourHelper::normalizePrice($this->price)?>&nbsp;<?=TSearch\TourHelper::htmlCurrency($this->currency)?></div>                        </div>                    <? } ?>                    <img src="<?=$has_real_images ? $images[0] : ArHotelPhotos::defaultPhotoUrl();?>" alt="" style="height: 240px;">                </a>            </div>            <? if($has_real_images) { ?>                <div class="col-md-10 col-sm-10 col-xs-10 <?=count($images) > 7 ? 'col-md-offset-1 col-sm-offset-1 col-xs-offset-1' : ''?> t-slick" style="display: none;">                    <? foreach( $images as $i => $image ){                        ?><a href="<?=$image?>" id="<?='a_id_' . $i?>" class="fancy-inline" rel="group"><img src="<?=$image?>" alt="" class="img-thumbnail hotel-img"></a><?                    }?>                </div>            <? } else {?>                <div class="col-md-12 col-sm-12 col-xs-12">                    <p class="help-block text-center">Фотографии отеля временно недоступны</p>                </div>            <? } ?>        </div><?        Yii::app()->clientScript->registerScript(            "hphotos",            ';(function($, undefined){                var $w = $("div[id=\'' . $this->getId() . '\']");                $(function(){                    $(".t-slick", $w).slick({                        dots: true,                        draggable: false,                        slidesToShow: 7,                        slidesToScroll: 7                    }).show();                            $("a.thumbnail", $w).click(function(){                        $("#" + $(this).attr("img_id"), $w).trigger("click");                        return false;                    });                });                    })(jQuery);',            CClientScript::POS_READY        );    }}