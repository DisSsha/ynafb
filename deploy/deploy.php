<?php
	/**
	 * GIT DEPLOYMENT SCRIPT
	 *
	 * Used for automatically deploying websites via github or bitbucket, more deets here:
	 *
	 *		https://gist.github.com/1809044
	 */

	// The commands
	$commands = array(
		'echo $PWD',
		'whoami',
		'git reset --hard HEAD',
		'git pull',
		'git status',
	);

	// Run the commands for output
	$output = '';
	foreach($commands AS $command){
		// Run it
		$tmp = shell_exec($command);
		// Output
		$output .= "<span style=\"color: #6666ff;\">\$</span> <span style=\"color: #729FCF;\">{$command}\n</span>";
		$output .= htmlentities(trim($tmp)) . "\n";
	}

	// Make it pretty for manual user access (and why not?)
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>GIT DEPLOYMENT SCRIPT</title>
  <style>
    body { font-family: Courier, monospace; font-size: 0.9em; }
    a {color: #ffff66;} , a:hover, a:active, a:visited { text-decoration: none; color: #ffff66 }
    a:hover { text-decoration: underline; }
    ul { list-style: none }
    li { overflow: hidden; height: 1.1em; }
    .rev { color: #ffff66; }
    .date { color: #66ff99; }
    .author, .author a { color: #9966ff; }
    .tags { color: #ffcc66; }
  </style>
</head>
<body style="background-color: #2a3a4a; color: #FFFFFF; padding: 0 10px;">
<pre>
 .  ____  .    ____________________________
 |/      \|   |                            |
[| <span style="color: #FF0000;">&hearts;    &hearts;</span> |]  | Git Deployment Script      |
 |___==___|  /                             |
              |____________________________|

<?php 
	echo $output; 
	$msgs = array();
  	exec( "/usr/bin/env git log --pretty=tformat:%s $limit", $msgs );
  	$lines = array();
  	exec( "/usr/bin/env git log --pretty=tformat:'</span><a href=\"#%h\">%h</a> - <span class=\"date\">[%cr]</span> <span class=\"tags\">%d</span> __COMMENT__ <span class=\"author\">&lt;<a href=\"mailto:%ae\">%an</a>&gt;</span></li>' --graph --abbrev-commit $limit", $lines );
?>
</pre>
<?php 
  echo '<ul>';
  	for ($i=0; $i<count($lines); $i++ ){
    	  $msg = htmlentities($msgs[$i],ENT_QUOTES);
    	  $message = str_replace('__COMMENT__',$msg,$lines[$i]);
    	  echo '<li><span class="graph">'.$message."\n";
    	}
    	echo '</ul>';
?>
</body>
</html>
