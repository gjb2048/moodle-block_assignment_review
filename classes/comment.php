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
 * Assignment Review block comment api.
 *
 * @package    block_assignment_review
 * @copyright  2016 onwards Church of England {@link http://www.churchofengland.org/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/comment/lib.php');

/**
 * Comment is helper class to add/delete comments anywhere in moodle
 *
 * @package   block_assignment_review
 * @copyright  2016 onwards Church of England {@link http://www.churchofengland.org/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 */
class assignment_review_comment extends comment {

    /**
     * Returns true if the user can delete this comment
     * @param int $commentid
     * @return bool
     */
    public function can_delete($commentid) {
        $this->validate(['commentid' => $commentid]);
        return has_capability('block/assignment_review:delete', $this->get_context());
    }
}
