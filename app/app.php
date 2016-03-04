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
            'stores' => Store::getAll(),
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
                'title' => 'Add Stores!',
                'text' => 'Use the form below to add a store to our database! Or, click back to go back!',
                'link1' => array(
                    'link' => '/stores',
                    'text' => 'Back'
                )
            ),
            'form' => array(
                'action' => '/stores/addStore'
            )
        ));
    });

    $app->post("/stores/addStore", function() use ($app) {
        if (!Store::findByName($_POST['name'])) {
          $new_store = new Store($_POST['name']);
          $new_store->save();
          $message_text = $_POST['name'] . ' was added to our database! Use the form below to add another store, or click back to go back!';
        } else {
          $message_text = $_POST['name'] . ' already exists in out database! Try creating a store with a different name!';
        }

        return $app['twig']->render('stores.html.twig', array(
            'navbar' => true,
            'message' => array(
                'title' => 'Stores!',
                'text' => $message_text,
                'link1' => array(
                    'link' => '/stores',
                    'text' => 'Back'
                )
            ),
            'form' => true
        ));
    });

    $app->get("/brands", function() use ($app) {
      return $app['twig']->render('brands.html.twig', array(
        'navbar' => true,
        'brands' => Brand::getAll(),
        'message' => array(
          'title' => 'Brands!',
          'text' => 'Below is a list of brands in our database. Feel free to add a brand. Click on a brand to add stores to that brand.',
          'link1' => array(
            'link' => '/brands/addBrandForm',
            'text' => 'Add a Brand'
          )
        )
      ));
    });

    $app->get("/brands/addBrandForm", function() use ($app) {
      return $app['twig']->render('brands.html.twig', array(
        'navbar' => true,
        'message' => array(
          'title' => 'Add Brands!',
          'text' => 'Use the form below to add a brand to our database! Or, click back to go back!',
          'link1' => array(
            'link' => '/brands',
            'text' => 'Back'
          )
        ),
        'form' => array(
            'action' => '/brands/addBrand'
        )
      ));
    });

    $app->post("/brands/addBrand", function() use ($app) {
      if (!Brand::findByName($_POST['name'])) {
        $new_brand = new Brand($_POST['name']);
        $new_brand->save();
        $message_text = $_POST['name'] . ' was added to our database! Use the form below to add another brand, or click back to go back!';
      } else {
        $message_text = $_POST['name'] . ' already exists in out database! Try creating a brand with a different name!';
      }

      return $app['twig']->render('brands.html.twig', array(
        'navbar' => true,
        'message' => array(
          'title' => 'Brands!',
          'text' => $message_text,
          'link1' => array(
            'link' => '/brands',
            'text' => 'Back'
          )
        ),
        'form' => true
      ));
    });

    return $app;
?>
