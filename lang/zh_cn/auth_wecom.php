<?php
/**
 * Simplified Chinese language strings for the WeCom authentication plugin.
 *
 * @package    auth_wecom
 * @copyright  2026 onwards Jason
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = '企业微信认证';
$string['auth_wecomdescription'] = '本插件允许用户使用企业微信进行单点登录（SSO）及账号绑定。';
$string['wecom_corpid'] = '企业 ID (CorpID)';
$string['wecom_corpsecret'] = '应用密钥 (CorpSecret)';
$string['wecom_agentid'] = '应用 ID (AgentID)';
$string['wecom_corpid_desc'] = '您的企业微信企业 ID。';
$string['wecom_corpsecret_desc'] = '您的企业微信自建应用 Secret。';
$string['wecom_agentid_desc'] = '您的企业微信自建应用 AgentID。';
$string['wecom_autocreate'] = '启用自动建帐';
$string['wecom_autocreate_desc'] = '启用后，如果系统中不存在该企业微信 UserID 对应的账号，将会自动为其创建新的 Moodle 账号。';
$string['wecom_login_button'] = '使用企业微信登录';
$string['wecom_bind_button'] = '绑定企业微信';
$string['wecom_bind_title'] = '绑定企业微信到 Moodle 账号';
$string['wecom_bind_desc'] = '您的企业微信尚未绑定 Moodle 账号。请使用您的 Moodle 账号进行登录以完成绑定。';
$string['wecom_bind_success'] = '成功绑定您的企业微信账号。';
$string['wecom_bind_fail'] = '无效的 Moodle 用户名或密码。';
$string['wecom_unbind'] = '解除绑定';
$string['wecom_status_bound'] = '您的账号已绑定企业微信。';
$string['wecom_status_unbound'] = '您的账号尚未绑定企业微信。';
$string['wecom_userid_label'] = '企业微信 UserID';
$string['wecom_currently_bound'] = '当前绑定的企业微信 ID：{$a}';
$string['wecom_not_bound'] = '尚未绑定企业微信';
$string['manage_binding'] = '管理绑定';
$string['error_fetching_wecom_userid'] = '从代码获取企业微信 UserID 时出错。';
$string['error_fetching_wecom_user_details'] = '从企业微信 API 获取用户详情时出错。';
$string['error_creating_user'] = '自动创建 Moodle 账号失败。';
$string['error_wecom_already_bound'] = '该企业微信账号已绑定到另一个 Moodle 账号。';
