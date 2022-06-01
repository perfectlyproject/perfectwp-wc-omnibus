<?php

namespace PerfectWPWCO\Models;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Plugin;

class HistoryPrice extends AbstractModel
{
    public function format()
    {
        return [
            'id' => '%d',
            'product_id' => '%d',
            'date' => '%s',
            'price' => '%s',
        ];
    }

    public static function getTable()
    {
        return Plugin::SLUG . '_history_prices';
    }

    public function setId($id)
    {
        $this->id = $id !== null ? intval($id) : null;
    }

    public function setProductId($productId)
    {
        $this->product_id = $productId !== null ? intval($productId) : null;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date->format('Y-m-d H:i:s');
    }

    public function setPrice($price)
    {
        $this->price = $price !== null ? floatval($price) : 0;
    }

    public function getProductId()
    {
        return intval($this->product_id);
    }

    public function getPrice()
    {
        return floatval($this->price);
    }

    public function getDate()
    {
        return new \DateTime($this->date);
    }
}