<?php

/**
 * Home.index view
 */

?>
<div id="header">
    <video autoplay loop poster="images/easykit.png" id="bgvid">
        <source src="<?= HTML::link('default/videos/easykit.mp4') ?>" type="video/mp4">
    </video>

    <div id="filtre"></div>

    <div id="bar-menu">
        <div id="logo">
            <a href="<?= HTML::link('/') ?>"><img src="<?= HTML::link('default/images/logo.png'); ?>" alt=""></a>
        </div>

        <ul id="buttons">
            <!--<li id="item1"><a href="#">Create your pack</a></li>-->

            <li id="item2"><a href="#infographie" data-slide="">How it works</a></li>
            <?php if (isset($_SESSION['user'])) : ?>
                <li id="item3" class="item3"><a href="#">My account</a></li>
                <li class="logout"><a href="<?= HTML::link('users/logout') ?>"><img src="<?= HTML::link('default/images/logout.png'); ?>"></a></li>

            <?php else : ?>
                <li id="item3" class="item3"><a href="#">Log in</a></li>
                <li id="item4"><a href="<?= HTML::link('users/register') ?>">Register</a></li>
            <?php endif; ?>
        </ul>

        <?php if (!isset($_SESSION['user'])) : ?>
            <div id="login-popup">
                <form class="sign-up login" style="border:solid 1px white;">
                    <a href="#" id="close"><i class="fa fa-times"></i></a>
                    <input type="email" id="emailLogin" class="sign-up-input" placeholder="What's your mail?" >
                    <input type="password" id="passwordLogin" class="sign-up-input" placeholder="Password">
                    <input type="checkbox"> Remember me <br/> <a href="#" class="forgot_pass">Forgot password?</a> <br/>
                    <input type="submit" id="submitLogin" value="Log in!" class="sign-up-button">
                    <ul class="social_button">
                        <li class="button fb"><a href="<?= FbHelper::getFbLink() ?>">With Facebook</a></li>
                    </ul>
                    <a href="">Forget password?</a>
                </form>
            </div>
        <?php endif; ?>

        <div id="menu-mobile">
            <a href="#" id="open_menu_mobile"><img src="<?= HTML::link('default/images/menu.png'); ?>" alt=""></a>
        </div>

        <div id="menu_open">
            <ul>
                <li class="menu_deroulant"><a href="#infographie" data-slide="">How it works</a></li>

                <li class="menu_deroulant"><a href="#" class="item3">Login</a></li>

                <li class="menu_deroulant"><a href="<?= HTML::link('users/register'); ?>">Register</a></li>
            </ul>
        </div>
    </div>

    <div id="search-area">
        <h1>Less planning, more sharing</h1>

        <h3>Plan, share and collect money for all your events.</h3><a id="cta" href="<?= HTML::link('packs/create') ?>" onclick="ga('send','event', 'Lien', 'Clic', 'Button Create Pack');">Create your pack</a> <!--<div id="bar-search">
        <form id="formu" method="post" action="traitement.php">
        <input type="search" id="search" placeholder="Search an event to start your pack...">
        </form>
        <a id="add" href="#">Or add an event</a>
    </div>-->
    </div>
</div>

<div id="containt" ng-controller="popularEvents">
    <h2>Some Events</h2>

    <div class="trait"></div>
    <span us-spinner="{radius:30, width:8, length: 16}"></span>

    <div class="spinner">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>

    <a class="block" ng-repeat="event in data.events track by $index" id="{{event.events_id}}" href="<?= HTML::link('/events/show/{{event.events_id}}')?>">
        <div class="couverture">
            <ul>
                <li ng-repeat="photos in event.events_medias|limitTo:1" style="background: url({{photos.medias_file}})"></li>
            </ul>

            <div class="description">
                {{event.events_summary}}<span class="info">{{event.events_address}}</span>
            </div>
        </div>

        <div class="block-text">
            <p class="titre">{{event.events_name}}</p>

            <div class="trait-block-text"></div>

            <ul class="icones" data-id="{{event.events_id}}">
                <li ng-controller="likes">
                    <img src="<?= HTML::link('default/images/like.png'); ?>" data-id="{{event.events_id}}" ng-click="like($event)" class="like off" title="Like this event" width="21" alt="like">
                    <img src="<?= HTML::link('default/images/like_on.png'); ?>" data-id="{{event.events_id}}" ng-click="unLike($event)" class="like on" title="Like this event" width="21" alt="like">

                    <div class="spinner-like"></div>
                    <span class="like_number">{{event.events_like}}</span>
                </li>

                <li><img src="<?= HTML::link('default/images/share.png'); ?>" title="Share this event" width="21" alt="share"></li>
            </ul>
        </div>
    </a>

    <div id="all-events">
        <a href="<?= HTML::link('events') ?>">See all events</a>
    </div>
</div>

<div id="infographie">
    <h2>How it works</h2>

    <div class="trait"></div>

    <video loop preload controls id="video_how" poster="<?= HTML::link('default/images/easykit_bg.png') ?>">
        <source src="<?= HTML::link('default/videos/easykit_howitworks.mov') ?>" >
    </video>
</div>

<div id="joinus">
    <h2>Join us</h2>

    <div class="trait"></div>

    <div id="socials">
        <ul class="social">
            <li><a href="http://www.facebook.com/easykit.project" target="_blank"></a></li>

            <li><a href="http://twitter.com/easy_kit" target="_blank"></a></li>

            <li><a href="http://plus.google.com/u/0/b/100863695395542898157/100863695395542898157/" target="_blank"></a></li>
        </ul>
    </div>

    <div id="mc_embed_signup">
        <form action="//ovh.us9.list-manage.com/subscribe/post?u=ec0be1ea7c0c896f8c94ca0ab&amp;id=e2c3d70672" method="post" id="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="">
            <div id="mc_embed_signup_scroll">
                <label for="mce-EMAIL">Keep posted about the last event, receive our selection every week !</label> <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required="">

                <div class="clear">
                    <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
                </div>
            </div>
        </form>
    </div>
</div>




