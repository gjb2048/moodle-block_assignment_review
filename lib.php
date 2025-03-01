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
 * Assignment Review lib
 *
 * @package    block_assignment_review
 * @copyright  2016 onwards Church of England {@link http://www.churchofengland.org/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 */

define('DEFAULT_NUMBER_OF_MARKERS', 4);

/**
 * Serve the block files.
 *
 * @param $course
 * @param $cm
 * @param $context
 * @param $filearea
 * @param $args
 * @param $forcedownload
 * @param array $options
 * @return bool
 */
function block_assignment_review_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {

    // Make sure the filearea starts with block_assignment_review.
    if ( strpos($filearea, 'block_assignment_review') !== 0 ) {
        return false;
    }

    require_capability('block/assignment_review:view', context_course::instance($course->id));

    /* Change the context to system for block_assignment_review now that we check the user has view permission on the current guide.
        if ($filearea == 'block_assignment_review') {
            $context = context_system::instance();
        } */

    // Leave this line out if you set the itemid to null in make_pluginfile_url (set $itemid to 0 instead).
    $itemid = array_shift($args); // The first item in the $args array.

    // Extract the filename / filepath from the $args array.
    $filename = array_pop($args); // The last item in the $args array.
    if (!$args) {
        $filepath = '/'; // If $args is empty => the path is '/'.
    } else {
        $filepath = '/' . implode('/', $args) . '/'; // If $args contains elements of the filepath.
    }

    // Retrieve the file from the Files API.
    $fs = get_file_storage();

    $file = $fs->get_file($context->id, 'block_assignment_review', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; // The file does not exist.
    }

    // We can now send the file back to the browser - in this case with a cache lifetime of 1 day and no filtering.
    // From Moodle 2.3, use send_stored_file instead.
    send_stored_file($file);
}

/**
 * Validate comment parameter before perform other comments actions
 *
 * @package  block_comments
 * @category comment
 *
 * @param stdClass $comment_param {
 *              context  => context the context object
 *              courseid => int course id
 *              cm       => stdClass course module object
 *              commentarea => string comment area
 *              itemid      => int itemid
 * }
 * @return boolean
 */
function block_assignment_review_comment_validate($commentparam) {
    if ($commentparam->commentarea != 'block_assignment_review_comments') {
        throw new comment_exception('invalidcommentarea');
    }
    if ($commentparam->itemid != 0) {
        throw new comment_exception('invalidcommentitemid');
    }
    return true;
}

/**
 * Running addtional permission check on plugins
 *
 * @package  block_comments
 * @category comment
 *
 * @param stdClass $args
 * @return array
 */
function block_assignment_review_comment_permissions($args) {
    return ['post' => true, 'view' => true];
}

/**
 * Validate comment data before displaying comments
 *
 * @package  block_comments
 * @category comment
 *
 * @param stdClass $comment
 * @param stdClass $args
 * @return boolean
 */
function block_assignment_review_comment_display($comments, $args) {
    if ($args->commentarea != 'block_assignment_review_comments') {
        throw new comment_exception('invalidcommentarea');
    }
    if ($args->itemid != 0) {
        throw new comment_exception('invalidcommentitemid');
    }
    return $comments;
}

