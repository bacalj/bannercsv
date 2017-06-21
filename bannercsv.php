<?php

class BannerCsv {

	public function __construct($id){
		global $DB;
		$this->courseobj = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
		$this->coursecontext = context_course::instance($this->courseobj->id);
		$this->userlist = get_enrolled_users($this->coursecontext, '');
		$params = array('theid' => $this->courseobj->id);
		$sql = "SELECT idnumber FROM {course} WHERE id = :theid";
		$this->crnterm = key($DB->get_records_sql($sql, $params));
	}

	function setup_page_and_access(){
		global $PAGE;
		require_login($this->courseobj);
		$PAGE->set_url('/report/bannercsv/index.php', array('id' => $this->courseobj->id));
		$returnurl = new moodle_url('/course/view.php', array('id' => $this->courseobj->id));
		require_capability('report/bannercsv:view', $this->coursecontext);
		$PAGE->set_title($this->courseobj->shortname .': '. get_string('bannercsv' , 'report_bannercsv'));
		$PAGE->set_heading($this->courseobj->fullname);
	}

	function see_this_obj(){
		var_dump($this);
	}

}
