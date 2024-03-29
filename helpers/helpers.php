<?php
	function display_errors($errors){
		// $display = '<ul class="container-fluid mx-5 list-unstyled ">';
		$display = '<div class="container">';
		foreach ($errors as $error){
			$display .= '<div class="alert alert-danger alert-dismissible fade show">';
				// $display .= '<li class="text-danger">'.$error.'</li>';
				$display .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
				$display .= '<li class="text-danger list-unstyled">'.$error.'</li>';
			$display .= '</div>';
		}
		// $display .= '</ul>';
		$display .= '</div>';
		return $display;
	}

	function sanitize($dirty){
		return htmlentities($dirty, ENT_QUOTES, "UTF-8");
	}

	function money($number){
		return '$'.number_format($number,2);
	}

	function login($user_id){
		$_SESSION['User'] = $user_id;
		global $db;
		$date = date("Y-m-d h:i:s");
		$db->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");

		$_SESSION['success_flash'] = 'You are now logged in!';
		header('Location: index.php');
	}

	function is_logged_in(){
		if (isset($_SESSION['User']) && $_SESSION['User'] > 0) {
			return true;
		}
		return false;
	}

	function login_error_redirect($url = 'login.php'){
		$_SESSION['error_flash'] = 'You must be logged in to access that page';
		header('Location: ' .$url);
	}

	function permission_error_redirect($url = 'login.php'){
		$_SESSION['error_flash'] = 'You do not have permission to access that page';
		header('Location: ' .$url);
	}

	function has_permission($permission = 'admin'){
		global $user_data;
		$permissions = explode(',', $user_data['permissions']);
		if (in_array($permission, $permissions, true)) {
			return true;
		}
		return false;
	}

	function pretty_date($date){
		date_default_timezone_set("Africa/Lagos");
		return date("M d, Y h:i A", strtotime($date));
	}

	function get_category($child_id){
		global $db;
		$id = sanitize($child_id);
		$sql = "SELECT p.id AS 'pid', p.category AS 'parent', c.id AS 'cid', c.category AS 'child' 
		FROM categories c INNER JOIN categories p ON c.parent = p.id
		WHERE c.id = $id";
		$query = $db->query($sql);
		$category = mysqli_fetch_assoc($query);
		return $category;
	}
?>