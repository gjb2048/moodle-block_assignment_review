<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Privacy API.
 *
 * @package    block_assignment_review
 * @copyright  2018 Church of England
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_assignment_review\privacy;

use context;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\approved_contextlist;
use core_comment\privacy\provider as comments_provider;

/**
 * Privacy API class.
 *
 * @package    block_assignment_review
 * @copyright  2018 Church of England
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\plugin\provider {

    use \core_privacy\local\legacy_polyfill;

    /**
     * Get the meta data.
     *
     * @param  collection $collection A list of information to add to.
     * @return collection Return the collection after adding to it.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->link_subsystem('core_comment', 'privacy:metadata:commentpurpose');
        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user to search.
     * @return contextlist $contextlist The contextlist containing the list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid($userid): contextlist {
        $sql = "SELECT DISTINCT c.contextid
                  FROM {comments} c
                 WHERE c.userid = :userid
                   AND c.component = :component";
        $params = [
            'userid' => $userid,
            'component' => 'block_assignment_review',
        ];

        $contextlist = new contextlist();
        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        $pluginname = get_string('pluginname', 'block_assignment_review');
        $contexts = $contextlist->get_contexts();
        foreach ($contexts as $context) {
            comments_provider::export_comments(
                $context,
                'block_assignment_review',
                'block_assignment_review_comments',
                0,
                [$pluginname],
                true
            );
        }
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param context $context The specific context to delete data for.
     */
    public static function delete_data_for_all_users_in_context(context $context) {
        comments_provider::delete_comments_for_all_users(
            $context,
            'block_assignment_review',
            'block_assignment_review_comments'
        );
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        comments_provider::delete_comments_for_user(
            $contextlist,
            'block_assignment_review',
            'block_assignment_review_comments'
        );
    }

}
