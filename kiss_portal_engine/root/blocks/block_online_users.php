<?php
/**
* blocks_block_online_users
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


$queries = $cached_queries = 0;

/* already processed by phpBB common code section */

$template->assign_vars(array(
	'ONLINE_USERS_DEBUG'	=> sprintf($user->lang['PORTAL_DEBUG_QUERIES'], ($queries) ? $queries : '0', ($cached_queries) ? $cached_queries : '0', ($total_queries) ? $total_queries : '0'),
));

?>