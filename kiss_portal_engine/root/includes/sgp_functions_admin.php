<?php
/**
*
* @package Kiss Portal Engine
* @version $Id$
* @copyright (c) 2005-2013 phpbbireland
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
* Updated:
*
*/

/**
* @ignore
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (!function_exists('get_reserved_words'))
{
	function get_reserved_words()
	{
		global $reserved_words, $db, $template;
		$reserved_words = array();
		$i = 0;

		$sql = 'SELECT *
			FROM ' . K_RESOURCES_TABLE . "
			WHERE type = 'R' ";

		$result = $db->sql_query($sql, 300);

		while ($row = $db->sql_fetchrow($result))
		{
			$reserved_words[] = $row['word'];

			$template->assign_block_vars('reserved_words', array(
				'RESERVED_WORDS' => $row['word'],
			));

		}
		$db->sql_freeresult($result);

		return($reserved_words);

	}
}

if (!function_exists('get_all_groups'))
{
	function get_all_groups()
	{
		global $db, $template, $user;

		// Get us all the groups
		$sql = 'SELECT group_id, group_name
			FROM ' . GROUPS_TABLE . '
			ORDER BY group_id ASC, group_name';
		$result = $db->sql_query($sql);

		// backward compatability, set up group zero //
		$template->assign_block_vars('groups', array(
			'GROUP_NAME' => $user->lang['NONE'],
			'GROUP_ID'   => 0,
			)
		);

		while ($row = $db->sql_fetchrow($result))
		{
			$group_id = $row['group_id'];
			$group_name = $row['group_name'];

			$group_name = ($user->lang(strtoupper('G_'.$group_name))) ? $user->lang(strtoupper('G_'.$group_name)) : $user->lang(strtoupper($group_name));

			$template->assign_block_vars('groups', array(
				'GROUP_NAME' => $group_name,
				'GROUP_ID'   => $group_id,
				)
			);
		}
		$db->sql_freeresult($result);
	}
}

/***
* phpbb pregs quote reused
*/
if (!function_exists('phpbb_preg_quote'))
{
	function phpbb_preg_quote($str, $delimiter)
	{
		$text = preg_quote($str);
		$text = str_replace($delimiter, '\\' . $delimiter, $text);

		return $text;
	}
}
?>