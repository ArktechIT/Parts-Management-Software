<?php
include("../Common Data/PHP Modules/anthony_retrieveText.php");
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<center><h2>図面をリンク Zumen LINK </h2></center>
<?php
$partId = isset($_GET['partId']) ? $_GET['partId'] : "";
$submit = isset($_GET['submit']) ? $_GET['submit'] : "";
$error = isset($_GET['error']) ? $_GET['error'] : "";
$cusDraw=0;
$arkDraw=0;
$otherDraw=0;
$isoDraw=0;

if($error!="")
{
echo "<br><center><font size=5>".$error."</font></center><br>"; 
}

if($partId>0)
{
	if((file_exists("../../Document Management System/customerDrawing/".$partId.".pdf")>0)){ $cusDraw=1; } if($cusDraw==1){$cusDraw2="ok";}	else{$cusDraw2="-なしnone-";}
	if((file_exists("../../Document Management System/arktechDrawing/".$partId.".pdf")>0)){ $arkDraw=1; } if($arkDraw==1){$arkDraw2="ok";}	else{$arkDraw2="-なしnone-";}
	if((file_exists("../../Document Management System/relatedDrawing/".$partId.".pdf")>0)){ $otherDraw=1; }  if($otherDraw==1){$otherDraw2="ok";}	else{$otherDraw2="-なしnone-";}
	if((file_exists("../../Document Management System/3Disometric/".$partId.".pdf")>0)){ $isoDraw=1; }  if($isoDraw==1){$isoDraw2="ok";}	else{$isoDraw2="-なしnone-";}
	?>
	<table border=1>
		<tr><td>
			<table border=1>
			<tr>
			<td width=150 align=center><b>図面の種類<br>Type of Drawing</b></td>
			<td width=80 align=center><b>格<br>Status</b></td>
			<td width=300 align=center><b>操作（追加/編集）<br>Operation (Add/Edit)</b></td>
			<td width=150 align=center><b>ボタン<br>button</b></td>
			</td>
			</tr>
			</table>
		</tr>
		<tr>
			<td>
				<form action="upload.php?partId=<?php echo $partId; ?>&submit=1" method="post" enctype="multipart/form-data">
				<table border=1>
				<tr>
				<td width=150>1. 顧客の図面 Customer Drawing</td>
				<?php if($cusDraw==1){ ?>
				<td width=80 align=center><a href="#" onclick= "window.open('../../Document Management System/customerDrawing/<?php echo $partId; ?>.pdf','','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><?php echo $cusDraw2; ?></a></td>	
				<?php }else{ ?>
				<td width=80 align=center><?php echo $cusDraw2; ?></td>
				<?php } ?>
				<td width=300><input type="file" name="fileToUpload" id="fileToUpload"></td>
				<td width=150 align=center><input type="submit" value="アップロード図面&#13;&#10;Upload Image" name="submit"></td>			
						
				</tr>
				</table>
				</form>
			</td>				
		</tr>
		<tr>
			<td>
				<form action="uploadArk.php?partId=<?php echo $partId; ?>&submit=1" method="post" enctype="multipart/form-data">
				<table border=1>
				<tr>
				<td width=150>2. アークテックの図面 <br> <?php echo displayText('L1726');?></td>				
				<?php if($arkDraw==1){ ?>
				<td width=80 align=center><a href="#" onclick= "window.open('../../Document Management System/arktechDrawing/<?php echo $partId; ?>.pdf','','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><?php echo $arkDraw2; ?></a></td>	
				<?php }else{ ?>
				<td width=80 align=center><?php echo $arkDraw2; ?></td>
				<?php } ?>					
				<td width=300><input type="file" name="fileToUpload" id="fileToUpload"></td>
				<td width=150 align=center><input type="submit" value="アップロード図面&#13;&#10;Upload Image" name="submit"></td>			
						
				</tr>
				</table>
				</form>
			</td>				
		</tr>
		<tr>
			<td>
				<form action="uploadOther.php?partId=<?php echo $partId; ?>&submit=1" method="post" enctype="multipart/form-data">
				<table border=1>
				<tr>
				<td width=150>3. 他の図面 <br> Other Drawing</td>
				<?php if($otherDraw==1){ ?>
				<td width=80 align=center><a href="#" onclick= "window.open('../../Document Management System/relatedDrawing/<?php echo $partId; ?>.pdf','','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><?php echo $otherDraw2; ?></a></td>	
				<?php }else{ ?>
				<td width=80 align=center><?php echo $otherDraw2; ?></td>
				<?php } ?>			
				<td width=300><input type="file" name="fileToUpload" id="fileToUpload"></td>
				<td width=150 align=center><input type="submit" value="アップロード図面&#13;&#10;Upload Image" name="submit"></td>			
						
				</tr>
				</table>
				</form>
			</td>	
			</tr>
		<tr>
			<td>
				<form action="upload3D.php?partId=<?php echo $partId; ?>&submit=1" method="post" enctype="multipart/form-data">
				<table border=1>
				<tr>
				<td width=150>3. 他の図面 <br> <?php echo displayText('L82');?></td>
				<?php if($isoDraw==1){ ?>
				<td width=80 align=center><a href="#" onclick= "window.open('../../Document Management System/3Disometric/<?php echo $partId; ?>.pdf','','left=50,screenX=20,screenY=60,resizable,scrollbars,status,width=700,height=500'); return false;"><?php echo $isoDraw2; ?></a></td>	
				<?php }else{ ?>
				<td width=80 align=center><?php echo $isoDraw2; ?></td>
				<?php } ?>			
				<td width=300><input type="file" name="fileToUpload" id="fileToUpload"></td>
				<td width=150 align=center><input type="submit" value="アップロード図面&#13;&#10;Upload Image" name="submit"></td>			
						
				</tr>
				</table>
				</form>
			</td>				
		</tr>
		
	</table>
	<?php
}
?>
