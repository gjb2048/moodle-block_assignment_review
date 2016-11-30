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
 * Plugin administration pages are defined here.
 *
 * @package    block_assignment_review
 * @copyright  2016 onwards Church of England {@link http://www.churchofengland.org/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/assignment_review/lib.php');

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_heading('blockassignmentmarkers',
        new lang_string('blockassignmentmarkers', 'block_assignment_review'),
        new lang_string('blockassignmentmarkersdesc', 'block_assignment_review'), ''));

    $settings->add(new admin_setting_configtext('blockassignmentmarkertotal', get_string('markertotal', 'block_assignment_review'),
        get_string('markertotaldesc', 'block_assignment_review'), DEFAULT_NUMBER_OF_MARKERS, PARAM_INT));

    if (empty($CFG->blockassignmentmarkertotal)) {
        $markertotal = DEFAULT_NUMBER_OF_MARKERS;
    } else {
        $markertotal = $CFG->blockassignmentmarkertotal;
    }

    for ($i=0; $i < $markertotal; $i++) {
        $settings->add(new admin_setting_configtext('blockassignmentmarkershortname'.$i, get_string('markershortname', 'block_assignment_review'),
            get_string('markershortnamedesc', 'block_assignment_review'), '', PARAM_TEXT));
        $settings->add(new admin_setting_configtext('blockassignmentmarkertext'.$i, get_string('markertext', 'block_assignment_review'),
            get_string('markertextdesc', 'block_assignment_review'), '', PARAM_TEXT));

        $settings->add(new admin_setting_heading('blockassignmentmarker'.$i, '', '<br/>', ''));
    }

    $settings->add(new admin_setting_heading('blockassignmentissues',
        new lang_string('blockassignmentissues', 'block_assignment_review'),
        new lang_string('blockassignmentissuesdesc', 'block_assignment_review'), ''));

    $settings->add(new admin_setting_configtext('blockassignmentissuetotal', get_string('issuetotal', 'block_assignment_review'),
        get_string('issuetotaldesc', 'block_assignment_review'), DEFAULT_NUMBER_OF_MARKERS, PARAM_INT));

    if (empty($CFG->blockassignmentissuetotal)) {
        $issuetotal = DEFAULT_NUMBER_OF_MARKERS;
    } else {
        $issuetotal = $CFG->blockassignmentissuetotal;
    }

    for ($i=0; $i < $issuetotal; $i++) {
        $settings->add(new admin_setting_configtext('blockassignmentissueshortname'.$i, get_string('issueshortname', 'block_assignment_review'),
            get_string('issueshortnamedesc', 'block_assignment_review'), '', PARAM_TEXT));
        $settings->add(new admin_setting_configtext('blockassignmentissuetext'.$i, get_string('issuetext', 'block_assignment_review'),
            get_string('issuetextdesc', 'block_assignment_review'), '', PARAM_TEXT));

        $settings->add(new admin_setting_heading('blockassignmentissue'.$i, '', '<br/>', ''));
    }

    $settings->add(new admin_setting_heading('blockassignmentdefaulttexts',
        new lang_string('blockassignmentdefaulttexts', 'block_assignment_review'), '', ''));

    $settings->add(new admin_setting_configtext('blockassignmentblockname', get_string('blockname', 'block_assignment_review'),
        '', '', PARAM_TEXT));

    $settings->add(new admin_setting_confightmleditor('blockassignmentblockdesc', get_string('blockdesc', 'block_assignment_review'),
        '', '', PARAM_RAW));

}