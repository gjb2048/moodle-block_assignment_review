<?php
// This file is part of Assignment Review plugin for Moodle
//
// Assignment Review plugin for Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Assignment Review plugin for Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Assignment Review plugin for Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Form for editing assignment_review block instances.
 *
 * @package    block_assignment_review
 * @copyright  2016 onwards Church of England {@link http://www.churchofengland.org/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 */

/**
 * Form for editing block_assignment_review block instances.
 *
 * @package    block_assignment_review
 * @copyright  2016 onwards Church of England {@link http://www.churchofengland.org/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 */
class block_assignment_review_edit_form extends block_edit_form {

    /**
     * Extends the configuration form for block_assignment_review.
     */
    protected function specific_definition($mform) {
        global $CFG;

        // Section header title.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('configtitle', 'block_assignment_review'));
        $mform->setType('config_title', PARAM_TEXT);
        if (!empty($CFG->blockassignmentblockname)) {
            $mform->setDefault('config_title', $CFG->blockassignmentblockname);
        }

        $mform->addElement('editor', 'config_description',
            get_string('configdescription', 'block_assignment_review'), array('rows' => 4));
        $mform->setType('config_description', PARAM_RAW);
        if (!empty($CFG->blockassignmentblockdesc)) {
            $mform->setDefault('config_description', array('text' => $CFG->blockassignmentblockdesc, 'format' => FORMAT_HTML));
        }
        
        $mform->addElement('text', 'config_titleinassign', get_string('configtitleinassign', 'block_assignment_review'));
        $mform->setType('config_titleinassign', PARAM_TEXT);
        if (!empty($CFG->blockassignmentblocknameinassign)) {
            $mform->setDefault('config_titleinassign', $CFG->blockassignmentblocknameinassign);
        }

        $mform->addElement('editor', 'config_descriptioninassign',
            get_string('configdescriptioninassign', 'block_assignment_review'), array('rows' => 4));
        $mform->setType('config_descriptioninassign', PARAM_RAW);
        if (!empty($CFG->blockassignmentblockdescinassign)) {
            $mform->setDefault('config_descriptioninassign', array('text' => $CFG->blockassignmentblockdescinassign, 'format' => FORMAT_HTML));
        }

        // Please keep in mind that all elements defined here must start with 'config_'.

    }
}
