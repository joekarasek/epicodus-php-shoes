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
          $message_text = $_POST['name'] . ' already exists in our database! Try creating a store with a different name!';
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

    $app->get("/stores/{store_id}/addBrandForm", function($store_id) use ($app) {
      $store = Store::find($store_id);

      return $app['twig']->render('stores.html.twig', array(
        'navbar' => true,
        'message' => array(
          'title' => 'Add a brand to the store ' . $store->getName() . '!',
          'text' => 'Use the form below to add a brand to the store ' . $store->getName(),
          'link1' => array(
            'link' => '/stores',
            'text' => 'Back'
          )
        ),
        'form' => array(
            'action' => '/stores' . '/' . $store_id . '/addBrand'
        )
      ));
    });

    $app->post("/stores/{store_id}/addBrand", function($store_id) use ($app) {
      $store = Store::find($store_id);
      $brand = Brand::findByName($_POST['name']);

      if ($brand) {
        $store->addBrand($brand);
        $message_text = $brand->getName() . ' was added to the store ' . $store->getName();
      } else {
        $brand = new Brand($_POST['name']);
        $brand->save();
        $store->addBrand($brand);
        $message_text = $brand->getName() . ' was created and added to the store ' . $store->getName();
      }

      return $app['twig']->render('stores.html.twig', array(
        'navbar' => true,
        'message' => array(
          'title' => 'Add another brand to the store ' . $store->getName() . '!',
          'text' => $message_text,
          'link1' => array(
            'link' => '/stores',
            'text' => 'Back'
          )
        ),
        'form' => array(
            'action' => '/stores' . '/' . $store_id . '/addBrand'
        )
      ));
    });

    $app->get("/stores/{store_id}/deleteStore", function($store_id) use ($app) {
      $store = Store::find($store_id);
      $store->delete();

      return $app['twig']->render('stores.html.twig', array(
        'navbar' => true,
        'stores' => Store::getAll(),
        'message' => array(
          'title' => $store->getName() . ' was deleted!',
          'text' => $store->getName() . ' was deleted!',
          'link1' => array(
            'link' => '/stores',
            'text' => 'Back'
          )
        )
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
        $message_text = $_POST['name'] . ' already exists in our database! Try creating a brand with a different name!';
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
        'form' => array(
            'action' => '/brands/addBrand'
        )
      ));
    });

    $app->get("/brands/{brand_id}/addStoreForm", function($brand_id) use ($app) {
      $brand = Brand::find($brand_id);

      return $app['twig']->render('brands.html.twig', array(
        'navbar' => true,
        'message' => array(
          'title' => 'Add a store to the brand ' . $brand->getName() . '!',
          'text' => 'Use the form below to add a store to the brand ' . $brand->getName(),
          'link1' => array(
            'link' => '/brands',
            'text' => 'Back'
          )
        ),
        'form' => array(
            'action' => '/brands' . '/' . $brand_id . '/addStore'
        )
      ));
    });

    $app->post("/brands/{brand_id}/addStore", function($brand_id) use ($app) {
      $brand = Brand::find($brand_id);
      $store = Store::findByName($_POST['name']);

      if ($store) {
        $brand->addStore($store);
        $message_text = $store->getName() . ' was added to the brand ' . $brand->getName();
      } else {
        $store = new Store($_POST['name']);
        $store->save();
        $brand->addStore($store);
        $message_text = $store->getName() . ' was created and added to the brand ' . $brand->getName();
      }

      return $app['twig']->render('brands.html.twig', array(
        'navbar' => true,
        'message' => array(
          'title' => 'Add another store to the brand ' . $brand->getName() . '!',
          'text' => $message_text,
          'link1' => array(
            'link' => '/brands',
            'text' => 'Back'
          )
        ),
        'form' => array(
            'action' => '/brands' . '/' . $brand_id . '/addStore'
        )
      ));
    });

    $app->get("/brands/{brand_id}/deleteBrand", function($brand_id) use ($app) {
      $brand = Brand::find($brand_id);
      $brand->delete();

      return $app['twig']->render('brands.html.twig', array(
        'navbar' => true,
        'brands' => Brand::getAll(),
        'message' => array(
          'title' => $brand->getName() . ' was deleted!',
          'text' => $brand->getName() . ' was deleted!',
          'link1' => array(
            'link' => '/brands',
            'text' => 'Back'
          )
        )
      ));
    });

    return $app;
?>
