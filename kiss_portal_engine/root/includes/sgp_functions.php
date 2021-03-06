<?php
/**
*
* @package Kiss Portal Engine
* @version $Id$
* @copyright (c) 2005-2013 phpbbireland
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
* Updated: 01 May 2013
*
* A couple of functions rescued from functions.php
* @copyright (c) 2007 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

/**
* @ignore
*/

if ( !defined('IN_PHPBB') )
{
	exit;
}

global $phpbb_root_path;

/***
* Stargate functions starts
*/


/***
* generate random logo
*/
if (!function_exists('sgp_get_rand_logo'))
{
	function sgp_get_rand_logo()
	{
		// initalise variables //
		global $user, $phpbb_root_path, $k_config;
		$rand_logo = "";
		$imglist = "";
		$imgs ="";

		// Random logos are disabled config, so return default logo //
		if ($k_config['k_allow_rotating_logos'] == 0)
		{
			return $user->img('site_logo');
		}

		mt_srand((double)microtime()*1000001);

		$logos_dir = "{$phpbb_root_path}styles/" . $user->theme['theme_path'] . '/theme/images/logos';

		$handle = @opendir($logos_dir);

		// for logo in default directory
		//@$handle=opendir('images/logos');

		if (!$handle) // no handle so we don't have logo directory or we are attempting to login to ACP so we need to return the default logo //
		{
			return($user->img('site_logo'));
		}

		while (false !== ($file = readdir($handle)))
		{
			if (stripos($file, ".gif") || stripos($file, ".jpg") || stripos($file, ".png") && stripos($file ,"ogo_") || stripos($file ,"logo"))
			{
				$imglist .= "$file ";
			}
		}
		closedir($handle);

		$imglist = explode(" ", $imglist);

		if (sizeof($imglist) < 2)
		{
			return $user->img('site_logo');
		}

		$random = mt_rand(0, (mt_rand(0, (sizeof($imglist)-2))));

		$image = $imglist[$random];

		$rand_logo .= '<img src="' . $logos_dir . '/' . $image . '" alt="" /><br />';

		// uncomment next line if template assignment is required //
		//$template->assign_vars(array('RAND_LOGO' => $rand_logo));

		return ($rand_logo);
	}
}

/***
* set config value phpbb code reused
*/
if (!function_exists('sgp_acp_set_config'))
{
	function sgp_acp_set_config($config_name, $config_value, $is_dynamic = false)
	{
		global $db, $cache, $k_config;

		$sql = 'UPDATE ' . K_BLOCKS_CONFIG_VAR_TABLE . "
			SET config_value = '" . $db->sql_escape($config_value) . "'
			WHERE config_name = '" . $db->sql_escape($config_name) . "'";
		$db->sql_query($sql);

		if (!$db->sql_affectedrows() && !isset($k_config[$config_name]))
		{
			$sql = 'INSERT INTO ' . K_BLOCKS_CONFIG_VAR_TABLE . ' ' . $db->sql_build_array('INSERT', array(
				'config_name'   => $config_name,
				'config_value'  => $config_value,
				'is_dynamic'    => ($is_dynamic) ? 1 : 0));
			$db->sql_query($sql);
		}

		$k_config[$config_name] = $config_value;

		if (!$is_dynamic)
		{
			$cache->destroy('k_config');
			$cache->destroy('config');
		}
	}
}
/***
* return a k_config value (may not be required)
*/
if (!function_exists('get_k_config_var'))
{
	function get_k_config_var($item)
	{
		if (isset($item))
		{
			return($item);
		}

		$sql = 'SELECT config_name, config_value
			FROM ' . K_BLOCKS_CONFIG_VAR_TABLE . '
			WHERE config_name = ' . (int)$item;

		$row = $db->sql_fetchrow($result);

		//$k_config[$row['config_name']] = $row['config_value'];
		return $row['config_value'];
	}
}

/***
* little function to create text version for a comnpletion bar 0-100%
*/
if (!function_exists('k_progress_bar'))
{
	function k_progress_bar($percent)
	{
		// $percent = number between 0 and 100 //

		$ss = "";

		// define these in css
		$start = '<b class="green">';   // green
		$middl = '<b class="orange">';  // orange
		$endss = '<b class="red">';     // red

		$tens = $percent / 10; // how many tens //

		if ($percent % 10)
		{
			$i = 1;
		}
		else
		{
			$i = 0;
		}

		for ($i; $i < ($percent / 10); $i++)
		{
			$ss .= '|';
		}

		$start .= $ss . '</b>';

		if ($percent % 10)
		{
			$start .= $middl . '|' . '</b>' . $endss;
		}
		else
		{
			$start .= '' . $endss;
		}

		while ($i++ < 10)
		{
			$start .= '|';
		}

		$start .= '</b>';

		return ' [' . $start . ']';
	}
}


/***
* same as truncate_string() with ...
*/
if (!function_exists('sgp_checksize'))
{
	function sgp_checksize($txt,$len)
	{
		if (strlen($txt) > $len)
		{
			$txt = truncate_string($txt, $len);
			$txt .= '...';
		}
		return($txt);
	}
}


/***
* sort smilies
*/
if (!function_exists('smiley_sort'))
{
	function smiley_sort($a, $b)
	{
		if ( strlen($a['code']) == strlen($b['code']) )
		{
			return 0;
		}

		return ( strlen($a['code']) > strlen($b['code']) ) ? -1 : 1;
	}
}

