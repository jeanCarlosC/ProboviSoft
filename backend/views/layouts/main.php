<?php

/* @var $this \yii\web\View */
/* @var $content string */
use kartik\growl\Growl;

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Provobisoft',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar',
        ],
    ]);
  
    $menuItems = [

        [
            'label' => '<i class="glyphicon glyphicon-list-alt"></i> Información',
            'items' => [
            [
            'label' => 'Tablas Informativas', 'url' =>Yii::$app->homeUrl],  
            ],
            'visible' => $this->context->route == 'site/index',
        ],

/*        [
            'label' => 'Producción',
            'items' => [
            ['label' => 'Producción-Lactancias', 'url' => Url::toRoute('/produccion/')],

            ],

            'visible' => ($this->context->route != "site/index" && $this->context->route != "site/login"),
        ],*/
        
        [

            'label' => 'Reportes',
            'items' => [
            '<li class="dropdown-header"><b>Producción</b></li>',
            ['label' => 'Productivo', 'url' => Url::toRoute('/produccion/')],
/*            ['label' => 'vacas proximas a parto', 'url' => Url::toRoute('#')],
            ['label' => 'Vacas proximas a servicio', 'url' => Url::toRoute('#')],*/
            '<li class="divider"></li>',
            '<li class="dropdown-header"><b>Reproducción</b></li>',
            ['label' => 'Reproductivo', 'url' => Url::toRoute('#')],
            ['label' => 'vacas proximas a parto', 'url' => Url::toRoute('#')],
            ['label' => 'Vacas proximas a servicio', 'url' => Url::toRoute('#')],

            ],
            'visible' => $this->context->route != "site/login",
        ],
        

        [
            'label' => 'Modulos',
            'items' => [
                /*'<li class="divider"></li>',*/
                '<li class="dropdown-header"><b>Registro</b></li>',
                ['label' => 'Animales', 'url' => Url::toRoute('/animal/')],
                ['label' => 'Semen', 'url' => Url::toRoute('/semen/')],
                ['label' => 'Venta/Muerte', 'url' => Url::toRoute('/status-eliminacion/')],
                '<li class="divider"></li>',
                '<li class="dropdown-header"><b>Eventos</b></li>',
                ['label' => 'Servicios', 'url' =>Url::toRoute('/servicio/')],
                ['label' => 'Diagnosticos', 'url' => Url::toRoute('/diagnostico/')],
                ['label' => 'Partos', 'url' => Url::toRoute('/parto/')],
                ['label' => 'Abortos', 'url' => Url::toRoute('/aborto/')],
                ['label' => 'Pesajes de leche', 'url' => Url::toRoute('/ordeno/')],
                ['label' => 'Secados', 'url' => Url::toRoute('/secado/')],
                ['label' => 'Pesos Corporales', 'url' => Url::toRoute('/peso/')],        

        ],
        
        'visible' => ($this->context->route != "site/index" && $this->context->route != "site/login"),
        ],

        [
            'label' => '<i class="glyphicon glyphicon-home"></i> Inicio', 
            'url' => Yii::$app->homeUrl,
            'visible' => ($this->context->route != "site/index" && $this->context->route != "site/login"),
        ],
    ];
    
    if (Yii::$app->user->isGuest) {

        $menuItems = [

                        [
                        'label' => 'Iniciar Sesion',
                         'url' =>  Url::toRoute('site/login/')
                        ],

                        [
                        'label' => 'Registrate',
                         'url' =>  Url::toRoute('site/signup/')
                        ],
        ];

    } else {
        $menuItems[] = 
        [
            'label' => 'Cerrar Sesion(' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);
    NavBar::end();
    ?>

    <div class="container">

        <?= Alert::widget() ?>
        <!-- ***************************** mensajeeeess ********************************** -->


 <?php foreach (Yii::$app->session->getAllFlashes() as $message): ; ?>
            <?php
            echo Growl::widget([
                'type' => (!empty ($message['type'])) ? $message['type']
                    : 'danger',
                'title' => (!empty ($message['title'])) ? Html::encode($message['title'])
                    : 'Title Not Set!',
                'icon' => (!empty ($message['icon'])) ? $message['icon']
                    : 'fa fa-info',
                'body' => (!empty ($message['message'])) ? Html::encode($message['message'])
                    : 'Message Not Set!',
                'showSeparator' => true,
                'delay' => 1, //This delay is how long before the message shows
                'pluginOptions' => [
                    'showProgressbar' => true,
                    'delay' => (!empty ($message['duration'])) ? $message['duration']
                        : 3000, //This delay is how long the message shows for
                    'placement' => [
                        'from' => (!empty ($message['positonY'])) ? $message['positonY']
                            : 'top',
                        'align' => (!empty ($message['positonX'])) ? $message['positonX']
                            : 'right',
                    ]
                ]
            ]);
            ?>
        <?php endforeach; ?>

        <!-- ***************************** Fin mensajes ********************************** -->
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Provobisoft <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
