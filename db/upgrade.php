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
 * Plugin upgrade steps are defined here.
 *
 * @package    block_assignment_review
 * @category   upgrade
 * @copyright  2016 onwards Church of England {@link http://www.churchofengland.org/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/upgradelib.php');

/**
 * Execute block_assignment_review upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_block_assignment_review_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    /* For further information please read the Upgrade API documentation:
       https://docs.moodle.org/dev/Upgrade_API

       You will also have to create the db/install.xml file by using the XMLDB Editor.
       Documentation for the XMLDB Editor can be found at:
       https://docs.moodle.org/dev/XMLDB_editor */

    if ($oldversion < 2018101902) {
        // Copy the default block title/desc in the default block title/desc for assignment pages.
        set_config('blockassignmentblocknameinassign', get_config('moodle', 'blockassignmentblockname'));
        set_config('blockassignmentblockdescinassign', get_config('moodle', 'blockassignmentblockdesc'));
    }

    return true;
}
