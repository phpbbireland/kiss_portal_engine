<?php
/**
* blocks_block_top_topics
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


global $k_config, $k_blocks;
$queries = $cached_queries = 0;

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_top_topics.html')
	{
		$block_cache_time = $blk['block_cache_time'];
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);

$k_top_topics_max = $k_config['k_top_topics_max'];
$k_top_topics_days = $k_config['k_top_topics_days'];


$sql = 'SELECT topic_id, topic_title, topic_replies, forum_id
	FROM ' . TOPICS_TABLE . '
	WHERE topic_approved = 1
		AND topic_replies <> 0
		AND topic_status <> ' . ITEM_MOVED . '
		AND topic_last_post_time > ' . (time() - $k_top_topics_days * 86400 ) . '
	ORDER BY topic_replies DESC';

$result = $db->sql_query_limit($sql, $k_top_topics_max, 0, $block_cache_time);

while ($row = $db->sql_fetchrow($result))
{

    if (!$row['topic_title'])
    {
        continue;
    }

	if ($auth->acl_gets('f_list', 'f_read', $row['forum_id']))
	{
		// reduce length and pad with ... if too long //
		$my_title = $row['topic_title'];

		if (strlen($my_title) > 16)
		{
			$my_title = sgp_checksize ($my_title, 14);
		}

		$template->assign_block_vars('top_topics', array(
			'TOPIC_TITLE'		=> $my_title,
			'FULL_T_TITLE'		=> $row['topic_title'],
			'S_SEARCH_ACTION'	=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $row['forum_id'] . '&amp;t=' . $row['topic_id']),
			'TOPIC_REPLIES'		=> $row['topic_replies'],
			)
		);
	}
}
$db->sql_freeresult($result);

$template->assign_vars(array(
	'TOP_TOPICS_DAYS'	=> sprintf($user->lang['TOP_TOPICS_DAYS'], $k_config['k_top_topics_days']),
	'TOP_TOPICS_DEBUG'	=> sprintf($user->lang['PORTAL_DEBUG_QUERIES'], ($queries) ? $queries : '0', ($cached_queries) ? $cached_queries : '0', ($total_queries) ? $total_queries : '0'),
));

?>