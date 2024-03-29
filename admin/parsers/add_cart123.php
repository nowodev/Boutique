<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/Projects/InProgress/Boutique/core/init.php';
	// $product_id = sanitize($_POST['product_id']);
	// $size = sanitize($_POST['size']);
	// $available = sanitize($_POST['available']);
	// $quantity = sanitize($_POST['quantity']);
	$product_id = isset($_POST['product_id'])? sanitize($_POST['product_id']):'';
	$size = isset($_POST['size'])? sanitize($_POST['size']):'';
	$available = isset($_POST['available'])? sanitize($_POST['available']):'';
	$quantity = isset($_POST['quantity'])? sanitize($_POST['quantity']):'';
	$item = array();
	$item[] = array(
		'id'       == $product_id,
		'size'     == $size,
		'quantity' == $quantity,
	);
	
	//browsers cannot accept cookies from localhost
	$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
	$query = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
	$product = mysqli_fetch_assoc($query);
	$_SESSION['success_flash'] = $product['title']. ' was added to your cart.';
	
	//check to see if the cart cookie exists
	if ($cart_id != '') {
		$cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
		$cart = mysqli_fetch_assoc($cartQ);
		$previous_items = array();
		$previous_items = json_decode($cart['items'], true);
		$item_match = 0;
		$new_items = array();
		foreach ($previous_items as $pitem){
			if ($item[0]['id'] == $pitem['id'] && $item[0]['size'] == $pitem['size']){
				$pitem['quantity'] = $pitem['quantity'] + $item[0]['quantity'];
				if ($pitem['quantity'] > $available){
					$pitem['quantity'] = $available;
				}
				$item_match = 1;
			}
			$new_items[] = $pitem;
		}

		if ($item_match != 1){
			$new_items = array_merge($item, $previous_items);
		}

		$items_json = json_encode($new_items);
		$cart_expire = date("Y-m-d H:i:s", strtotime("+30 days"));
		$db->query("UPDATE cart SET items = '{$items_json}', expire_date = '{$cart_expire}' WHERE id = '{$cart_id}'");
		setcookie(CART_COOKIE, '', 1, '/', $domain, false);
		setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, '/', $domain, false);
	} else{
		// add the cart to the database and set cookie
		$items_json = json_encode($item);
		$cart_expire = date("Y-m-d H:i:s", strtotime("+30 days"));
		$db->query("INSERT INTO cart (items, expire_date) VALUES ('{$items_json}', '{$cart_expire}')");
		$cart_id = $db->insert_id;
		// db->mysqli_insert_id($cart_id);
		setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, '/', $domain, false);
	}
?>

<!-- After changing the button tag from my modal to an <input type="button" and set my setcookie like this: setcookie('CART_COOKIE',$cart_id,time() + (86400 * 7),'/',false,false); it worked!! Yeii!
Maybe the CART_COOKIE_EXPIRE didn't worked because of the encript json?because in the config file I have it defined and initialized with the same value: define('CART_COOKIE_EXPIRE', time() + (86400 * 7));, but if I set setcookie('CART_COOKIE',$cart_id,'CART_COOKIE_EXPIRE','/',false,false); does not work, with or witout the single quotes..
Anyway, sorry for this long message. Now it works! Thank you for your lessons! -->