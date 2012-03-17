<?php
if(__FILE__ == $_SERVER['SCRIPT_FILENAME'])
	{
		header('Location: index.php?page=fileinfo');
		die();
	}

if(isset($_GET['fileID']))
{
	if(isset($_GET['reportSuccess']) && $_GET['reportSuccess'] == 'true') echo '<div id="success">You have succesfully reported the file as abuse. Thank you!</div>
';
	$fileID = $_GET['fileID'];

	$sql = "SELECT f.rowID AS f_rowID,
	        f.file AS f_file,
	        f.uploaded_by AS f_uploaded_by,
			f.uploaded_date AS f_uploaded_date,
			f.size AS f_size,
			f.times_downloaded AS f_times_downloaded,
			f.mimetype AS f_mimetype,
			f.description AS f_description,
			u.rowID AS u_rowID,
			u.username AS u_username
				FROM users u,
				     files f
				WHERE f.uploaded_by=u.rowID
				AND f.rowID=$fileID LIMIT 1";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) == 0)
	{
		header('Location: ?page=404');
		die();
	}
	$row = mysql_fetch_array($result);
	echo '
<h1>'.$row['f_file'].'</h1>
';
	//Has the file been reported as abuse?
	$sql3    = "SELECT *
				FROM abuse
				WHERE fileID=$fileID";
	$result3 = mysql_query($sql3);
	if(mysql_num_rows($result3) != 0 && !isset($_GET['reportSuccess'])) echo '
<div id="error">
Be careful! This file has been reported at abuse! We are on the case. You can still download the file, but: BE CAREFUL!
<br>
';
	echo '
<p class="submit">
	<input type="button" value="Download file" onClick="window.location.href=\'download.php?file='.$row['f_rowID'].'\'"></p>
</div>
';

	$sql4 = "SELECT d.rowID AS d_rowID,
		       d.downloaded_by AS d_downloaded_by,
		       d.fileID AS d_fileID,
		       d.downloaded_by AS d_downloaded_date,
		       u.rowID AS u_rowID,
		       u.username AS u_username,
		       u.rowID AS u_rowID,
		       f.rowID AS f_rowID
				FROM downloads d,
				     users u,
				     files f
				WHERE d.fileID =$fileID
				AND d.downloaded_by = u.rowID
    			AND d.fileID = f.rowID";
	$result4 = mysql_query($sql4);
	$download_numrows = mysql_num_rows($result4);

	$string1   = 'onClick=reportFile('.$row['f_rowID'].');';
    echo "
<p class='submit'>
<input type='button' onClick='$string1' href='#' value='Report abuse' ></p>
";
	echo '
	<center>
		<table id="table">
			<th>Uploaded by</th>
			<th>Size</th>
			<th>Date uploaded</th>
			<th>Mimetype</th>';
			if(substr($row['f_mimetype'], 0, 6) == 'image/') echo '<th>Dimentions</th>';
			echo '<th>Times downloaded</th>
			';
	echo '
			<tr class="alt">
				';
	echo '
				<td>
					<a href=?page=profile&userID='.$row['u_rowID'].'>'.$row['u_username'].'</a>
				</td>
				';
		echo '
				<td>'.calc_file_size($row['f_size']).'</td>
				';
	echo '
				<td>'.date("d/m/y H:i",$row['f_uploaded_date']).'</td>
				';
	echo '
				<td>'.$row['f_mimetype'].'</td>
				';
	if(substr($row['f_mimetype'], 0, 6) == 'image/')
	{
		if($_SERVER['HTTP_HOST'] == 'localhost') $url = 'http://localhost/filehunt/';
		else $url = 'http://'.$_SERVER['HTTP_HOST'].'/';
		$size = getimagesize($url.'printimage.php?id='.$fileID);
		$explode = explode('"', $size[3]);
		$width = $explode[1];
		$height = $explode[3];
		echo '
		<td>Height: '.$height.' Width: '.$width.'</td>';
	}

	echo "
				<td>
					<a href='?page=download_analysis&file=$fileID'>$download_numrows</a>
				</td>
				";
	echo '
			</tr>
			';
	echo '
		</table>
	</center>
	';

	//Description
	if($row['f_description'] != '')
	{
		echo '
<br />
<fieldset class="description">
<legend>Description</legend>
';
		echo $row['f_description'].'
</fieldset>
<br />
';
	}


	if(substr($row['f_mimetype'], 0, 6) == 'image/')
	{
		if($height > 500)
		{
			$height = round($height / 5.184);
			$width = round($width / 5.184);
		}
		echo '
			<br />
			<img src="printimage.php?id='.$fileID.'" height="'.$height.'" width="'.$width.'" />';
	}

	echo '
<h1>Comments</h1>
';

	$sql = "SELECT c.rowID AS comment_rowID,
			c.fileID AS fileID,
			c.comment_by AS comment_by_id,
			c.date_commented AS date_commented,
			c.comment AS comment,
			u.username AS username,
			u.rowID AS user_row
			FROM comments c,
     			users u
			WHERE c.fileID=$fileID
    		AND c.comment_by=u.rowID";
	//
	$result  = mysql_query($sql,$con);
	$numrows = mysql_num_rows($result);
	if($numrows !=0)
	{
		echo
<<< _END
		<center>
<table id="table">
	<th>Commented by</th>
	<th>Date commented</th>
	<th>Comment</th>
_END;
		
		$fileID  = $_GET['fileID'];
		$sql2 ="SELECT f.file AS filename,
				f.rowID AS file_row,
				f.uploaded_by AS uploaded_by,
				u.rowID AS user_row,
				u.username AS username
				FROM files f,
				     users u
				WHERE f.rowID=$fileID 
				LIMIT 1";
		$result2 = mysql_query($sql2,$con);
		$row2 = mysql_fetch_array($result2);
		$fileName = $row2['filename'];
		$uploaded_by = $row2['username'];
		$count = 0;
		while($row = mysql_fetch_array($result))
		{
			$commented_by   = $row['username'];
			$date_commented = date("d/m/y H:i",$row['date_commented']);
			$comment        = $row['comment'];
			if(oddOrEven($count)==1) echo '
	<tr class="alt">
		';
			elseif(oddOrEven($count)) echo '
		<tr>
			';
			$row_userid = $row['user_row'];
			echo "
			<td>
				<a href='?page=profile&userID=$row_userid'>$commented_by</a>
			</td>
			<td>$date_commented</td>
			<td>$comment</td>
		</tr>
		";
		++$count;
		}
		echo '
	</table>
</center>
';
		
	}
	elseif($numrows == 0 && isset($_SESSION['dbuserid']))
	{
		echo "
<div id='error'>There is no comments for this file! Why dont ya' add one?</div>
";
	}
	elseif($numrows == 0 && !isset($_SESSION['dbuserid']))
	{
		echo "
<div id='error'>There is no comments for this file!</div>
";
	}

	if (isset($_SESSION['dbuserid']))
	{
	$query_string = '?'.$_SERVER['QUERY_STRING'];
	echo
<<< _END
<form class="form" action="$query_string" method="post">
	<p class="message">
		<label for="message">Message</label>
		<br />
		<textarea name="comment" cols="40" rows="6" placeholder="Message" id="message" ></textarea>
		<input type='hidden' name='submit' value='true' />
	</p>
	<p class="submit">
		<input type="submit" value="Submit" />
	</p>
</form>
_END;
	}
	else
	{
		$fileID_attempt = $_GET['fileID'];
		echo "
<br />
<div id='error'>
	You need to
	<a href='?page=login&attemptedSite=fileinfo&fileID=$fileID_attempt'>login</a>
	to comment!
</div>
";
	}

	if(isset($_POST['comment']) && !empty($_POST['comment']) && isset($_POST['submit']))
	{
		$get_fileid     = $_GET['fileID'];
		$session_userid = $_SESSION['dbuserid'];
		// $date = date("d/m/y H:i", time());
		// $datestrto = strtotime($date);
		$datestrto = time();
		$post_comment   = $_POST['comment'];
		$sql_uniq = "SELECT *
					FROM comments
					WHERE comment='$post_comment'
					    AND comment_by=$session_userid";
		$result_uniq = mysql_query($sql_uniq);
		if(mysql_num_rows($result_uniq) == 0)
		{
			$sql = "INSERT INTO comments (rowID, fileID, comment_by, date_commented, comment) VALUES(NULL, $get_fileid, $session_userid, $datestrto, '$post_comment')";
			$result = mysql_query($sql,$con);

		}
		header('Location: '.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
	}
}
else
{
	header('Location: ?page=404');
	die();
}

?>