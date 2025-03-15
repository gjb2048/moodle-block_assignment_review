User context
============

The user problems
-----------------
Archbishops’ Council needs meet the needs in British Higher Education for a second marker or moderator to make comments
on the marking of an assignment as a whole.  Often comments on how the external examiner marked the students, note on
how to markstudents.

The solutions
-------------
In order for the reviewers to post "global" comment about assignment, a new Moodle block will display threaded comments
per assignment on assignment pages.  The plugin should  also allow the insertion of key markers or text that can be picked
up in database queries, for example using the Configurable Reports plugin. These markers can be into the comments or in
additional field till the solution is simple to set up with the configurable report plugin.

Project features
----------------
This block would function in a way very similar to the existing comments block but with some added functionality which
would be configurable at a site level. This additional functionality would comprise of the following, configured at
site level.

1. Ability to Name the Block.
2. Ability to set text under the heading that can act as instructions on correct use.
3. Select buttons where one only must be selected to indicate the status the person holds in relation to the comment posted.
   Text is add to the top of the message based on which button is selected. The number of buttons, the titles of the buttons
   and the text to be added would be configured at a site level via plugin settings. Thus the following three buttons would
   be created by the following text in the config setting and generate corresponding text at the start of the message when
   saved.  If no buttons are listed then none would be displayed or required.
4. Additional select buttons where one or more could be selected to indicate certain issues, eg "Important" or "Re-marking
   required".  These would again add text into the comment text when saved.  The idea is that this text can be searched for
   and comments displayed via 'Configurable Reports', but saving it as part of the comment removes any need to change how
   information is stored in the database eg adding extra fields, it is simply stored as part of the comment text.  If no buttons
   are listed then none would be displayed.

The permissions must prevent student access to any comments, indeed they should not ever see this block. Other read, write delete
permission could be set.  As far as I can tell, the existing comments system could be used as is.

Installation
============
1. Ensure you have the version of Moodle as stated above in 'Required version of Moodle'.  This is essential as the
   block relies on underlying core code that is out of its control.
2. Login as an administrator and put Moodle in 'Maintenance Mode' so that there are no users using it bar you as the administrator.
3. Copy the extracted 'assignment_review' folder to the '/blocks/' folder.
4. Go to 'Site administration' -> 'Notifications' and follow standard the 'plugin' update notification.
5. Put Moodle out of Maintenance Mode.
6. Add the plugin on an assignment page.

Upgrading
=========
1. Ensure you have the version of Moodle as stated above in 'Required version of Moodle'.  This is essential as the
   block relies on underlying core code that is out of its control.
2. Login as an administrator and put Moodle in 'Maintenance Mode' so that there are no users using it bar you as the administrator.
3. Make a backup of your old 'assignment_review' folder in '/blocks/' and then delete the folder.
4. Copy the replacement extracted 'assignment_review' folder to the '/blocks/' folder.
5. Go to 'Site administration' -> 'Notifications' and follow standard the 'plugin' update notification.
6. Put Moodle out of Maintenance Mode.
7. Add the plugin on an assignment page.

Uninstallation
==============
1. Put Moodle in 'Maintenance Mode' so that there are no users using it bar you as the administrator.
2. Go to 'Site administration' -> 'Plugins' -> 'Blocks -> 'Manage blocks'.
3. Click on 'Uninstall' and follow the on screen instructions.
4. Put Moodle out of Maintenance Mode.

Required version of Moodle
==========================
This version works with:

 - Moodle 4.1 version 2022112800.00 (Build: 20221128) and above within the 4.1 branch.
 - Moodle 4.2 version 2023042400.00 (Build: 20230424) and above within the 4.2 branch.
 - Moodle 4.3 version 2023100900.00 (Build: 20231009) and above within the 4.3 branch.
 - Moodle 4.4 version 2024042200.00 (Build: 20240422) and above within the 4.4 branch.
 - Moodle 4.5 version 2024100700.00 (Build: 20241007) and above within the 4.5 branch.

Installing Moodle links
-----------------------
Please ensure that your hardware and software complies with 'Requirements' in 'Installing Moodle' on:
 - [Moodle 4.1](https://docs.moodle.org/401/en/Installing_Moodle)
 - [Moodle 4.2](https://docs.moodle.org/402/en/Installing_Moodle)
 - [Moodle 4.3](https://docs.moodle.org/403/en/Installing_Moodle)
 - [Moodle 4.4](https://docs.moodle.org/404/en/Installing_Moodle)
 - [Moodle 4.5](https://docs.moodle.org/405/en/Installing_Moodle)

License
=======

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
