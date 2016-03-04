<?php
    class Store
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

        function setName($name)
        {
            $this->name = $name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO stores (name) VALUES ('{$this->getName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE stores SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM stores WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM brands_stores WHERE store_id = {$this->getId()};");
        }

        function addBrand($brand)
        {
            $GLOBALS['DB']->exec("INSERT INTO brands_stores (brand_id, store_id) VALUES ({$brand->getId()}, {$this->getId()});");
        }

        function getBrands()
        {
            $query = $GLOBALS['DB']->query("SELECT brands.* FROM
                brands_stores JOIN brands ON(brands_stores.brand_id = brands.id )
                WHERE store_id = {$this->getId()};");
            $brands = $query->fetchAll(PDO::FETCH_ASSOC);
            $results = array();
            foreach ($brands as $brand) {
                $name = $brand['name'];
                $id = $brand['id'];
                $new_brand = new Brand($name, $id);
                array_push($results, $new_brand);
            }
            return $results;
        }

        static function getAll()
        {
            $returned_stores = $GLOBALS['DB']->query("SELECT * FROM stores;");
            $stores = array();
            foreach ($returned_stores as $store) {
                $name = $store['name'];
                $id = $store['id'];
                $new_store = new Store($name, $id);
                array_push($stores, $new_store);
            }
            return $stores;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->query("DELETE FROM stores;");
            $GLOBALS['DB']->query("DELETE FROM brands_stores;");
        }

        static function find($search_id)
        {
            $found_store = NULL;
            $stores = Store::getAll();
            foreach ($stores as $store) {
                if ($store->getId() == $search_id) {
                    $found_store = $store;
                }
            }
            return $found_store;
        }

        static function findByName($search_name)
        {
            $found_store = NULL;
            $stores = Store::getAll();
            foreach ($stores as $store) {
                if ($store->getName() == $search_name) {
                    return true;
                }
            }
            return false;
        }
    }
 ?>
