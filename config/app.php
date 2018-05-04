<?php

use App\Framework\Middleware\AttachMiddleware;
use Framework\Cookie\CookieInterface;
use Framework\Cookie\PHPCookie;
use Framework\Middleware\CsrfMiddleware;
use Framework\Middleware\DispatcherMiddleware;
use Framework\Middleware\LoggedInMiddleware;
use Framework\Middleware\MethodMiddleware;
use Framework\Middleware\NotFoundMiddleware;
use Framework\Middleware\RouterMiddleware;
use Framework\Middleware\TrailingSlashMiddleware;
use Framework\Router;
use Framework\Router\RouterInterface;
use Framework\Session\ErrorsManager;
use Framework\Session\PHPSession;
use Framework\Session\SessionInterface;
use Framework\Twig\ActiveExtension;
use Framework\Twig\CsrfExtension;
use Framework\Twig\CssExtension;
use Framework\Twig\FlashExtension;
use Framework\Twig\FormExtension;
use Framework\Twig\JsExtension;
use Framework\Twig\PagerFantaExtension;
use Framework\Twig\RouterTwigExtension;
use Framework\Twig\TextExtension;
use Framework\Twig\TimeExtension;
use Middlewares\Whoops;

return [
    /**
     * Middleware Définition for Application
     */
    "middlewares" => [
        Whoops::class,
        TrailingSlashMiddleware::class,
        MethodMiddleware::class,
        CsrfMiddleware::class,
        RouterMiddleware::class,
        LoggedInMiddleware::class,
        AttachMiddleware::class,
        DispatcherMiddleware::class,
        NotFoundMiddleware::class
    ],

    /**
     * Database définition
     */
    "database" => [
        'database.host' => 'localhost',
        'database.username' => 'homestead',
        'database.password' => 'secret',
        'database.name' => 'homestead'
    ],

    /**
     * Mail Configuration
     */
    "mailer" => [
        'mail.host' => "localhost",
        'mail.port' => 1025,
        'mail.username' => null,
        'mail.password' => null,
        'mail.from' => ["doriangrelu@gmail.com" => "Dorian GRELU"]
    ],

    /**
     * TWIG Moduls définition
     */
    'twig.extensions' => [
        \DI\get(RouterTwigExtension::class),
        \DI\get(PagerFantaExtension::class),
        \DI\get(TextExtension::class),
        \DI\get(TimeExtension::class),
        \DI\get(FlashExtension::class),
        \DI\get(FormExtension::class),
        \DI\get(CsrfExtension::class),
        \DI\get(ActiveExtension::class),
        \DI\get(CssExtension::class),
        \DI\get(JsExtension::class)
    ],

    /**
     * Dependencies injection definitions
     */
    'container'=>[
        CookieInterface::class=> \DI\object(PHPCookie::class),
        SessionInterface::class => \DI\object(PHPSession::class),
        CsrfMiddleware::class => \DI\object()->constructor(\DI\get(SessionInterface::class), \DI\get(CookieInterface::class)),
        RouterInterface::class => \DI\object(Router::class),
        ErrorsManager::class=>\DI\object(ErrorsManager::class),
        \PDO::class => function (\Psr\Container\ContainerInterface $c) {
            return new PDO(
                'mysql:host=' . $c->get('database.host') . ';dbname=' . $c->get('database.name'),
                $c->get('database.username'),
                $c->get('database.password'),
                [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            );
        },
        FluentPDO::class => DI\object()->constructor(DI\get(\PDO::class)),

    ]

];