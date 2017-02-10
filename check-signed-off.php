<?php

/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines http://www.simplemachines.org
 * @copyright 2017 Simple Machines and individual contributors
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.1 Beta 3
 */

$message = trim(shell_exec('git show -s --format=%B HEAD'));
$lines = explode("\n", trim(str_replace("\r", "\n", $message)));
$lastLine = $lines[count($lines) - 1];

// This is debugging stuff for now to figure out why Travis can fail to get us the right info about a signed off commit.
echo "--DEBUG MASTER B --\n";
shell_exec('git show -s --format=%B HEAD');
echo "\n--DEBUG MASTER B --\n";

echo "--DEBUG MASTER B2 --\n";
shell_exec('git show -s --format=%b HEAD');
echo "\n--DEBUG MASTER B2 --\n";

echo "--DEBUG MASTER N --\n";
shell_exec('git show -s --format=%N HEAD');
echo "\n--DEBUG MASTER N --\n";

echo "--DEBUG MASTER d --\n";
shell_exec('git show -s --format=%d HEAD');
echo "\n--DEBUG MASTER d --\n";

echo "--DEBUG MASTER H --\n";
shell_exec('git show -s --format=%H HEAD');
echo "\n--DEBUG MASTER H --\n";

echo "--DEBUG MASTER T --\n";
shell_exec('git show -s --format=%T HEAD');
echo "\n--DEBUG MASTER T --\n";

echo "--DEBUG MASTER P --\n";
shell_exec('git show -s --format=%P HEAD');
echo "\n--DEBUG MASTER P --\n";

// Did we find a merge?
if (preg_match('~Merge ([A-Za-z0-9]{40}) into ([A-Za-z0-9]{40})~i', $lastLine, $merges))
{
	echo 'Message contains a merge, trying to find parent [' . $lastLine . ']' . '[' . $message . ']' . "\n";
	$message = trim(shell_exec('git show -s --format=%B ' . $merges[1]));	
	$lines = explode("\n", trim(str_replace("\r", "\n", $message)));
	$lastLine = $lines[count($lines) - 1];


	// This is debugging stuff for now to figure out why Travis can fail to get us the right info about a signed off commit.
	echo "--DEBUG SECONDARY B --\n";
	shell_exec('git show -s --format=%B ' . $merges[1] . '');
	echo "\n--DEBUG SECONDARY B --\n";

	echo "--DEBUG SECONDARY B2 --\n";
	shell_exec('git show -s --format=%b ' . $merges[1] . '');
	echo "\n--DEBUG SECONDARY B2 --\n";

	echo "--DEBUG SECONDARY N --\n";
	shell_exec('git show -s --format=%N ' . $merges[1] . '');
	echo "\n--DEBUG SECONDARY N --\n";

	echo "--DEBUG SECONDARY d --\n";
	shell_exec('git show -s --format=%d ' . $merges[1] . '');
	echo "\n--DEBUG SECONDARY d --\n";

	echo "--DEBUG SECONDARY H --\n";
	shell_exec('git show -s --format=%H ' . $merges[1] . '');
	echo "\n--DEBUG SECONDARY H --\n";

	echo "--DEBUG SECONDARY T --\n";
	shell_exec('git show -s --format=%T ' . $merges[1] . '');
	echo "\n--DEBUG SECONDARY T --\n";

	echo "--DEBUG SECONDARY P --\n";
	shell_exec('git show -s --format=%P ' . $merges[1] . '');
	echo "\n--DEBUG SECONDARY P --\n";
}

$result = stripos($lastLine, 'Signed-off-by:');
if ($result === false)
{
	// Try 2.
	$result2 = stripos($lastLine, 'Signed by');
	if ($result2 === false)
	{
		die('Error: Signed-off-by not found in commit message [' . $lastLine . ']' . '[' . $message . ']' . '[' . $debugSecondary . '][' . $debugMaster . ']' . "\n");
	}
}