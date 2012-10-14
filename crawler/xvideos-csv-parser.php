<?php

$db['host'] = 'localhost';
$db['user'] = 'root';
$db['password'] = 'jio8yhbhkutf';
$db['database'] = 'xvs';
$mysqli = new mysqli($db['host'],$db['user'],$db['password'],$db['database']) or die('failed to connect db');
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$row = 1;
if (($handle = fopen($argv[1], "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 16384, ";")) !== FALSE) {
		$num = count($data);
		$row++;
		for ($c=0; $c < $num; $c++) {
			switch ($c) {
				case 0:
					$video['uri'] = $data[$c];
					$url_fragment = parse_url($data[$c]);
					preg_match('/^\/video(\d+)\//',$url_fragment['path'],$matches);
					if (is_nan($matches[1])) {
						continue 3;
					}
					$video['id'] = $matches[1];
					break;
				case 1:
					$video['title'] = $data[$c];
					break;
				case 2:
					preg_match('/^(\d+)/',$data[$c],$matches);
					$video['duration'] = $matches[1];
					if ($video['duration'] < 60) {
						continue 3;
					}
					break;
				case 3:
					$video['thumb_uri'] = $data[$c];
					break;
				case 4:
					$video['embed_tag'] = $data[$c];
					break;
				case 5:
					if (empty($data[$c])) {
						continue 3;
					}
					$video['tags'] = explode(',',$data[$c]);
					break;
				case 6:
					$video['category'] = $data[$c];
					break;
			}
		}
		if ($vidsql = $mysqli->prepare(
			"INSERT INTO xvideos (
				id,
				uri,
				title,
				duration,
				thumb_uri,
				embed_tag,
				category
			) VALUES (?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE
			id=?,
			uri=?,
			title=?,
			duration=?,
			thumb_uri=?,
			embed_tag=?,
			category=?"
		)) {
		}else{
			continue;
		};
		$vidsql->bind_param('ississsississs',$video['id'],$video['uri'],$video['title'],$video['duration'],$video['thumb_uri'],$video['embed_tag'],$video['category'],$video['id'],$video['uri'],$video['title'],$video['duration'],$video['thumb_uri'],$video['embed_tag'],$video['category']);
		$vidsql->execute();
		if ($vidsql->errno) {
			echo($vidsql->error);
		}
		$vidsql->close();
		if ($tagrsql = $mysqli->prepare("SELECT tag FROM xvideos_tag WHERE xvideos_id=?")){
		}
		else{continue;};
		$tagrsql->bind_param('i',$video['id']);
		$tagrsql->execute();
		$tagrsql->bind_result($res_tag);
		$tagrsql->close();
		if ($tagdsql = $mysqli->prepare("DELETE FROM xvideos_tag WHERE xvideos_id=?")){
		}
		else {continue;}
		$tagdsql->bind_param('i',$video['id']);
		$tagdsql->execute();
		$tagdsql->close();
		$tagwsql_raw = "INSERT INTO xvideos_tag (xvideos_id,tag) VALUES ";
		foreach ($video['tags'] as $tag) {
			$tagwsql_raw .= '('.$video['id'].',\''.$tag.'\'),';
		}
		$tagwsql_raw = substr($tagwsql_raw,0,-1);
		if ($mysqli->query($tagwsql_raw)===TRUE) {
		}
		else {continue;}
	}
	fclose($handle);
}
