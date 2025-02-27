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
 * Data provider tests.
 *
 * @package    block_assignment_review
 * @category   test
 * @copyright  2018 Church of England
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_assignment_review;

use context_course;
use context_module;
use context_system;
use core_privacy\tests\provider_testcase;
use core_privacy\local\metadata\collection;
use core_privacy\local\metadata\types\subsystem_link;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\transform;
use core_privacy\local\request\writer;
use block_assignment_review\privacy\provider;
use stdClass;

defined('MOODLE_INTERNAL') || die();
global $CFG, $DB;

require_once($CFG->dirroot . '/comment/lib.php');

/**
 * Data provider testcase class.
 *
 * @package    block_assignment_review
 * @category   test
 * @copyright  2018 Church of England
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class privacy_test extends provider_testcase {

    /**
     * Convenience method for creating comments.
     *
     * Note, you must set the current user prior to calling this.
     *
     * @param context $context The context.
     * @param string $message The message.
     * @return object The comment.
     */
    protected function create_comment($context, $message) {
        global $CFG, $SITE;
        require_once($CFG->dirroot . '/comment/lib.php');

        $course = null;
        $coursecontext = $context->get_course_context(false);
        if (!$coursecontext) {
            $course = $SITE;
        } else {
            $course = get_course($coursecontext->instanceid);
        }

        $args = new stdClass();
        $args->context = $context;
        $args->course = $course;
        $args->area = 'block_assignment_review_comments';
        $args->itemid = 0;
        $args->component = 'block_assignment_review';
        $args->linktext  = get_string('showcomments');
        $args->notoggle  = true;
        $args->autostart = true;
        $args->displaycancel = false;

        $comment = new \comment($args);
        $comment->set_post_permission(true);
        $comment->set_fullwidth();
        $comment->add($message);

        return $comment;
    }

    /**
     * Test get meta data.
     */
    public function test_get_metadata(): void {
        $collection = new collection('block_assignment_review');
        $collection = provider::get_metadata($collection);
        $items = $collection->get_collection();
        $this->assertCount(1, $items);
        $this->assertTrue($items[0] instanceof subsystem_link);
        $this->assertEquals('core_comment', $items[0]->get_name());
    }

    /**
     * Test get content for user ID.
     */
    public function test_get_contexts_for_userid(): void {
        $this->resetAfterTest();
        $dg = $this->getDataGenerator();

        $c1 = $dg->create_course();
        $c2 = $dg->create_course();
        $cm1a = $dg->create_module('page', ['course' => $c1]);
        $cm1b = $dg->create_module('page', ['course' => $c1]);
        $cm2a = $dg->create_module('page', ['course' => $c2]);

        $sysctx = context_system::instance();
        $c1ctx = context_course::instance($c1->id);
        $c2ctx = context_course::instance($c2->id);
        $cm1actx = context_module::instance($cm1a->cmid);
        $cm1bctx = context_module::instance($cm1b->cmid);
        $cm2actx = context_module::instance($cm2a->cmid);

        $u1 = $dg->create_user();
        $u2 = $dg->create_user();
        $u3 = $dg->create_user();

        $this->setUser($u1);
        $this->create_comment($sysctx, 'Test sys');
        $this->create_comment($c1ctx, 'Test course 1 - a');
        $this->create_comment($c1ctx, 'Test course 1 - b');
        $this->create_comment($cm1bctx, 'Test cm 1b');
        $this->create_comment($cm2actx, 'Test cm 2');

        $this->setUser($u2);
        $this->create_comment($c1ctx, 'Test2 course 1');
        $this->create_comment($c2ctx, 'Test2 course 2');
        $this->create_comment($cm1actx, 'Test2 cm 1b');

        $contextlist = provider::get_contexts_for_userid($u1->id);
        $this->assert_contextlist_equals($contextlist, [$sysctx, $c1ctx, $cm1bctx, $cm2actx]);

        $contextlist = provider::get_contexts_for_userid($u2->id);
        $this->assert_contextlist_equals($contextlist, [$c1ctx, $c2ctx, $cm1actx]);

        $contextlist = provider::get_contexts_for_userid($u3->id);
        $this->assert_contextlist_equals($contextlist, []);
    }

    public function test_export_data_for_user() {
        $this->resetAfterTest();
        $dg = $this->getDataGenerator();

        $c1 = $dg->create_course();
        $c2 = $dg->create_course();
        $cm1a = $dg->create_module('page', ['course' => $c1]);
        $cm1b = $dg->create_module('page', ['course' => $c1]);
        $cm2a = $dg->create_module('page', ['course' => $c2]);

        $sysctx = context_system::instance();
        $c1ctx = context_course::instance($c1->id);
        $c2ctx = context_course::instance($c2->id);
        $cm1actx = context_module::instance($cm1a->cmid);
        $cm1bctx = context_module::instance($cm1b->cmid);
        $cm2actx = context_module::instance($cm2a->cmid);

        $u1 = $dg->create_user();
        $u2 = $dg->create_user();
        $u3 = $dg->create_user();

        $this->setUser($u1);
        $this->create_comment($sysctx, 'Test sys');
        $this->create_comment($c1ctx, 'Test course 1 - a');
        $this->create_comment($c1ctx, 'Test course 1 - b');
        $this->create_comment($cm1bctx, 'Test cm 1b');
        $this->create_comment($cm2actx, 'Test cm 2');

        $this->setUser($u2);
        $this->create_comment($c1ctx, 'Test2 course 1');
        $this->create_comment($c2ctx, 'Test2 course 2');
        $this->create_comment($cm1actx, 'Test2 cm 1a');

        $getmessage = function($comment) {
            return strip_tags($comment->content);
        };

        $path = [get_string('pluginname', 'block_assignment_review'), get_string('commentsubcontext', 'core_comment')];
        writer::reset();
        $this->setUser($u1);
        provider::export_user_data(new approved_contextlist($u1, 'block_assignment_review', [$c1ctx->id, $cm2actx->id]));
        $data = writer::with_context($c1ctx)->get_data($path);
        $this->assertCount(2, $data->comments);
        $this->assertContains('Test course 1 - a', array_map($getmessage, $data->comments));
        $this->assertContains('Test course 1 - b', array_map($getmessage, $data->comments));
        $data = writer::with_context($cm2actx)->get_data($path);
        $this->assertCount(1, $data->comments);
        $this->assertEquals('Test cm 2', $data->comments[0]->content);

        $data = writer::with_context($sysctx)->get_data($path);
        $this->assertEmpty($data);
        $data = writer::with_context($cm1bctx)->get_data($path);
        $this->assertEmpty($data);

        writer::reset();
        $this->setUser($u2);
        provider::export_user_data(new approved_contextlist($u2, 'block_assignment_review', [$sysctx->id, $c1ctx->id,
            $cm1actx->id, $cm1bctx->id, $c2ctx->id, $cm2actx->id]));
        $data = writer::with_context($c1ctx)->get_data($path);
        $this->assertCount(1, $data->comments);
        $this->assertEquals('Test2 course 1', $data->comments[0]->content);
        $data = writer::with_context($c2ctx)->get_data($path);
        $this->assertCount(1, $data->comments);
        $this->assertEquals('Test2 course 2', $data->comments[0]->content);
        $data = writer::with_context($cm1actx)->get_data($path);
        $this->assertCount(1, $data->comments);
        $this->assertEquals('Test2 cm 1a', $data->comments[0]->content);

        $data = writer::with_context($sysctx)->get_data($path);
        $this->assertEmpty($data);
        $data = writer::with_context($cm1bctx)->get_data($path);
        $this->assertEmpty($data);
        $data = writer::with_context($cm2actx)->get_data($path);
        $this->assertEmpty($data);

        writer::reset();
        $this->setUser($u3);
        provider::export_user_data(new approved_contextlist($u3, 'block_assignment_review', [$sysctx->id, $c1ctx->id,
            $cm1actx->id, $cm1bctx->id, $c2ctx->id, $cm2actx->id]));
        $data = writer::with_context($sysctx)->get_data($path);
        $this->assertEmpty($data);
        $data = writer::with_context($c1ctx)->get_data($path);
        $this->assertEmpty($data);
        $data = writer::with_context($c2ctx)->get_data($path);
        $this->assertEmpty($data);
        $data = writer::with_context($cm1actx)->get_data($path);
        $this->assertEmpty($data);
        $data = writer::with_context($cm1bctx)->get_data($path);
        $this->assertEmpty($data);
        $data = writer::with_context($cm2actx)->get_data($path);
        $this->assertEmpty($data);
    }

    public function test_delete_data_for_user(): void {
        global $DB;
        $this->resetAfterTest();
        $dg = $this->getDataGenerator();

        $c1 = $dg->create_course();
        $c2 = $dg->create_course();
        $cm1a = $dg->create_module('page', ['course' => $c1]);
        $cm1b = $dg->create_module('page', ['course' => $c1]);
        $cm2a = $dg->create_module('page', ['course' => $c2]);

        $sysctx = context_system::instance();
        $c1ctx = context_course::instance($c1->id);
        $c2ctx = context_course::instance($c2->id);
        $cm1actx = context_module::instance($cm1a->cmid);
        $cm1bctx = context_module::instance($cm1b->cmid);
        $cm2actx = context_module::instance($cm2a->cmid);

        $u1 = $dg->create_user();
        $u2 = $dg->create_user();
        $u3 = $dg->create_user();

        $this->setUser($u1);
        $this->create_comment($sysctx, 'Test sys');
        $this->create_comment($c1ctx, 'Test course 1 - a');
        $this->create_comment($c1ctx, 'Test course 1 - b');
        $this->create_comment($cm1bctx, 'Test cm 1b');
        $this->create_comment($cm2actx, 'Test cm 2');

        $this->setUser($u2);
        $this->create_comment($c1ctx, 'Test2 course 1');
        $this->create_comment($c2ctx, 'Test2 course 2');
        $this->create_comment($cm1actx, 'Test2 cm 1a');

        $this->assertEquals(5, $DB->count_records('comments', ['userid' => $u1->id]));
        $this->assertEquals(1, $DB->count_records('comments', ['userid' => $u1->id, 'contextid' => $sysctx->id]));
        $this->assertEquals(2, $DB->count_records('comments', ['userid' => $u1->id, 'contextid' => $c1ctx->id]));
        $this->assertEquals(3, $DB->count_records('comments', ['userid' => $u2->id]));

        provider::delete_data_for_user(new approved_contextlist($u1, 'block_assignment_review', [$sysctx->id, $c1ctx->id]));

        $this->assertEquals(2, $DB->count_records('comments', ['userid' => $u1->id]));
        $this->assertEquals(0, $DB->count_records('comments', ['userid' => $u1->id, 'contextid' => $sysctx->id]));
        $this->assertEquals(0, $DB->count_records('comments', ['userid' => $u1->id, 'contextid' => $c1ctx->id]));
        $this->assertEquals(3, $DB->count_records('comments', ['userid' => $u2->id]));
    }

    public function test_delete_data_for_all_users_in_context(): void {
        global $DB;
        $this->resetAfterTest();
        $dg = $this->getDataGenerator();

        $c1 = $dg->create_course();
        $c2 = $dg->create_course();
        $cm1a = $dg->create_module('page', ['course' => $c1]);
        $cm1b = $dg->create_module('page', ['course' => $c1]);
        $cm2a = $dg->create_module('page', ['course' => $c2]);

        $sysctx = context_system::instance();
        $c1ctx = context_course::instance($c1->id);
        $c2ctx = context_course::instance($c2->id);
        $cm1actx = context_module::instance($cm1a->cmid);
        $cm1bctx = context_module::instance($cm1b->cmid);
        $cm2actx = context_module::instance($cm2a->cmid);

        $u1 = $dg->create_user();
        $u2 = $dg->create_user();
        $u3 = $dg->create_user();

        $this->setUser($u1);
        $this->create_comment($sysctx, 'Test sys');
        $this->create_comment($c1ctx, 'Test course 1 - a');
        $this->create_comment($c1ctx, 'Test course 1 - b');
        $this->create_comment($cm1bctx, 'Test cm 1b');
        $this->create_comment($cm2actx, 'Test cm 2');

        $this->setUser($u2);
        $this->create_comment($c1ctx, 'Test2 course 1');
        $this->create_comment($c2ctx, 'Test2 course 2');
        $this->create_comment($cm1actx, 'Test2 cm 1a');

        $this->assertEquals(5, $DB->count_records('comments', ['userid' => $u1->id]));
        $this->assertEquals(2, $DB->count_records('comments', ['userid' => $u1->id, 'contextid' => $c1ctx->id]));
        $this->assertEquals(1, $DB->count_records('comments', ['userid' => $u1->id, 'contextid' => $cm1bctx->id]));
        $this->assertEquals(3, $DB->count_records('comments', ['userid' => $u2->id]));
        $this->assertEquals(1, $DB->count_records('comments', ['userid' => $u2->id, 'contextid' => $c1ctx->id]));
        $this->assertEquals(1, $DB->count_records('comments', ['userid' => $u2->id, 'contextid' => $c2ctx->id]));

        provider::delete_data_for_all_users_in_context($c1ctx);

        $this->assertEquals(3, $DB->count_records('comments', ['userid' => $u1->id]));
        $this->assertEquals(0, $DB->count_records('comments', ['userid' => $u1->id, 'contextid' => $c1ctx->id]));
        $this->assertEquals(1, $DB->count_records('comments', ['userid' => $u1->id, 'contextid' => $cm1bctx->id]));
        $this->assertEquals(2, $DB->count_records('comments', ['userid' => $u2->id]));
        $this->assertEquals(0, $DB->count_records('comments', ['userid' => $u2->id, 'contextid' => $c1ctx->id]));
        $this->assertEquals(1, $DB->count_records('comments', ['userid' => $u2->id, 'contextid' => $c2ctx->id]));
    }

    /**
     * Assert the content of a context list.
     *
     * @param contextlist $contextlist The collection.
     * @param array $expected List of expected contexts or IDs.
     */
    protected function assert_contextlist_equals($contextlist, array $expected) {
        $expectedids = array_map(function($context) {
            if (is_object($context)) {
                return $context->id;
            }
            return $context;
        }, $expected);
        $contextids = array_map('intval', $contextlist->get_contextids());
        sort($contextids);
        sort($expectedids);
        $this->assertEquals($expectedids, $contextids);
    }
}
