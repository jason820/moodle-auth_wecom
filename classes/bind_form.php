<?php
/**
 * Account binding form for WeCom.
 *
 * @package    auth_wecom
 * @copyright  2026 onwards Jason
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace auth_wecom;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Account binding form.
 */
class bind_form extends \moodleform {

    /**
     * Define the form.
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('header', 'bindheader', get_string('wecom_bind_title', 'auth_wecom'));
        $mform->addElement('static', 'description', '', get_string('wecom_bind_desc', 'auth_wecom'));

        $mform->addElement('text', 'username', get_string('username'));
        $mform->setType('username', PARAM_RAW);
        $mform->addRule('username', get_string('required'), 'required', null, 'client');

        $mform->addElement('password', 'password', get_string('password'));
        $mform->setType('password', PARAM_RAW);
        $mform->addRule('password', get_string('required'), 'required', null, 'client');

        $mform->addElement('hidden', 'wecom_userid');
        $mform->setType('wecom_userid', PARAM_RAW);

        $this->add_action_buttons(true, get_string('login'));
    }

    /**
     * Custom validation.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Basic check to ensure wecom_userid is present.
        if (empty($data['wecom_userid'])) {
            $errors['bindheader'] = 'Missing WeCom UserID. Please restart the login process.';
        }

        return $errors;
    }
}
