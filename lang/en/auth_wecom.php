<?php
/**
 * Language strings for the WeCom authentication plugin.
 *
 * @package    auth_wecom
 * @copyright  2026 onwards Jason
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'WeCom Authentication';
$string['auth_wecomdescription'] = 'This plugin allows users to log in using Enterprise WeChat (WeCom) SSO and binding.';
$string['wecom_corpid'] = 'CorpID';
$string['wecom_corpsecret'] = 'CorpSecret';
$string['wecom_agentid'] = 'AgentID';
$string['wecom_corpid_desc'] = 'The CorpID of your Enterprise WeChat account.';
$string['wecom_corpsecret_desc'] = 'The Secret of your Enterprise WeChat application.';
$string['wecom_agentid_desc'] = 'The AgentID of your Enterprise WeChat application.';
$string['wecom_autocreate'] = 'Enable Automatic Account Creation';
$string['wecom_autocreate_desc'] = 'If enabled, a new Moodle account will be automatically created if the WeCom UserID does not exist in the system.';
$string['wecom_login_button'] = 'Login with WeCom';
$string['wecom_bind_button'] = 'Bind WeCom';
$string['wecom_bind_title'] = 'Bind WeCom to Moodle Account';
$string['wecom_bind_desc'] = 'Your WeCom account is not bound to a Moodle account. Please log in with your Moodle credentials to bind them.';
$string['wecom_bind_success'] = 'Successfully bound your WeCom account.';
$string['wecom_bind_fail'] = 'Invalid Moodle username or password.';
$string['wecom_unbind'] = 'Unbind WeCom';
$string['wecom_status_bound'] = 'Your account is bound to WeCom.';
$string['wecom_status_unbound'] = 'Your account is not bound to WeCom.';
$string['wecom_userid_label'] = 'WeCom UserID';
$string['wecom_currently_bound'] = 'Bound WeCom ID: {$a}';
$string['wecom_not_bound'] = 'Not bound to WeCom';
$string['manage_binding'] = 'Manage Binding';
$string['error_fetching_wecom_userid'] = 'Error fetching WeCom UserID from code.';
$string['error_fetching_wecom_user_details'] = 'Error fetching user details from WeCom API.';
$string['error_creating_user'] = 'Failed to create Moodle user account.';
$string['error_wecom_already_bound'] = 'This WeCom account is already bound to another Moodle user.';
