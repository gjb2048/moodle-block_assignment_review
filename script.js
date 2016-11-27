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
 * JS script.
 *
 * @package    block_assignment_review
 * @copyright  2016 onwards Church of England {@link http://www.churchofengland.org/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jerome Mouneyrac <jerome@mouneyrac.com>
 */

window.onload = function() {
    $('.comment-area .fd > a').css('visibility', 'hidden');
    $('.comment-area .fd').append(
        "<a id=\"block_assignment_review_savecomment\" href=\"#\">Save comment</a>"
    );
    $('#block_assignment_review_savecomment').click(
        function() {
            var text = $('.comment-area .db > textarea').val();



            // Retrieve the selected choice of marker.
            var selectedmarker = $('input[name=blockassignmentmarker]:checked').val() + ' | ';

            //Retrieve the issues.
            var selectedissues = '';
            $('input[name="blockassignmentissues"]:checked').each(function() {
                selectedissues = this.value + ' | ' + selectedissues;
            });

            // Add the issue
            $('.comment-area .db > textarea').val(selectedmarker + selectedissues + text );



            $('.comment-area .fd > a').first().css('visibility', 'visible');
            $('#block_assignment_review_savecomment').css('visibility', 'hidden');



            $('.comment-area .fd > a')[0].click();

            $('.comment-area .fd > a').first().css('visibility', 'hidden');
            $('#block_assignment_review_savecomment').css('visibility', 'visible');
        }
    );
}