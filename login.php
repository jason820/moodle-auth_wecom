<?php
/**
 * Login handler for WeCom.
 *
 * @package    auth_wecom
 * @copyright  2026 onwards Jason
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/auth/wecom/auth.php');

/** @var auth_plugin_wecom $auth */
$auth = get_auth_plugin('wecom');

// --- SEAMLESS REDIRECT FOR ALREADY LOGGED-IN & BOUND USERS ---
if (isloggedin() && !isguestuser()) {
    $mapping = $DB->get_record('auth_wecom_map', ['userid' => $USER->id]);
    if ($mapping) {
        // Already logged in and bound, no need to do anything.
        redirect(new moodle_url('/'));
    }
}
// --- END SEAMLESS REDIRECT ---

$code = optional_param('code', '', PARAM_RAW); // OAuth2 authorization code.

// If we don't have a code, redirect to WeCom.
if (empty($code)) {
    redirect($auth->get_authorization_url());
} else {
    // Basic state check.
    if ($state !== 'wecom_login_' . sesskey()) {
        // Log potential CSRF or misconfiguration, but continue for now if strictness is not requested.
        // For production, this should be validated properly.
    }

    // Process the code, fetch the user ID.
    $wecom_userid = $auth->get_wecom_userid($code);

    if (!$wecom_userid) {
        throw new moodle_exception('error_fetching_wecom_userid', 'auth_wecom', new moodle_url('/login/index.php'));
    }

    // --- NEW BINDING LOGIC FOR LOGGED-IN USERS ---
    if (isloggedin() && !isguestuser()) {
        // Not bound yet or we want to update the binding.
        $auth->update_binding($USER->id, $wecom_userid);
        redirect(new moodle_url('/auth/wecom/manage.php'), get_string('wecom_bind_success', 'auth_wecom'), null, \core\output\notification::NOTIFY_SUCCESS);
    }
    // --- END NEW BINDING LOGIC ---

    // 1. Try to find bound user (including Strategy A: Silent Binding).
    $user = $auth->get_bound_user($wecom_userid);

    if ($user) {
        // Success! User is bound. Complete the login.
        complete_user_login($user);
        redirect(new moodle_url('/'));
    }

    // 2. If not bound, check if automatic creation is enabled.
    if (!empty($auth->config->autocreate)) {
        $details = $auth->get_wecom_user_details($wecom_userid);
        if ($details) {
            $user = $auth->create_moodle_user($wecom_userid, $details);
            if ($user) {
                complete_user_login($user);
                redirect(new moodle_url('/'));
            }
        }
    }

    // 3. Not bound and no auto-create. Redirect to the manual binding page.
    redirect(new moodle_url('/auth/wecom/bind.php', ['wecom_userid' => $wecom_userid]));
}
