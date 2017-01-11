<?php

$ADMIN->add('reports', new admin_externalpage('reportbannercsv', get_string('bannercsv', 'report_bannercsv'), "$CFG->wwwroot/report/bannercsv/index.php"));


//BTW: args for settings maker thing are: ($name, $visiblename, $description, $defaultsetting)

$settings->add(
	new admin_setting_configcheckbox(
		'report_bannercsv/torf', //name
		get_string('flavor1', 'report_bannercsv'), //visible name
		'foo desc', //description
		1  //default
	)
);
