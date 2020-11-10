#!/usr/bin/php
<?php
// wrapper script for sendmail, to log all email sent via CLI with the original user and the recipient
// inspired by https://www.howtoforge.com/how-to-log-emails-sent-with-phps-mail-function-to-detect-form-spam

// install instructions :
// sudo ln -s phpsendmail.php /usr/sbin
// sudo chmod a+x /usr/sbin/phpsendmail.php
// sudo mv /usr/sbin/sendmail /usr/sbin/sendmail.original
// sudo ln -s /usr/sbin/phpsendmail.php /usr/sbin/sendmail
// sudo touch /var/log/phpsendmail.log
// sudo chmod 752 /var/log/phpsendmail.log

// usage instructions
// echo "test" | sendmail recipient@domain.org
// sudo tail /var/log/phpsendmail.log


// config
$mail_bin = '/usr/sbin/sendmail.original';
$logfile = '/var/log/phpsendmail.log';


// Get the email content
$logline = '';
$mail = '';
$pointer = fopen('php://stdin', 'r');
while ($line = fgets($pointer)) {
    if(preg_match('/^to:/i', $line) || preg_match('/^from:/i', $line)) {
        $logline .= trim($line).' ';
    }
    $mail .= $line;
}


array_shift($_SERVER['argv']);
$args_string = implode(" ", $_SERVER['argv']);
// compose the sendmail command
$command = 'echo ' . escapeshellarg($mail) . ' | ' . $mail_bin . " ";
$command .= $args_string;

$logline .= $args_string;


// Write the log
$date = date('Y-m-d H:i:s');
$username = $_SERVER["USERNAME"] ?? $_SERVER["LOGNAME"] . "(" . posix_getpwuid(posix_geteuid())["name"] . ")";
file_put_contents($logfile, "$date $username $logline".PHP_EOL, FILE_APPEND);

// echo "test2" | logger --no-act --stderr 2>&1 | sed 's/^<[0-9]\+>//' >> 
// https://gist.github.com/ssbarnea/2411440

// Execute the command
echo shell_exec($command);

