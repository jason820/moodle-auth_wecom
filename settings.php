<?php
/**
 * Settings for the WeCom authentication plugin.
 *
 * @package    auth_wecom
 * @copyright  2026 onwards Jason
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    // CorpID.
    $settings->add(new admin_setting_configtext('auth_wecom/corpid',
        get_string('wecom_corpid', 'auth_wecom'),
        get_string('wecom_corpid_desc', 'auth_wecom'),
        '', PARAM_RAW));

    // CorpSecret.
    $settings->add(new admin_setting_configpasswordunmask('auth_wecom/corpsecret',
        get_string('wecom_corpsecret', 'auth_wecom'),
        get_string('wecom_corpsecret_desc', 'auth_wecom'),
        ''));

    // AgentID.
    $settings->add(new admin_setting_configtext('auth_wecom/agentid',
        get_string('wecom_agentid', 'auth_wecom'),
        get_string('wecom_agentid_desc', 'auth_wecom'),
        '', PARAM_INT,10));

    // Enable Automatic Creation.
    $settings->add(new admin_setting_configcheckbox('auth_wecom/autocreate',
        get_string('wecom_autocreate', 'auth_wecom'),
        get_string('wecom_autocreate_desc', 'auth_wecom'),
        0));
}
