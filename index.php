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
require_once($CFG->libdir . '/csvlib.class.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->dirroot . '/grade/querylib.php');
require_once($CFG->libdir . '/gradelib.php');

global $DB;

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

// Check permissions (borrowing from roster)
require_capability('report/roster:view', $coursecontext);

//page setup
$PAGE->set_title($course->shortname .': '. get_string('bannercsv' , 'report_bannercsv'));
$PAGE->set_heading($course->fullname);

/* our end goal is:

  Term Code, CRN, Student ID, Course, Final Grade
  201601, 14705, 990591314, Engineering, A-
  201601, 14705, 990591675, Engineering, B+
  etc

*/

//get idnumber from the datbase
$params = array('theid' => $id);
$sql = "SELECT idnumber FROM {course} WHERE id = :theid";
$idnumber = key($DB->get_records_sql($sql, $params));

//extract term code and crn from idnumber
$idnumber_elements = explode(".", $idnumber);
$crn = $idnumber_elements[0];
$termcode = $idnumber_elements[1];

//get the student id for each Student and construct a record for each
$students_list = array();
foreach ($userlist as $person) {
  $student_record = array();

  array_push($student_record, $termcode);
  array_push($student_record, $crn);
  array_push($student_record, $person->idnumber);
  array_push($student_record, $course->shortname);

  $final_grade = "get final grade";
  // YOU JUST NEED TO INCLUDE THE QUERYLIB then this might work
  $final_grade = grade_get_course_grade($course->id, $person->id);

  array_push($student_record, $final_grade);

  array_push($students_list, $student_record);
}



//Render
echo $OUTPUT->header();

//le debugging
echo '<pre>';

  print_r('<b>"grading info"</b><br>');
  print_r($grading_info);
  print_r('<br><br>');

  print_r('<b>Course ID:</b><br>');
  print_r($id);
  print_r('<br><br>');

  print_r('<b>idumber:</b><br>');
  print_r($idnumber);
  print_r('<br><br>');

  print_r('<b>crn:</b><br>');
  print_r($crn);
  print_r('<br><br>');

  print_r('<b>termcode:</b><br>');
  print_r($termcode);
  print_r('<br><br>');

  print_r("<b>students list:</b><br>");
  print_r($students_list);
  print_r('<br><br>');

echo '</pre>';

//use Moodle api for getting a CSV and downloading it
$csvexport = new csv_export_writer();
//$csvexport->set_filename($filename);
//$csvexport->add_data($fields);
print_r("<pre>");
var_dump($csvexport);
print_r("</pre>");

echo $OUTPUT->footer();
