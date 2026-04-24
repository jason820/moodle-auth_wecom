<?php
/**
 * Main authentication class for WeCom.
 *
 * @package    auth_wecom
 * @copyright  2026 onwards Jason
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/authlib.php');
require_once($CFG->libdir . '/filelib.php');

/**
 * WeCom authentication plugin.
 */
class auth_plugin_wecom extends auth_plugin_base {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'wecom';
        $this->config = get_config('auth_wecom');
    }

    /**
     * Users should not log in with a WeCom username/password in the traditional way.
     *
     * @param string $username The username
     * @param string $password The password
     * @return bool False
     */
    public function user_login($username, $password) {
        return false;
    }

    /**
     * Indicates whether this plugin can edit a user's profile.
     *
     * @return bool True
     */
    public function can_edit_profile() {
        return true;
    }

    /**
     * Indicates whether this plugin can change a user's password.
     *
     * @return bool False
     */
    public function can_change_password() {
        return false;
    }

    /**
     * Hook to add the WeCom login button to the login page.
     */
    public function loginpage_hook() {
        global $PAGE, $OUTPUT, $CFG;

        if (isloggedin() && !isguestuser()) {
            return;
        }

        // Check if we are in the WeCom App (Mobile/Silent flow).
        if ($this->is_wecom_browser()) {
            // To fulfill "silent" requirement, we redirect to login.php immediately if it's the first visit.
            if (!optional_param('noredirect', 0, PARAM_INT)) {
                redirect(new moodle_url('/auth/wecom/login.php'));
            }
        }

        // Inject the WeCom login button via JavaScript to avoid theme hacks.
        $loginurl = new moodle_url('/auth/wecom/login.php');
        $buttontext = get_string('wecom_login_button', 'auth_wecom');
        $iconurl = $CFG->wwwroot . '/auth/wecom/pix/icon.svg';

        $js = "
            var loginForm = document.getElementById('login');
            if (loginForm) {
                var btnDiv = document.createElement('div');
                btnDiv.className = 'mt-3 wecom-login-container';
                btnDiv.innerHTML = '<a href=\"{$loginurl}\" class=\"btn btn-outline-primary btn-block d-flex align-items-center justify-content-center\" style=\"gap: 0.5rem;\">' +
                                   '<img src=\"{$iconurl}\" width=\"24\" height=\"24\" alt=\"\" />' +
                                   '<span>{$buttontext}</span></a>';
                loginForm.appendChild(btnDiv);
            }
        ";
        $PAGE->requires->js_amd_inline($js);
    }

    /**
     * Detect if the request is from WeCom browser.
     *
     * @return bool
     */
    public function is_wecom_browser() {
        $useragent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        return (strpos($useragent, 'wxwork') !== false);
    }

    /**
     * Unbind WeCom for a user.
     *
     * @param int $userid
     * @return bool
     */
    public function unbind_user($userid) {
        global $DB;
        return $DB->delete_records('auth_wecom_map', ['userid' => $userid]);
    }

    /**
     * Get the WeCom authorization URL.
     *
     * @return string
     */
    public function get_authorization_url() {
        global $CFG;

        $corpid = $this->config->corpid;
        $redirect_uri = urlencode($CFG->wwwroot . '/auth/wecom/login.php');
        $agentid = $this->config->agentid;
        $state = 'wecom_login_' . sesskey();

        if ($this->is_wecom_browser()) {
            // Mobile silent login URL.
            return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$corpid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&agentid={$agentid}&state={$state}#wechat_redirect";
        } else {
            // PC QR Code login URL.
            return "https://open.work.weixin.qq.com/wwopen/sso/qrConnect?appid={$corpid}&agentid={$agentid}&redirect_uri={$redirect_uri}&state={$state}";
        }
    }

    /**
     * Get WeCom Access Token.
     *
     * @return string|false
     */
    protected function get_access_token() {
        $corpid = $this->config->corpid;
        $corpsecret = $this->config->corpsecret;

        $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid={$corpid}&corpsecret={$corpsecret}";
        
        $curl = new \curl();
        $response = $curl->get($url);
        $result = json_decode($response);

        if (isset($result->access_token)) {
            return $result->access_token;
        }

        debugging('WeCom access token error: ' . $response);
        return false;
    }

    /**
     * Get WeCom UserID from code.
     *
     * @param string $code
     * @return string|false
     */
    public function get_wecom_userid($code) {
        $token = $this->get_access_token();
        if (!$token) {
            return false;
        }

        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token={$token}&code={$code}";
        
        $curl = new \curl();
        $response = $curl->get($url);
        $result = json_decode($response);

        if (isset($result->UserId)) {
            return $result->UserId;
        }

        debugging('WeCom user info error: ' . $response);
        return false;
    }

    /**
     * Check if a WeCom UserID is bound to a Moodle user.
     * Includes Strategy A: Check for matching username if no mapping exists.
     *
     * @param string $wecom_userid
     * @return \stdClass|false Moodle user object
     */
    public function get_bound_user($wecom_userid) {
        global $DB;

        // 1. Check mapping table.
        $mapping = $DB->get_record('auth_wecom_map', ['wecom_userid' => $wecom_userid]);
        if ($mapping) {
            $user = $DB->get_record('user', ['id' => $mapping->userid, 'deleted' => 0]);
            if ($user && $user->suspended) {
                return false;
            }
            return $user;
        }

        // 2. Strategy A: Check username match (Silent Binding).
        // Moodle usernames are always lowercase.
        $moodle_username = core_text::strtolower($wecom_userid);
        $user = $DB->get_record('user', ['username' => $moodle_username, 'deleted' => 0]);
        if ($user) {
            if ($user->suspended) {
                return false;
            }
            // Auto-bind (using update_binding to handle existing relationships).
            $this->update_binding($user->id, $wecom_userid);
            return $user;
        }

        return false;
    }

    /**
     * Update or create a binding relationship.
     * Ensures one-to-one mapping by clearing previous associations for both IDs.
     *
     * @param int $userid Moodle user ID
     * @param string $wecom_userid WeCom user ID
     * @return bool
     */
    public function update_binding($userid, $wecom_userid) {
        global $DB;

        // 1. Remove any existing binding for this Moodle user.
        $DB->delete_records('auth_wecom_map', ['userid' => $userid]);

        // 2. Remove any existing binding for this WeCom UserID (the "modify" logic).
        $DB->delete_records('auth_wecom_map', ['wecom_userid' => $wecom_userid]);

        // 3. Create the new exclusive binding.
        return $DB->insert_record('auth_wecom_map', [
            'userid' => $userid,
            'wecom_userid' => $wecom_userid,
            'timecreated' => time()
        ]);
    }

    /**
     * Get WeCom User details from Address Book API.
     *
     * @param string $wecom_userid
     * @return \stdClass|false
     */
    public function get_wecom_user_details($wecom_userid) {
        $token = $this->get_access_token();
        if (!$token) {
            return false;
        }

        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token={$token}&userid={$wecom_userid}";
        
        $curl = new \curl();
        $response = $curl->get($url);
        $result = json_decode($response);

        if (isset($result->userid)) {
            return $result;
        }

        debugging('WeCom user details error: ' . $response);
        return false;
    }

    /**
     * Create a new Moodle user from WeCom details (Just-In-Time Provisioning).
     *
     * @param string $wecom_userid
     * @param \stdClass $wecom_details
     * @return \stdClass|false
     */
    public function create_moodle_user($wecom_userid, $wecom_details) {
        global $DB, $CFG;

        $user = new \stdClass();
        $user->username = core_text::strtolower($wecom_userid);
        $user->password = 'not-applicable'; // External auth.
        $user->firstname = $wecom_details->name ?? $wecom_userid;
        $user->lastname = ' '; // Moodle requires lastname.
        $user->email = $wecom_details->email ?? ($wecom_userid . '@example.com');
        $user->auth = 'wecom';
        $user->confirmed = 1;
        $user->mnethostid = $CFG->mnet_localhost_id;
        $user->lang = $CFG->lang;
        $user->timecreated = time();
        $user->timemodified = time();

        try {
            $userid = $DB->insert_record('user', $user);
            if ($userid) {
                // Create mapping using update_binding.
                $this->update_binding($userid, $wecom_userid);
                return $DB->get_record('user', ['id' => $userid]);
            }
        } catch (\Exception $e) {
            debugging('Error creating user: ' . $e->getMessage());
            return false;
        }

        return false;
    }
}
