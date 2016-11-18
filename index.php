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

//dependencies
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/csvlib.class.php');
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

//set up page
$PAGE->set_title($course->shortname .': '. get_string('bannercsv' , 'report_bannercsv'));
$PAGE->set_heading($course->fullname);

// get course idnumber - which is a concat of Banner CRN + Term Code
$params = array('theid' => $id);
$sql = "SELECT idnumber FROM {course} WHERE id = :theid";
$idnumber = key($DB->get_records_sql($sql, $params));

//extract term code and crn from idnumber
$idnumber_elements = explode(".", $idnumber);
$crn = $idnumber_elements[0];
$termcode = $idnumber_elements[1];

//hardcode the field titles and put it on top of our future list
$students_list = array('Term Code,CRN,Student ID,Course,Final Grade');

//get the student id for each Student and construct a record for each
foreach ($userlist as $person) {

  //each row in csv is going to be a record array
  $student_record = array();

  //get the students final grade
  $studentkey = $person->id;
  $final_grade_obj = grade_get_course_grades($course->id, $studentkey);
  $final_grade_num = $final_grade_obj->grades[$studentkey]->grade;
  $final_grade_ltr = $final_grade_obj->grades[$studentkey]->str_grade;

  //build up the record
  array_push($student_record, $termcode);
  array_push($student_record, $crn);
  array_push($student_record, $person->idnumber);
  array_push($student_record, $course->shortname);
  array_push($student_record, $final_grade_ltr);

  //compact each record into a comma-separated string
  $record_string = implode(',', $student_record);

  //put the record in the records list
  array_push($students_list, $record_string);
}

//hardcode Banner field titles
$fields = 'Term Code,CRN,Student ID,Course,Final Grade';

//Render
echo $OUTPUT->header();

echo '<pre>';
  var_dump($students_list);
echo '</pre>';


$open_csv_link = '<a href="';
$streamer = 'data:application/octet-stream,';
$magic_string = 'field1%2Cfield2%0Afoo%2Cbar%0Agoo%2Cgai%0A';
$close_csv_link = '">Download CSV</a>';

echo $open_csv_link . $streamer . $magic_string . $close_csv_link;

echo $OUTPUT->footer();
