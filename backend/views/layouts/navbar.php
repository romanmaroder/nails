<?php

/* @var $countEventTotal string */

use yii\helpers\Html;
use yii\widgets\Menu;

?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
	<!-- Left navbar links -->

    <?= Menu::widget(
        [
            'options' => [
                'class' => 'navbar-nav',
                'data' => 'menu',
            ],
            'labelTemplate' => '{label}',
            'linkTemplate' => '<a class="nav-link" href="{url}">{label}</a>',
            'itemOptions' => ['class' => 'nav-item'],
            'activeCssClass' => 'active',
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => '<i class="fas fa-bars"></i>',
                    'url' => '#',
                    'options' => ['class' => '',
                                  'data-toggle' => 'tooltip',
                                  'data-placement' => 'tooltip',
                                  'title' =>'Боковое меню'
                    ],
                    'template' => '<a href="{url}" class="nav-link" 
                                                    data-widget="pushmenu"
                                                    role="button"
                                                   >{label}
                                    </a>',
                ],
                [
                    'label' => '<i class="far fa-calendar-alt"></i>',
                    'options' => ['class' => 'd-none d-sm-inline-block',
                                  'data-toggle' => 'tooltip',
                                  'data-placement' => 'tooltip',
                                  'title' =>'Календарь'
                    ],
                    'url' => ['/calendar/event/index'],
                ],
                [
                    'label' => '<i class="fas fa-users"></i>',
                    'options' => ['class' => 'd-none d-sm-inline-block',
                                  'data-toggle' => 'tooltip',
                                  'data-placement' => 'tooltip',
                                  'title' =>'Клиенты'
                    ],
                    'url' => ['/client/client/index'],
                ],
                ['label' => '<i class="fas fa-users-cog"></i>',
                 'options' => ['class' => 'd-none d-sm-inline-block',
                               'data-toggle' => 'tooltip',
                               'data-placement' => 'tooltip',
                               'title' =>'Мастера'
                 ],
                 'url' => ['/employees/master/index']],
                [
                    'label' => '<i class="far fa-newspaper"></i>',
                    'options' => ['class' => 'd-none d-sm-inline-block',
                                  'data-toggle' => 'tooltip',
                                  'data-placement' => 'tooltip',
                                  'title' =>'Блог'
                    ],
                    'url' => ['/blog/post/index'],
                ],
                [
                    'label' => '<i class="fa fa-list-ul" aria-hidden="true"></i>',
                    'options' => ['class' => 'd-none d-sm-inline-block',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'tooltip',
                        'title' =>'Заметки'
                    ],
                    'url' => ['/todo/todo/index'],
                ],
                [
                    'label' => 'Меню',
                    'url' => '#',
                    'options' => ['class' => 'nav-item dropdown d-sm-none'],
                    'template' => '<a href="{url}" id="dropdownSubMenu1" 
                                                    data-toggle="dropdown" 
                                                    aria-haspopup="true" 
                                                    aria-expanded="false" 
                                                    class="nav-link dropdown-toggle">{label}
                                    </a>',
                    'items' => [
                        [
                            'label' => 'Календарь',
                            'url' => ['/calendar/event/index'],
                            'options' => ['class' => 'nav-item'],
                        ],
                        [
                            'label' => 'Клиенты',
                            'url' => ['/client/client/index'],
                            'options' => ['class' => 'nav-item'],
                        ],
                        ['label' => 'Мастера',
                         'url' => ['/employees/master/index'],
                         'options' => ['class' => 'nav-item'],
                        ],
                        [
                            'label' => 'Блог',
                            'url' => ['/blog/post/index'],
                            'options' => ['class' => 'nav-item'],
                        ],
                        [
                            'label' => 'Заметки',
                            'url' => ['/todo/todo/index'],
                            'options' => ['class' => 'nav-item'],
                        ],
                        [
                            'label' => 'Выйти',
                            'url' =>  ['/site/logout'],
                            'options' => ['class' => 'nav-item' ,],
                            'template'=>'<a class="nav-link " data-method="post" href="{url}">{label}</a>',
                        ],


                    ],
                    'submenuTemplate' => "\n<ul class='dropdown-menu border-0 shadow' role='menu' aria-labelledby='dropdownSubMenu1'>\n{items}\n</ul>\n",
                ],
            ],

        ]
    );
    ?>

	<!-- SEARCH FORM -->
	<!--<form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>-->

	<!-- Right navbar links -->
	<ul class="navbar-nav ml-auto">
		<!-- Navbar Search -->
		<li class="nav-item">
			<a class="nav-link" data-widget="navbar-search" href="#" role="button">
				<i class="fas fa-search"></i>
			</a>
			<div class="navbar-search-block">
				<form class="form-inline">
					<div class="input-group input-group-sm">
						<input class="form-control form-control-navbar" type="search" placeholder="Search"
							   aria-label="Search">
						<div class="input-group-append">
							<button class="btn btn-navbar" type="submit">
								<i class="fas fa-search"></i>
							</button>
							<button class="btn btn-navbar" type="button" data-widget="navbar-search">
								<i class="fas fa-times"></i>
							</button>
						</div>
					</div>
				</form>
			</div>
		</li>

		<!-- Messages Dropdown Menu -->
		<li class="nav-item dropdown d-none">
			<a class="nav-link" data-toggle="dropdown" href="#">
				<i class="far fa-comments"></i>
				<span class="badge badge-danger navbar-badge">3</span>
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
				<a href="#" class="dropdown-item">
					<!-- Message Start -->
					<div class="media">
                        <?php
                        echo Html::img(
                            '/img/user1-128x128.jpg',
                            ['class' => 'img-size-50 mr-3 img-circle', 'alt' => 'User Avatar']
                        ); ?>
						<div class="media-body">
							<h3 class="dropdown-item-title">
								Brad Diesel
								<span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
							</h3>
							<p class="text-sm">Call me whenever you can...</p>
							<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
						</div>
					</div>
					<!-- Message End -->
				</a>
				<div class="dropdown-divider"></div>
				<a href="#" class="dropdown-item">
					<!-- Message Start -->
					<div class="media">
                        <?php
                        echo Html::img(
                            '/img/user8-128x128.jpg',
                            ['class' => 'img-size-50 img-circle mr-3', 'alt' => 'User Avatar']
                        ); ?>
						<div class="media-body">
							<h3 class="dropdown-item-title">
								John Pierce
								<span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
							</h3>
							<p class="text-sm">I got your message bro</p>
							<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
						</div>
					</div>
					<!-- Message End -->
				</a>
				<div class="dropdown-divider"></div>
				<a href="#" class="dropdown-item">
					<!-- Message Start -->
					<div class="media">
                        <?php
                        echo Html::img(
                            '/img/user3-128x128.jpg',
                            ['class' => 'img-size-50 img-circle mr-3', 'alt' => 'User Avatar']
                        ); ?>
						<div class="media-body">
							<h3 class="dropdown-item-title">
								Nora Silvester
								<span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
							</h3>
							<p class="text-sm">The subject goes here</p>
							<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
						</div>
					</div>
					<!-- Message End -->
				</a>
				<div class="dropdown-divider"></div>
				<a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
			</div>
		</li>
		<!-- Notifications Dropdown Menu -->
		<li class="nav-item dropdown d-none">
			<a class="nav-link" data-toggle="dropdown" href="#">
				<i class="far fa-bell"></i>
				<span class="badge badge-warning navbar-badge">15</span>
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
				<span class="dropdown-header">15 Notifications</span>
				<div class="dropdown-divider"></div>
				<a href="#" class="dropdown-item">
					<i class="fas fa-envelope mr-2"></i> 4 new messages
					<span class="float-right text-muted text-sm">3 mins</span>
				</a>
				<div class="dropdown-divider"></div>
				<a href="#" class="dropdown-item">
					<i class="fas fa-users mr-2"></i> 8 friend requests
					<span class="float-right text-muted text-sm">12 hours</span>
				</a>
				<div class="dropdown-divider"></div>
				<a href="/admin/event/index" class="dropdown-item">
					<i class="fas fa-file mr-2"></i>Всего записей <?php
                    echo $countEventTotal; ?>
					<span class="float-right text-muted text-sm">-</span>
				</a>
				<div class="dropdown-divider"></div>
				<a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
			</div>
		</li>
		<li class="nav-item d-none d-sm-block">
            <?= Html::a(
                '<i class="fas fa-sign-out-alt"></i>',
                ['/site/logout'],
                ['data-method' => 'post', 'class' => 'nav-link']
            ) ?>
		</li>
		<!--<li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>-->
	</ul>
</nav>
<!-- /.navbar -->