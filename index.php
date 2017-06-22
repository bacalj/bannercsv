<?php

//dependencies
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/csvlib.class.php');
require_once($CFG->dirroot . '/grade/querylib.php');
require_once($CFG->libdir . '/gradelib.php');
include('bannercsv.php');
global $DB;

$id = required_param('id', PARAM_INT);
$bcsv = new BannerCsv($id);

$bcsv->setup_page_and_access();

$bcsv->extract_term_and_crn();

//render
echo $OUTPUT->header();
//echo '<pre>';
  //$bcsv->display_record_preview();
$bcsv->build_student_records_stream_content();
$bcsv->display_record_preview();
$bcsv->render_csv_download_link();

  //$bcsv->collect_student_id_location();
//echo '</pre>';
echo $OUTPUT->footer();
