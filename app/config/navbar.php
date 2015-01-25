<?php
/**
 * Config-file for navigation bar.
 */
// Setup $items.
$a = array(
	// This is a menu item. 
	'home' => array(
		'text' => '<i class="fa fa-university"></i> Home',
		'url' => $this->di->get('url')->create(''),
		'title' => 'Home'
	),
	// This is a menu item. 
	'about' => array(
		'text' => '<i class="fa fa-info-circle"></i> About',
		'url' => $this->di->get('url')->create('about'),
		'title' => 'About'
	),
	// This is a menu item. 
	'questions' => array(
		'text' => '<i class="fa fa-comment"></i> Questions',
		'url' => $this->di->get('url')->create('questions'),
		'title' => 'Questions',
		'mark-if-parent-of' => 'questions'
	),
	// This is a menu item.
	'tags' => array(
		'text' => '<i class="fa fa-tags"></i> Tags',
		'url' => $this->di->get('url')->create('tags'),
		'title' => 'Tags',
		'mark-if-parent-of' => 'tags'
	),
	// This is a menu item.
	'users' => array(
		'text' => '<i class="fa fa-users"></i> Users',
		'url' => $this->di->get('url')->create('users'),
		'title' => 'Users',
		'mark-if-parent-of' => 'users'
	)
);
// Setup login.
if ($this->di->session->get('user')) {
	$user = $this->di->session->get('user');
	$b    = array(
		// This is a menu item.
		'ask' => array(
			'text' => '<i class="fa fa-question"></i>',
			'url' => $this->di->get('url')->create('ask'),
			'title' => 'Ask Question'
		),
		// This is a menu item.
		'profile' => array(
			'text' => "<i class='fa fa-caret-down'></i> <i class='fa fa-user'></i>  " . $user[0]->username,
			'url' => $this->di->get('url')->create('users/self'),
			'title' => 'User Profile',
			// Here we add the submenu, with some menu items, as part of a existing menu item.
			'submenu' => array(
				'items' => array(
					// This is a menu item of the submenu.
					'logout' => array(
						'text' => '<i class="fa fa-sign-out"></i> Logout',
						'url' => $this->di->get('url')->create('users/logout'),
						'title' => 'Logout'
					)
				)
			)
		)
	);
} else {
	$b = array(
		// This is a menu item.
		'signin' => array(
			'text' => '<i class="fa fa-sign-in"></i> Sign In',
			'url' => $this->di->get('url')->create('signin'),
			'title' => 'Sign In'
		),
		// This is a menu item.
		'signup' => array(
			'text' => '<i class="fa fa-user"></i> Sign Up',
			'url' => $this->di->get('url')->create('signup'),
			'title' => 'Sign Up'
		)
	);
}
$items = array_merge($a, $b);
unset($a);
unset($b);
// Setup return.
return array(
	// Use for styling the menu.
	'class' => 'navbar',
	// Here comes the menu strcture.
	'items' => $items,
	// Callback tracing the current selected menu item base on scriptname.
	'callback' => function($url) {
		if ($this->di->get('request')->getCurrentUrl($url) == $this->di->get('url')->create($url)) {
			return true;
		}
	},
	/**
	 * Callback to check if current page is a decendant of the menuitem, this check applies for those menuitems that has the setting 'mark-if-parent' set to true.
	 */
	'is_parent' => function($parent) {
		$route = $this->di->get('request')->getRoute();
		return !substr_compare($parent, $route, 0, strlen($parent));
	}
);
?>