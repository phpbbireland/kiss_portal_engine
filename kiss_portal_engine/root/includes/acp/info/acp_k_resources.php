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

if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* @package module_install
*/

class acp_k_resources_info
{
	function module()
	{
		return array(
			'filename' => 'acp_k_resources',
			'title'    => 'ACP_K_RESOURCES',
			'version'  => '1.0.19',
			'modes'    => array(
				'select' => array('title' => 'ACP_K_RESOURCES', 'auth' => 'acl_a_k_tools', 'cat' => array('ACP_K_TOOLS')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}

?>