<?php

$ADMIN->add('reports', new admin_externalpage('reportbannercsv', get_string('bannercsv', 'report_bannercsv'), "$CFG->wwwroot/report/bannercsv/index.php"));

//BTW: args for settings maker thing are: ($name, $visiblename, $description, $defaultsetting, array of options in dropdown)
//U can use, e.g.: admin_setting_configmultiselect, admin_setting_configselect, admin_setting_configtext

$settings->add(
	new admin_setting_configselect(
		'report_bannercsv/course_id_pattern', //name
		get_string('course_id_pattern', 'report_bannercsv'), //visible name
		get_string('course_id_pattern_desc', 'report_bannercsv'), //description
		'default', // string or int default settings
		array(
			'.' => '[CRN].[TERMCODE]',
			'-' => '[CRN]-[TERMCODE]'
		)
	)
);
