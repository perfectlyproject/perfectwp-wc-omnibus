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
            'start_date' => '%s',
            'end_date' => '%s',
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

    public function setStartDate(\DateTime $date)
    {
        $this->start_date = $date->format('Y-m-d H:i:s');
    }

    public function setEndDate(\DateTime $date)
    {
        $this->end_date = $date->format('Y-m-d H:i:s');
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

    public function getStartDate()
    {
        return new \DateTime($this->start_date);
    }

    public function getEndDate()
    {
        return new \DateTime($this->start_date);
    }
}