<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Cart {
    protected $cart_contents = array();
    
    public function __construct() {
        // get the shopping cart array from the session
        $this->cart_contents = !empty($_SESSION['cart_contents']) ? $_SESSION['cart_contents'] : NULL;
        if ($this->cart_contents === NULL) {
            // set some base values
            $this->cart_contents = array('cart_total' => 0, 'total_items' => 0);
        }
    }
    
    public function contents() {
        $cart = array_reverse($this->cart_contents);
        unset($cart['total_items']);
        unset($cart['cart_total']);
        return $cart;
    }
    
    public function get_item($row_id) {
        return (in_array($row_id, array('total_items', 'cart_total'), TRUE) || !isset($this->cart_contents[$row_id]))
            ? FALSE
            : $this->cart_contents[$row_id];
    }
    
    public function total_items() {
        return $this->cart_contents['total_items'];
    }
    
    public function total() {
        return $this->cart_contents['cart_total'];
    }
    
    public function insert($item = array()) {
        if (!is_array($item) || count($item) === 0) {
            return FALSE;
        }

        if (!isset($item['id'], $item['name'], $item['price'], $item['qty'])) {
            return FALSE;
        }

        $item['qty'] = (float) $item['qty'];
        if ($item['qty'] == 0) {
            return FALSE;
        }

        $item['price'] = (float) $item['price'];
        $rowid = md5($item['id']);
        $old_qty = isset($this->cart_contents[$rowid]['qty']) ? (int) $this->cart_contents[$rowid]['qty'] : 0;

        $item['rowid'] = $rowid;
        $item['qty'] += $old_qty;
        $this->cart_contents[$rowid] = $item;
                
        if ($this->save_cart()) {
            return isset($rowid) ? $rowid : TRUE;
        }

        return FALSE;
    }
    
    public function update($item = array()) {
        if (!is_array($item) || count($item) === 0) {
            return FALSE;
        }

        if (!isset($item['rowid'], $this->cart_contents[$item['rowid']])) {
            return FALSE;
        }

        if (isset($item['qty'])) {
            $item['qty'] = (float) $item['qty'];
            if ($item['qty'] == 0) {
                unset($this->cart_contents[$item['rowid']]);
                return TRUE;
            }
        }
                
        $keys = array_intersect(array_keys($this->cart_contents[$item['rowid']]), array_keys($item));

        if (isset($item['price'])) {
            $item['price'] = (float) $item['price'];
        }

        foreach (array_diff($keys, array('id', 'name')) as $key) {
            $this->cart_contents[$item['rowid']][$key] = $item[$key];
        }

        $this->save_cart();
        return TRUE;
    }
    
    protected function save_cart() {
        $this->cart_contents['total_items'] = 0;
        $this->cart_contents['cart_total'] = 0;

        foreach ($this->cart_contents as $key => $val) {
            if (!is_array($val) || !isset($val['price'], $val['qty'])) {
                continue;
            }
     
            $this->cart_contents['cart_total'] += ($val['price'] * $val['qty']);
            $this->cart_contents['total_items'] += $val['qty'];
            $this->cart_contents[$key]['subtotal'] = ($this->cart_contents[$key]['price'] * $this->cart_contents[$key]['qty']);
        }
        
        if (count($this->cart_contents) <= 2) {
            unset($_SESSION['cart_contents']);
            return FALSE;
        }

        $_SESSION['cart_contents'] = $this->cart_contents;
        return TRUE;
    }
    
    public function remove($row_id) {
        unset($this->cart_contents[$row_id]);
        $this->save_cart();
        return TRUE;
    }
     
    public function destroy() {
        $this->cart_contents = array('cart_total' => 0, 'total_items' => 0);
        unset($_SESSION['cart_contents']);
    }
}
?>