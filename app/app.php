<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Store.php";
    require_once __DIR__."/../src/Brand.php";

    $app = new Silex\Application();
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

    // setup server for database
    $server = 'mysql:host=localhost:8889;dbname=shoes';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    // allow patch and delete request to be handled by browser
    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array(
            'navbar' => true,
            'message' => array(
                'title' => 'Welcome to the Shoe Finder!',
                'text' => 'This webapp lets you find shoe brands by store, or stores by shoe brands. Follow the links in the navbar above or beneath this message to get started',
                'link1' => array(
                    'link' => '/stores',
                    'text' => 'Stores'
                ),
                'link2' => array(
                    'link' => '/brands',
                    'text' => 'Brands'
                )
            )
        ));
    });

    $app->get("/stores", function() use ($app) {
        return $app['twig']->render('stores.html.twig', array(
            'navbar' => true,
            'message' => array(
                'title' => 'Stores!',
                'text' => 'Below is a list of stores in our database. Feel free to add a store. Click on a store to see its brands and add brands to that store.',
                'link1' => array(
                    'link' => '/stores/addStoreForm',
                    'text' => 'Add a Store'
                )
            )
        ));
    });

    $app->get("/stores/addStoreForm", function() use ($app) {
        return $app['twig']->render('stores.html.twig', array(
            'navbar' => true,
            'message' => array(
                'title' => 'Stores!',
                'text' => 'Use the form below to add a store to our database!',
                'link1' => array(
                    'link' => '/stores',
                    'text' => 'Back'
                )
            )
        ));
    });

    return $app;
?>
