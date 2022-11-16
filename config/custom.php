<?php

return [
    'custom' => [
        'mainLayoutType' => 'vertical', // Options[String]: vertical(default), horizontal
        'theme' => 'light', // options[String]: 'light'(default), 'dark', 'bordered', 'semi-dark'
        'sidebarCollapsed' => false, // options[Boolean]: true, false(default) (warning:this option only applies to the vertical theme.)
        'navbarColor' => '', // options[String]: bg-primary, bg-info, bg-warning, bg-success, bg-danger, bg-dark (default: '' for #fff)
        'horizontalMenuType' => 'floating', // options[String]: floating(default) / static /sticky (Warning:this option only applies to the Horizontal theme.)
        'verticalMenuNavbarType' => 'sticky', // options[String]: floating(default) / static / sticky / hidden (Warning:this option only applies to the vertical theme)
        'footerType' => 'sticky', // options[String]: static(default) / sticky / hidden
        'layoutWidth' => 'boxed', // options[String]: boxed(default) / full,
        'showMenu' => true, // options[Boolean]: true(default), false //show / hide main menu (Warning: if set to false it will hide the main menu)
        'bodyClass' => '', // add custom class
        'pageHeader' => true, // options[Boolean]: true(default), false (Page Header for Breadcrumbs)
        'contentLayout' => 'default', // options[String]: default, content-left-sidebar, content-right-sidebar, content-detached-left-sidebar, content-detached-right-sidebar (warning:use this option if your whole project with sidenav Otherwise override this option as page level )
        'defaultLanguage' => 'id',    //en(default)/de/pt/fr here are four optional language provided in theme
        'blankPage' => false, // options[Boolean]: true, false(default) (warning:only make true if your whole project without navabr and sidebar otherwise override option page wise)
        'direction' => env('MIX_CONTENT_DIRECTION', 'ltr'), // Options[String]: ltr(default), rtl
    ],
];

/* Do changes in this file if you know what it effects to your template. For more infomation refer the <a href="https://pixinvent.com/demo/vuexy-bootstrap-laravel-admin-template//documentation/documentation-laravel.html"> documentation </a> */
