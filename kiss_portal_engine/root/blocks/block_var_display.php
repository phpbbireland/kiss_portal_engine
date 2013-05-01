<?php
/**
* blocks_block_var_display
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

$queries = $cached_queries = $total_queries = 0;

$template->assign_vars(array(
	'SGP_DEBUG_VD'	=> sprintf($user->lang['PORTAL_DEBUG_QUERIES'], ($queries) ? $queries : '0', ($cached_queries) ? $cached_queries : '0', ($total_queries) ? $total_queries : '0'),
));

?>