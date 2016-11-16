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
 * Display a bannercsv of students
 *
 * @package   report_bannercsv
 * @copyright 2016 Joe Bacal, Smith College ETS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');

//collect key values
$id = required_param('id', PARAM_INT);
$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
$coursecontext = context_course::instance($course->id);
$userlist = get_enrolled_users($coursecontext, '');

//we are advised to do this
require_login($course);

//set urls
$PAGE->set_url('/report/bannercsv/index.php', array('id' => $id));
$returnurl = new moodle_url('/course/view.php', array('id' => $id));

// Check permissions (borrowing from roster - TODO: make your own)
require_capability('report/roster:view', $coursecontext);

//page setup
$PAGE->set_title($course->shortname .': '. get_string('bannercsv' , 'report_bannercsv'));
$PAGE->set_heading($course->fullname);

//Render
echo $OUTPUT->header();

//le debugging
echo '<pre>';
  print_r('<b>Course ID:</b><br>');
  var_dump($id, $course->id);
  print_r("<br>");
  print_r("<b>Users:</b><br>");
  foreach ($userlist as $user) {
    var_dump($user->email);
  }
echo '</pre>';

echo $OUTPUT->footer();
