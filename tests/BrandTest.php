<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once __DIR__ . '/../src/Brand.php';
    require_once __DIR__ . '/../src/Store.php';

    $server = 'mysql:host=localhost:8889;dbname=shoes_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BrandTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Brand::deleteAll();
            Store::deleteAll();
        }

        function test_save()
        {
            // Arrange
            $name = 'Nike';
            $test_brand = new Brand($name);

            // Act
            $test_brand->save();

            // Assert
            $result = Brand::getAll();
            $this->assertEquals($test_brand, $result[0]);
        }

        function test_getId()
        {
            // Arrange
            $name = 'Nike';
            $id = 1;
            $test_brand = new Brand($name, $id);

            // Act
            $result = $test_brand->getId();

            // Assert
            $this->assertEquals(1, $result);
        }

        function test_getAll()
        {
            // Arrange
            $name = 'Nike';
            $test_brand = new Brand($name);
            $test_brand->save();

            $name2 = 'Adidadas';
            $test_brand2 = new Brand($name2);
            $test_brand2->save();

            // Act
            $result = Brand::getAll();

            // Assert
            $this->assertEquals([$test_brand, $test_brand2], $result);

        }

        function test_deleteAll()
        {
            // Arrange
            $name = 'Nike';
            $test_brand = new Brand($name);
            $test_brand->save();

            $name2 = 'Air Jordans';
            $test_brand2 = new Brand($name2);
            $test_brand2->save();

            // Act
            Brand::deleteAll();
            $result = Brand::getAll();

            // Assert
            $this->assertEquals([], $result);

        }

        function test_find()
        {
            // Arrange
            $name = 'Nike';
            $test_brand = new Brand($name);
            $test_brand->save();

            $name2 = 'Puma';
            $test_brand2 = new Brand($name2);
            $test_brand2->save();

            // Act
            $id = $test_brand->getId();
            $result = Brand::find($id);

            // Assert
            $this->assertEquals($test_brand, $result);
        }

        function test_addStore()
        {
            // Arrange
            $name = 'Shoe-porium';
            $test_store = new Store($name);
            $test_store->save();

            $name2 = 'Nike';
            $test_brand = new Brand($name2);
            $test_brand->save();

            // Act
            $test_brand->addStore($test_store);

            // Assert
            $this->assertEquals([$test_store], $test_brand->getStores());
        }

        function test_getStores()
        {
            // Arrange
            $name = 'Shoe-porium';
            $test_store = new Store($name);
            $test_store->save();

            $name2 = 'The Shoe Wareshouse';
            $test_store2 = new Store($name2);
            $test_store2->save();

            $name3 = 'Nike';
            $test_brand = new Brand($name3);
            $test_brand->save();

            // Act
            $test_brand->addStore($test_store);
            $test_brand->addStore($test_store2);
            $store_results = $test_brand->getStores();

            // Assert
            $this->assertEquals([$test_store, $test_store2], $store_results);
        }

    }
 ?>
