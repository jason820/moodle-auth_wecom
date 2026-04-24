<?php
/**
 * Library functions for auth_wecom.
 *
 * @package    auth_wecom
 * @copyright  2026 onwards Jason
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Extends the user profile navigation.
 *
 * @param \core_user\output\myprofile\tree $tree
 * @param stdClass $user
 * @param bool $iscurrentuser
 * @param stdClass $course
 */
function auth_wecom_myprofile_navigation(core_user\output\myprofile\tree $tree, $user, $iscurrentuser, $course) {
    global $DB, $CFG;

    // 获取绑定状态
    $mapping = $DB->get_record('auth_wecom_map', ['userid' => $user->id]);

    $status_text = '';
    $link_text = '';
    $manage_url = new moodle_url('/auth/wecom/manage.php', ['userid' => $user->id]);

    if ($mapping) {
        $status_text = '✅ ' . get_string('wecom_currently_bound', 'auth_wecom', $mapping->wecom_userid);
        $link_text = '[' . get_string('manage_binding', 'auth_wecom') . ']';
    } else {
        $status_text = '❌ ' . get_string('wecom_not_bound', 'auth_wecom');
        $link_text = '[' . get_string('wecom_bind_button', 'auth_wecom') . ']';
    }

    $status_text .= ' ' . html_writer::link($manage_url, $link_text);

    // 创建一个节点并添加到“联系方式 (contact)”或“其它 (miscellaneous)”类别中
    $node = new core_user\output\myprofile\node(
        'contact',
        'wecom_binding',
        get_string('pluginname', 'auth_wecom'),
        null,
        null,
        $status_text
    );
    
    $tree->add_node($node);
}
