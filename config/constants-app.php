<?php

/**
 * constants-app.php
 */

// app constants
// general
define('EOL', "\r\n");
define('BR', '<br />');
// for URL append usage
define('DC', time());
define('DC2', date('Ym'));
define('DC3', date('Ymd'));
define('DS', DIRECTORY_SEPARATOR);

// date+
define('DEFAULT_DATE_FORMAT', 'Y-m-d');
define('DEFAULT_DATETIME_FORMAT', 'Y-m-d H:i:s');
define('DATE_FORMAT_NO_ZERO', 'Y.n.j');
define('TODAY', date(DEFAULT_DATE_FORMAT));
define('NOW', date(DEFAULT_DATETIME_FORMAT));
define('FULL_DATE', date('l jS F Y h:i:s A'));
define('DATE_FORMAT_FULL_MONTH', 'd F, Y');

// admin/webmaster
define('ADMIN_EMAIL', 'developer_mi@yonefu.info');
define('PHONE_CODE', '+977 ');
