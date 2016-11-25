## Project context

### The user problems
Archbishops’ Council needs meet the needs in British Higher Education for a second marker or moderator to make comments on the marking of an assignment as a whole.. Often comments on how the external examiner marked the students, note on how to mark students.

The solutions
In order for the reviewers to post “global” comment about assignment, a new Moodle block will display threaded comments per assignment on assignment pages. The plugin should  also allow the insertion of key markers or text that can be picked up in database queries, for example using the Configurable Reports plugin. These markers can be into the comments or in additional field till the solution is simple to set up with the configurable report plugin.

### Project features 

This block would function in a way very similar to the existing comments block but with some added functionality which would be configurable at a site level. This additional functionality would comprise of the following, configured at site level
1) Ability to Name the Block
2) Ability to set text under the heading that can act as instructions on correct use.
3) Select buttons where one only must be selected to indicate the status the person holds in relation to the comment posted. Text is add to the top of the message based on which button is selected. The number of buttons, the titles of the buttons and the text to be added would be configured at a site level via plugin settings. Thus the following three buttons would be created by the following text in the config setting and generate corresponding text at the start of the message when saved. If no buttons are listed then none would be displayed or required.
 


4) Additional select buttons where one or more could be selected to indicate certain issues, eg “Important” or “Re-marking required”. These would again add text into the comment text when saved. (The idea is that this text can be searched for and comments displayed via Configurable Reports, but saving it as part of the comment removes any need to change how information is stored in the database eg adding extra fields, it is simply stored as part of the comment text). If no buttons are listed then none would be displayed.


The permissions must prevent student access to any comments, indeed they should not ever see this block. Other read, write delete permission could be set. As far as I can tell, the existing comments system could be used as is.## Installation ##
install the plugin in blocks/assignment_review Add the plugin on the front page


## Block setup ##
The goal of this block is to be displayed on all course main pages. 
In order to achieve this result you need to add the course on the front page,
edit it and select display this block through the entire site. Then go to a course page.
Edit the block and select display on course main pages only.

Turn editing on and edit the block with the cloack icon. You can there change the name of the plugin.

## Compatibility ##
The plugin has been developed for Moodle 2.8 / MySQL / PHP 5.5.

## License ##

Church of England

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <http://www.gnu.org/licenses/>.
