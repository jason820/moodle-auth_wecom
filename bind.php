<?php
/**
 * Page for binding WeCom to a Moodle account.
 *
 * @package    auth_wecom
 * @copyright  2026 onwards Jason
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->libdir . '/authlib.php');
require_once($CFG->dirroot . '/auth/wecom/auth.php');

$wecom_userid = optional_param('wecom_userid', '', PARAM_RAW); // Provided from the login redirect.

if (isloggedin() && !isguestuser()) {
    redirect(new moodle_url('/'));
}

if (empty($wecom_userid)) {
    // If WeCom UserID is missing, redirect to login page.
    throw new moodle_exception('missing_wecom_userid', 'auth_wecom', new moodle_url('/login/index.php'));
}

$PAGE->set_url(new moodle_url('/auth/wecom/bind.php', ['wecom_userid' => $wecom_userid]));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('login');
$PAGE->set_title(get_string('wecom_bind_title', 'auth_wecom'));
$PAGE->set_heading(get_string('wecom_bind_title', 'auth_wecom'));

$mform = new \auth_wecom\bind_form(null, ['wecom_userid' => $wecom_userid]);
$mform->set_data(['wecom_userid' => $wecom_userid]);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/login/index.php'));
} else if ($data = $mform->get_data()) {
    // 1. Verify Moodle account (Manual auth only).
    $user = authenticate_user_login($data->username, $data->password);

    if ($user && $user->auth === 'manual') {
        // 2. Success! Create or update the exclusive mapping.
        $auth = get_auth_plugin('wecom');
        $auth->update_binding($user->id, $data->wecom_userid);

        // 3. Complete the login.
        complete_user_login($user);

        // 4. Redirect to homepage.
        redirect(new moodle_url('/'), get_string('wecom_bind_success', 'auth_wecom'), null, \core\output\notification::NOTIFY_SUCCESS);
    } else {
        // Verification failed.
        \core\notification::error(get_string('wecom_bind_fail', 'auth_wecom'));
    }
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
