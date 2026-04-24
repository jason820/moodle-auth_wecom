<?php
/**
 * Traditional Chinese language strings for the WeCom authentication plugin.
 *
 * @package    auth_wecom
 * @copyright  2026 onwards Jason
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = '企業微信認證';
$string['auth_wecomdescription'] = '本插件允許用戶使用企業微信進行單點登錄（SSO）及賬號綁定。';
$string['wecom_corpid'] = '企業 ID (CorpID)';
$string['wecom_corpsecret'] = '應用密鑰 (CorpSecret)';
$string['wecom_agentid'] = '應用 ID (AgentID)';
$string['wecom_corpid_desc'] = '您的企業微信企業 ID。';
$string['wecom_corpsecret_desc'] = '您的企業微信自建應用 Secret。';
$string['wecom_agentid_desc'] = '您的企業微信自建應用 AgentID。';
$string['wecom_autocreate'] = '啟用自動建帳';
$string['wecom_autocreate_desc'] = '啟用後，如果系統中不存在該企業微信 UserID 對應的帳號，將會自動為其創建新的 Moodle 帳號。';
$string['wecom_login_button'] = '使用企業微信登錄';
$string['wecom_bind_button'] = '綁定企業微信';
$string['wecom_bind_title'] = '綁定企業微信到 Moodle 帳號';
$string['wecom_bind_desc'] = '您的企業微信尚未綁定 Moodle 帳號。請使用您的 Moodle 帳號進行登錄以完成綁定。';
$string['wecom_bind_success'] = '成功綁定您的企業微信帳號。';
$string['wecom_bind_fail'] = '無效的 Moodle 用戶名或密碼。';
$string['wecom_unbind'] = '解除綁定';
$string['wecom_status_bound'] = '您的帳號已綁定企業微信。';
$string['wecom_status_unbound'] = '您的帳號尚未綁定企業微信。';
$string['wecom_userid_label'] = '企業微信 UserID';
$string['wecom_currently_bound'] = '目前綁定的企業微信 ID：{$a}';
$string['wecom_not_bound'] = '尚未綁定企業微信';
$string['manage_binding'] = '管理綁定';
$string['error_fetching_wecom_userid'] = '從代碼獲取企業微信 UserID 時出錯。';
$string['error_fetching_wecom_user_details'] = '從企業微信 API 獲取用戶詳情時出錯。';
$string['error_creating_user'] = '自動創建 Moodle 帳號失敗。';
$string['error_wecom_already_bound'] = '該企業微信帳號已綁定到另一個 Moodle 帳號。';