/***
* search block search
*/
if (!function_exists('search_block_func'))
{
	function search_block_func()
	{
		global $lang, $template, $portal_config, $board_config, $keywords, $phpbb_root_path;

		$phpEx = substr(strrchr(__FILE__, '.'), 1);

		$template->assign_vars(array(
			"L_SEARCH_ADV"     => $lang['SEARCH_ADV'],
			"L_SEARCH_OPTION"  => (!empty($portal_config['search_option_text'])) ? $portal_config['search_option_text'] : $board_config ['sitename'],
			'U_SEARCH'         => append_sid("{$phpbb_root_path}search.$phpEx", 'keywords=' . urlencode($keywords)),
			)
		);
	}
}

/**
*	returns the users group name
*/
if (!function_exists('which_group'))
{
	function which_group($id)
	{
		global $db, $template;

		// Get group name for this user
		$sql = 'SELECT group_name
			FROM ' . GROUPS_TABLE . '
			WHERE group_id = ' . (int)$id;

		$result = $db->sql_query($sql,650);

		$name = $db->sql_fetchfield('group_name');

		$db->sql_freeresult($result);

		return ($name);
	}
}


if (!function_exists('process_for_vars'))
{
	function process_for_vars($data)
	{
		global $config, $k_config, $k_resources;

		$a = array('{', '}');
		$b = array('','');

		$replace = array();

		foreach ($k_resources as $search)
		{
			$find = $search;

			// convert to normal text //
			$search = str_replace($a, $b, $search);
			$search = strtolower($search);

			if (isset($k_config[$search]))
			{
				$replace = (isset($k_config[$search])) ? $k_config[$search] : '';
				$data = str_replace($find, $replace, $data);
			}
			else if (isset($config[$search]))
			{
				$replace = (isset($config[$search])) ? $config[$search] : '';
				$data = str_replace($find, $replace, $data);
			}
		}
		return($data);
	}
}


// Stargate Random Banner mod //
if (!function_exists('get_user_data'))
{
	function get_user_data($what = '', $id)
	{
		global $db, $template, $user;

		if (!$id)
		{
			return($user->lang['NO_ID_GIVEN']);
		}

		// Get user info
		$sql = 'SELECT user_id, username, user_colour
			FROM ' . USERS_TABLE . '
			WHERE user_id = ' . (int)$id;

		$result = $db->sql_query($sql,10);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		switch ($what)
		{
			case 'name':
				return($row['username']);

			case 'full':
				return(get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']));

			default:
				return;
		}
	}
}

/**
* templates
*/
if (!function_exists('portal_block_template'))
{
	function portal_block_template($block_file)
	{
		global $template;

		// Set template filename
		$template->set_filenames(array('block' => 'blocks/' . $block_file));

		// Return templated data
		return $template->assign_display('block', true);
	}
}

if (!function_exists('process_for_admin_bbcodes'))
{
	function process_for_admin_bbcodes($data)
	{
		global $user;

		// later pull admin bbcodes from DB //

		if ($user->data['user_id'] == ANONYMOUS)
		{
			$data = str_replace("[you]", $user->lang['GUEST'], $data);
		}
		else
		{
			$data = str_replace("[you]", ('<span style="font-weight:bold; color:#' . $user->data['user_colour'] . ';">' . $user->data['username'] . '</span>'), $data);
		}
		return($data);
	}
}


/*
* Takes the page name
* Returns the pages id
*/
if (!function_exists('get_page_id'))
{
	function get_page_id($this_page_name)
	{
		global $db, $user, $k_pages;

		foreach ($k_pages as $page)
		{
			if ($page['page_name'] == $this_page_name)
			{
				return($page['page_id']);
			}
		}
		return(0);

	}
}

/**
* Convert Menu Name to language variable... leave alone if not found!
**/
if (!function_exists('get_menu_lang_name'))
{
	function get_menu_lang_name($input)
	{
		global $user;

		// Basic error checking //
		if ($input == '')
		{
			return('');
		}

		$block_title = $input;
		$name = strtoupper($input);
		$name = str_replace(" ","_", $name);
		$block_title = (!empty($user->lang[$name])) ? $user->lang[$name] : $block_title;

		return($block_title);
	}
}

/**
* Takes a phpBB $page name and position (left/right/centre)...
* Returns true/false if the block should be displayed on a giveb page...
**/
/***
if (!function_exists('show_blocks'))
{
	function show_blocks($page, $position)
	{
		global $k_config;

		$page_id = get_page_id($page);

		if ($position == 'L' || $position == 'R' || $position == 'C')
		{
			return(true);
		}
		return(false);
	}
}
***/

if (!function_exists('s_get_vars_array'))
{
	function s_get_vars_array()
	{

		global $db, $template;
		$resources = array();

		$sql = 'SELECT * FROM ' . K_RESOURCES_TABLE  . ' ORDER BY word ASC';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$resources[] = $row['word'];
		}

		$db->sql_freeresult($result);
		return($resources);
	}
}

if (!function_exists('s_get_vars'))
{
	function s_get_vars()
	{
		global $db, $template;

		$sql = "SELECT * FROM " . K_RESOURCES_TABLE  . " WHERE type = 'V' ORDER BY word ASC";

		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('adm_vars', array(
				'VAR' => $row['word'],
			));
		}
		$db->sql_freeresult($result);
	}
}

if (!function_exists('get_link_from_image_name'))
{
	function get_link_from_image_name($image)
	{
		if (strpos($image, '.gif'))
		{
			$lnk = explode(".gif", $image);
		}
		else if (strpos($image, '.png'))
		{
			$lnk = explode(".png", $image);
		}
		else if (strpos($image, '.jpg'))
		{
			$lnk = explode(".jpg", $image);
		}

		$lnk = str_replace('+','/', $lnk);
		$lnk = str_replace('@','?', $lnk);
		$lnk = str_replace('�','+', $lnk);
		return($lnk);
	}
}
?>