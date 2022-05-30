<?php

namespace PerfectWPWCO\Models;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Utils\Arr;

abstract class AbstractModel implements \ArrayAccess
{
    protected $primary = 'id';

    protected $attributes = [];

    abstract public function format();

    abstract public static function getTable();

    public function __set($key, $value)
    {
        Arr::set($this->attributes, $key, $value);
    }

    public function __get($key)
    {
        return Arr::get($this->attributes, $key);
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public static function getPrefixedTable()
    {
        global $wpdb;
        return $wpdb->prefix . static::getTable();
    }

    public function getPrimary()
    {
        return $this->primary;
    }

    public function getAttributesFormat()
    {
        $format = [];

        foreach ($this->attributes as $key => $attribute) {
            Arr::set($format, $key, Arr::get($this->format(), $key));
        }

        return $format;
    }

    public function save()
    {
        if (method_exists($this, 'onSaving')) {
            $this->onSaving();
        }

        if ($this->{$this->getPrimary()} === null) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }

    public function insert()
    {
        global $wpdb;

        if ($wpdb->insert(static::getPrefixedTable(), $this->attributes, $this->getAttributesFormat())) {
            return $wpdb->insert_id;
        }

        return null;
    }

    public function update()
    {
        global $wpdb;

        if ($wpdb->update(static::getPrefixedTable(),
            $this->attributes, [
                $this->getPrimary() => $this->{$this->getPrimary()}
            ], $this->getAttributesFormat(), [
                $this->getPrimary() => '%d'
            ]) !== false) {

            return Arr::get($this->attributes, $this->getPrimary());
        }

        return null;
    }

    public function delete()
    {
        global $wpdb;

        return $wpdb->delete(static::getPrefixedTable(), [
            $this->getPrimary() => Arr::get($this->attributes, $this->getPrimary())
        ], [
            $this->getPrimary() => '%d'
        ]);
    }

    public static function findFirstBy($by, $id)
    {
        global $wpdb;

        $params = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . static::getPrefixedTable() . ' WHERE '.esc_sql($by).' = %s', $id), ARRAY_A);

        if ($params === null) {
            return null;
        }

        return self::buildModel($params);
    }

    public static function findBy($by, $id)
    {
        global $wpdb;

        $models = [];
        foreach ($wpdb->get_results($wpdb->prepare('SELECT * FROM ' . static::getPrefixedTable() . ' WHERE '.esc_sql($by).' = %s', $id), ARRAY_A) as $result) {
            $models[] = self::buildModel($result);
        }

        return $models;
    }

    public static function getAll()
    {
        global $wpdb;
        $models = [];
        foreach ($wpdb->get_results('SELECT * FROM ' . static::getPrefixedTable(), ARRAY_A) as $result) {
            $models[] = self::buildModel($result);
        }

        return $models;
    }

    public static function buildModel($params)
    {
        $className = get_called_class();
        $model = new $className;
        $model->fill($params);

        return $model;
    }

    public function fill(array $params)
    {
        $this->attributes = $params;
    }

    public static function deleteBy($id, $value)
    {
        foreach (static::findBy($id, $value) as $item) {
            $item->delete();
        }
    }

    public function offsetExists($offset)
    {
        return Arr::has($this->attributes, $offset);
    }

    public function offsetSet($offset, $value)
    {
        Arr::set($this->attributes, $offset, $value);
    }

    public function offsetGet($offset)
    {
        return Arr::get($this->attributes, $offset);
    }

    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }
}