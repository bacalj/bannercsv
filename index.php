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

//render
echo $OUTPUT->header();
echo '<pre>';
  $bcsv->see_this_obj();
echo '</pre>';
echo $OUTPUT->footer();
