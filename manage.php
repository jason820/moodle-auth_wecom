<?php
/**
 * Page for managing WeCom binding (Self and Admin).
 *
 * @package    auth_wecom
 * @copyright  2026 onwards Jason
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->libdir . '/authlib.php');
require_once($CFG->dirroot . '/auth/wecom/auth.php');

$userid = optional_param('userid', 0, PARAM_INT); // 0 means current user.
$action = optional_param('action', '', PARAM_ALPHA);

require_login();

if (empty($userid)) {
    $userid = $USER->id;
}

$context = context_user::instance($userid);
if ($userid != $USER->id) {
    // If managing another user, must be admin or have proper capability.
    require_capability('moodle/user:update', $context);
}

$PAGE->set_url(new moodle_url('/auth/wecom/manage.php', ['userid' => $userid]));
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'auth_wecom'));
$PAGE->set_heading(get_string('pluginname', 'auth_wecom'));

/** @var auth_plugin_wecom $auth */
$auth = get_auth_plugin('wecom');

// Handle unbind action.
if ($action === 'unbind' && confirm_sesskey()) {
    $auth->unbind_user($userid);
    redirect($PAGE->url, get_string('wecom_unbind', 'auth_wecom'), null, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();

$mapping = $DB->get_record('auth_wecom_map', ['userid' => $userid]);

if ($mapping) {
    echo $OUTPUT->notification(get_string('wecom_status_bound', 'auth_wecom'), 'success');
    echo $OUTPUT->box_start();
    echo html_writer::tag('p', get_string('wecom_userid_label', 'auth_wecom') . ': ' . html_writer::tag('b', $mapping->wecom_userid));
    $unbindurl = new moodle_url($PAGE->url, ['action' => 'unbind', 'sesskey' => sesskey()]);
    echo $OUTPUT->single_button($unbindurl, get_string('wecom_unbind', 'auth_wecom'), 'post', ['class' => 'btn-danger']);
    echo $OUTPUT->box_end();
} else {
    echo $OUTPUT->notification(get_string('wecom_status_unbound', 'auth_wecom'), 'info');
    if ($userid == $USER->id) {
        $loginurl = new moodle_url('/auth/wecom/login.php');
        echo $OUTPUT->single_button($loginurl, get_string('wecom_bind_button', 'auth_wecom'));
    }
}

echo $OUTPUT->footer();
