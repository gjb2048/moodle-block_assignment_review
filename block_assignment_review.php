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
 * Block assignment_review is defined here.
 *
 * @package    block_assignment_review
 * @copyright  2016 onwards Church of England {@link http://www.churchofengland.org/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/assignment_review/classes/comment.php');
require_once($CFG->dirroot . '/blocks/assignment_review/lib.php');

/**
 * assignment_review block.
 *
 * @package    block_assignment_review
 * @copyright  2016 onwards Church of England {@link http://www.churchofengland.org/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 */
class block_assignment_review extends block_base {

    /**
     * Initializes class member variables.
     */
    public function init() {

         $this->title = get_string('pluginname', 'block_assignment_review');

    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {
        global $COURSE, $CFG;

        /* Edge case - somehow config is not set at this moment for getting title
           when no config has been recorded in the block settings for the default title. */
        if (empty($this->config)) {
            $this->title = $CFG->blockassignmentblockname;
        } else {
            if (strpos($this->page->url, '/mod/assign/') !== false) {
                $this->title = $this->config->titleinassign;
            } else {
                $this->title = $this->config->title;
            }
        }

        if (!has_capability('block/assignment_review:view', $this->page->context)) {
            return $this->content;
        }

        if ($this->content !== null) {
            return $this->content;
        }

        if (!$CFG->usecomments) {
            $this->content = new stdClass();
            $this->content->text = '';
            if ($this->page->user_is_editing()) {
                $this->content->text = get_string('disabledcomments');
            }
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = [];
        $this->content->icons = [];
        $this->content->footer = '';

        if (empty($this->config->description['text'])) {
            $desc = '';
            // If ever the block settings has never been saved and some default desc exist, then display it.
            if (!isset($this->config->description) && !empty($CFG->blockassignmentblockdesc)) {
                if (strpos($this->page->url, '/mod/assign/') !== false) {
                    $desc = $CFG->blockassignmentblockdescinassign;
                } else {
                    $desc = $CFG->blockassignmentblockdesc;
                }
            }
        } else {
            if (strpos($this->page->url, '/mod/assign/') !== false) {
                $desc = $this->config->descriptioninassign['text'];
            } else {
                $desc = $this->config->description['text'];
            }
        }

        // Description.
        $this->content->text = $desc;

        // Markers.
        $usermarker = get_user_preferences('block_assignment_review_marker');
        if (empty($usermarker)) {
            $usermarker = '';
        }
        if (empty($CFG->blockassignmentmarkertotal)) {
            $markertotal = DEFAULT_NUMBER_OF_MARKERS;
        } else {
            $markertotal = $CFG->blockassignmentmarkertotal;
        }
        $this->content->text .= '<form id="block_assignment_review_markers" class="block_assignment_review_markers" action="">';
        for ($i = 0; $i < $markertotal; $i++) {

            $configname = 'blockassignmentmarkertext' . $i;
            $configshortname = 'blockassignmentmarkershortname' . $i;
            if (!empty($CFG->{$configname}) && !empty($CFG->{$configshortname})) {

                /* $selected = '';
                   if (empty($selected) &&
                   ($usermarker === $CFG->{$configshortname} || $i === $markertotal - 1)) {
                     $selected = "checked=\"checked\"";
                   } */

                $this->content->text .=
                    '<input type="radio" name="blockassignmentmarker" value="'.$CFG->{$configshortname}.'" > ' .
                        $CFG->{$configname} . '</input><br/>';
            }
        }
        $this->content->text .= '</form>';

        // Comments.
        $this->page->requires->strings_for_js(
            [
            'addcomment',
            'comments',
            'commentscount',
            'commentsrequirelogin',
            // If needed 'deletecomment',?
            ],
            'moodle'
        );
        $args = new stdClass;
        $args->context   = $this->page->context;
        $args->course    = $COURSE;
        $args->area      = 'block_assignment_review_comments';
        $args->itemid    = 0;
        $args->component = 'block_assignment_review';
        $args->linktext  = get_string('showcomments');
        $args->notoggle  = true;
        $args->autostart = true;
        $args->displaycancel = false;
        $comment = new assignment_review_comment($args);
        $comment->set_view_permission(true);
        $comment->set_fullwidth();
        $this->content->text .= $comment->output(true);

        // Issues.
        if (empty($CFG->blockassignmentissuetotal)) {
            $issuetotal = DEFAULT_NUMBER_OF_MARKERS;
        } else {
            $issuetotal = $CFG->blockassignmentissuetotal;
        }
        $this->content->text .= '<form id="block_assignment_review_issues" class="block_assignment_review_issues" action="">';
        for ($i = 0; $i < $issuetotal; $i++) {
            $configname = 'blockassignmentissuetext' . $i;
            $configshortname = 'blockassignmentissueshortname' . $i;
            if (!empty($CFG->{$configname})) {
                $this->content->text .= '<input type="checkbox" name="blockassignmentissues" value="' .
                    $CFG->{$configshortname}.'"> ' . $CFG->{$configname} . '</input><br/>';
            }
        }
        $this->content->text .= '</form>';

        // Load jquery.
        $this->page->requires->jquery();
        $this->page->requires->js('/blocks/assignment_review/script.js');

        return $this->content;
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediatly after init().
     */
    public function specialization() {
        global $CFG;

        // Load user defined title and make sure it's never empty.
        // config_title is defined in the edit_form.php file.
        if (empty($this->config->title)) {
            if (empty($CFG->blockassignmentblockname)) {
                $this->title = get_string('pluginname', 'block_assignment_review');
            } else {
                // If no title but a default is set in the administration page.
                $this->title = $CFG->blockassignmentblockname;
            }
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * Enables global configuration of the block in settings.php.
     *
     * @return bool True if the global configuration is enabled.
     */
    public function has_config() {
        return true;
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats() {
        return ['all' => true];
    }
}
