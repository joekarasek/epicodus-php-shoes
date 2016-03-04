<?php
    class Brand
    {
        private $name;
        private $id;

        function __construct($name, $id=null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO brands (name) VALUES ('{$this->getName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM brands WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM brands_stores WHERE brand_id = {$this->getId()};");
        }

        function addStore($store)
        {
          $GLOBALS['DB']->exec("INSERT INTO brands_stores (brand_id, store_id) VALUES ({$this->getId()}, {$store->getId()});");
        }

        function getStores()
        {
          $query = $GLOBALS['DB']->query("SELECT stores.* FROM
            brands_stores JOIN stores ON(brands_stores.store_id = stores.id )
            WHERE brand_id = {$this->getId()};");
            $stores = $query->fetchAll(PDO::FETCH_ASSOC);
            $results = array();
            foreach ($stores as $store) {
              $name = $store['name'];
              $id = $store['id'];
              $new_store = new Store($name, $id);
              array_push($results, $new_store);
            }
            return $results;
          }

        static function getAll()
        {
            $returned_brands = $GLOBALS['DB']->query("SELECT * FROM brands ORDER BY name ASC;");
            $brands = array();
            foreach ($returned_brands as $brand) {
                $name = $brand['name'];
                $id = $brand['id'];
                $new_brand = new Brand($name, $id);
                array_push($brands, $new_brand);
            }
            return $brands;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->query("DELETE FROM brands;");
            $GLOBALS['DB']->query("DELETE FROM brands_stores;");
        }

        static function find($search_id)
        {
            $found_brand = NULL;
            $brands = Brand::getAll();
            foreach ($brands as $brand) {
                if ($brand->getId() == $search_id) {
                    $found_brand = $brand;
                }
            }
            return $found_brand;
        }

        static function findByName($search_name)
        {
            $brands = Brand::getAll();
            foreach ($brands as $brand) {
                if ($brand->getName() == $search_name) {
                    return $brand;
                }
            }
            return false;
        }
    }
 ?>
