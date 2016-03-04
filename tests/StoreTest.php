<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once __DIR__ . '/../src/Store.php';
    require_once __DIR__ . '/../src/Brand.php';

    $server = 'mysql:host=localhost:8889;dbname=shoes_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class StoreTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Store::deleteAll();
            Brand::deleteAll();
        }

        function test_save()
        {
            // Arrange
            $name = 'Shoe-porium';
            $test_Store = new Store($name);

            // Act
            $test_Store->save();

            // Assert
            $result = Store::getAll();
            $this->assertEquals($test_Store, $result[0]);
        }

        function test_getId()
        {
            // Arrange
            $name = 'Shoe-porium';
            $id = 1;
            $test_Store = new Store($name, $id);

            // Act
            $result = $test_Store->getId();

            // Assert
            $this->assertEquals(1, $result);
        }

        function test_getAll()
        {
            // Arrange
            $name = 'Shoe-porium';
            $test_Store = new Store($name);
            $test_Store->save();
            $name2 = 'Marth Stewart';
            $test_Store2 = new Store($name2);
            $test_Store2->save();

            // Act
            $result = Store::getAll();

            // Assert
            $this->assertEquals([$test_Store, $test_Store2], $result);

        }

        function test_deleteAll()
        {
            // Arrange
            $name = 'Shoe-porium';
            $test_Store = new Store($name);
            $test_Store->save();
            $name2 = 'Shoe Warehouse';
            $test_Store2 = new Store($name2);
            $test_Store2->save();

            // Act
            Store::deleteAll();
            $result = Store::getAll();

            // Assert
            $this->assertEquals([], $result);

        }

        function test_find()
        {
            // Arrange
            $name = 'Shoe-porium';
            $test_Store = new Store($name);
            $test_Store->save();
            $name2 = 'Shoe Warehouse';
            $test_Store2 = new Store($name2);
            $test_Store2->save();

            // Act
            $id = $test_Store->getId();
            $result = Store::find($id);

            // Assert
            $this->assertEquals($test_Store, $result);
        }

        function test_update()
        {
            // Arrange
            $name = 'Shoe-porium';
            $test_Store = new Store($name);
            $test_Store->save();
            $new_name = 'The Shoe-porium';

            // Act
            $test_Store->update($new_name);
            $result = $test_Store->getName();

            // Assert
            $this->assertEquals($new_name, $result);
        }

        function test_delete()
        {
            // Arrange
            $name = 'Shoe-porium';
            $test_Store = new Store($name);
            $test_Store->save();
            $name2 = 'Shoe Warehouse';
            $test_Store2 = new Store($name2);
            $test_Store2->save();

            // Act
            $test_Store->delete();

            // Assert
            $this->assertEquals([$test_Store2], Store::getAll());
        }

        function test_addBrand()
        {
            // Arrange
            $name = 'Shoe-porium';
            $test_Store = new Store($name);
            $test_Store->save();

            $name2 = 'Nike';
            $test_brand = new Brand($name2);
            $test_brand->save();

            // Act
            $test_Store->addBrand($test_brand);

            // Assert
            $this->assertEquals([$test_brand], $test_Store->getBrands());
        }

        function test_getBrand()
        {
            // Arrange
            $name = 'Shoe-porium';
            $test_Store = new Store($name);
            $test_Store->save();

            $name2 = 'Nike';
            $test_brand = new Brand($name2);
            $test_brand->save();

            $name3 = 'Puma';
            $test_brand2 = new Brand($name3);
            $test_brand2->save();

            // Act
            $test_Store->addBrand($test_brand);
            $test_Store->addBrand($test_brand2);
            $brand_results = $test_Store->getBrands();

            // Assert
            $this->assertEquals([$test_brand, $test_brand2], $brand_results);
        }
    }
 ?>
