<?php
/**
* blocks_block_forum_categories
*
* @package Kiss Portal Engine
* @version $Id$
* @copyright (c) 2005-2013 phpbbireland
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
* Updated:
*/

/**
* @ignore
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

//for bots test //
//$page_title = $user->lang['BLOCK_CATEGORIES'];

// SGP debug vars. Used to display query loading on a block basis...
$queries = $cached_queries = $total_queries = 0;


/***
* Validation notes, version: 1.0.19 (2 February 2013)
*
* As this block's data can be obtained from block_build.php (which processes
* the phpBB core for use by the portal page), we do not need to reinvent the
* wheel, so I removed it.
*
* This file is included to ensure all updates overwrite previous file versions...
*
***/

$template->assign_vars(array(
	'FORUM_CATEGORIES_DEBUG'	=> sprintf($user->lang['PORTAL_DEBUG_QUERIES'], ($queries) ? $queries : '0', ($cached_queries) ? $cached_queries : '0', ($total_queries) ? $total_queries : '0'),
));

?>