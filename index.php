<?php
/*
	programmer by :Qusai Taha
	Date : 3/5/2019
	Description: these page that implement the playfire cipher
*/
	if($_SERVER["REQUEST_METHOD"]=="POST")
	{
		//session_start();
		//$plaintext=strtolower($_SESSION['trans']);
		$plaintext="welcom to playfair cipher ";// pliantext wanted to encryption
		//$_SESSION['pplayfire']=$plaintext;
		//$_SESSION['kplayfire']=$_POST['key'];
		//$plaintext="meetmeatnextmidnight";
		$plaintext=strtolower($plaintext);
		$plaintext=str_replace('i', '+', $plaintext);//to make i/j same letter=>+
		$plaintext=str_replace('j', '+', $plaintext);//to make i/j same letter=>+
		$s="";
		for($i=0;$i<strlen($plaintext);$i++)// to delete spaces from plain text
		{
			if($plaintext[$i]!=" ")
			{
				$s.=$plaintext[$i];
			}
		}
		$plaintext=$s;
		$newtext="";// to check each two letter not same 
		for($i = 0; $i <strlen ( $plaintext ); $i+=2) {
			if ($plaintext[$i] == $plaintext[$i + 1]) // if are same 
			{
				$newtext.=$plaintext[$i];//take forst letter 
				$newtext.="x";// add x 
				$i--;// to add second letter in other loop 

			}else{
				$newtext.=$plaintext[$i];
				$newtext.=$plaintext[$i+1];
			}
		}

		if(strlen($newtext)%2!=0)// to chaeck the length can be divided into two letters if not add x
		{
			$newtext.='x';
		}

		$plaintext=$newtext;

		$ar=['a'=>array(0,0),'b'=>array(0,0),'c'=>array(0,0),'d'=>array(0,0),'e'=>array(0,0),'f'=>array(0,0),'g'=>array(0,0),'h'=>array(0,0),'+'=>array(0,0),'k'=>array(0,0),'l'=>array(0,0),'m'=>array(0,0),'n'=>array(0,0),'o'=>array(0,0),'p'=>array(0,0),'q'=>array(0,0),'r'=>array(0,0),'s'=>array(0,0),'t'=>array(0,0),'u'=>array(0,0),'v'=>array(0,0),'w'=>array(0,0),'x'=>array(0,0),'y'=>array(0,0),'z'=>array(0,0)];// to store each letter where found in (col,row)

		$ar1=['a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0,'k'=>0,'l'=>0,'m'=>0,'n'=>0,'o'=>0,'p'=>0,'q'=>0,'r'=>0,'s'=>0,'t'=>0,'u'=>0,'v'=>0,'w'=>0,'x'=>0,'y'=>0,'z'=>0];
		//to help delete repaeted letters from a key

		$keyword=$_POST['key'];
		
		$s="";// store keyword after delete repeated letter

		for($i=0;$i<strlen($keyword);$i++)// loop to delete reapeted letter
		{
			if($ar1[$keyword[$i]]==0)// check if the value taken or not
			{
				$s.=$keyword[$i];
				$ar1[$keyword[$i]]=1;
			}
		}

		$keyword=$s;
		$keyword=str_replace('i', '+', $keyword);// make i/j are the same
		$keyword=str_replace('j', '+', $keyword);// make i/j are the same
		$matrix=array();// matrix of playfair
		for($i=1;$i<=5;$i++)// to make the matrix 5 cols or rows
		{
			$matrix[$i]=array();
		}

		$c=0;//to access into keyword 
		$l=strlen($keyword);// store length of the keyword
		// loop  to detrmine each letter where in any column and row
		for($i=1;$i<=5;$i++)
		{
			for($j=1;$j<=5;$j++)
			{
				if($c<$l)// to take all letter in key word 
				{
					$ar[$keyword[$c]][0]=$j;// number of column
					$ar[$keyword[$c]][1]=$i;//number of row
					$matrix[$j][$i]=$keyword[$c];//store the letter in matrix 
					//echo $j."  ".$i."<br>";
				}else{
					foreach ($ar as $k => $v) //to take other letter 
					{
						if($v[0]==0)
						{
							$ar[$k][0]=$j;
							$ar[$k][1]=$i;
							$matrix[$j][$i]=$k;
							break;
						}
					}
				}
				$c++;
			}
		}
		
		$cipher="";// store value of cipher text
		//print_r($matrix);
		for($i=0;$i<strlen($plaintext);$i+=2)//implement the playfire on the plaintext
		{
			if($ar[$plaintext[$i]][0]==$ar[$plaintext[$i+1]][0])// if two letter in the same column
			{
				$c1=$ar[$plaintext[$i]][0];//store value of colunm
				$r1=$ar[$plaintext[$i]][1];//store value of row to firstb letter
				$r2=$ar[$plaintext[$i+1]][1];// store value of row to the second letter
				if($r1==4)
				{
					$cipher.=$matrix[$c1][5];
				}else
				$cipher.=$matrix[$c1][($r1+1)%5];
				if($r2==4)
				{
					$cipher.=$matrix[$c1][5];
				}else
				$cipher.=$matrix[$c1][($r2+1)%5];
				//echo $plaintext[$i]."  ".$plaintext[$i+1]."<br>";
			}else
			if($ar[$plaintext[$i]][1]==$ar[$plaintext[$i+1]][1])// if two letter in same row
			{
				$r=$ar[$plaintext[$i]][1];// store value of the row
				$c1=$ar[$plaintext[$i]][0];// store value the column for the first letter
				$c2=$ar[$plaintext[$i+1]][0];// store value the column for the second letter
				if($c1==4)
				{
					$cipher.=$matrix[5][$r];
				}else
				$cipher.=$matrix[($c1+1)%5][$r];
				if($c2==4)
				{
					$cipher.=$matrix[5][$r];
				}else
				$cipher.=$matrix[($c2+1)%5][$r];
				//echo $plaintext[$i]."  ".$plaintext[$i+1]."<br>";
			}else// if two letter in differnt row and col
			{
				$c1=$ar[$plaintext[$i]][0];//store the value row the first letter
				$c2=$ar[$plaintext[$i+1]][0];// store the value row of the second letter
				$r1=$ar[$plaintext[$i]][1];// store the value column of the first letter
				$r2=$ar[$plaintext[$i+1]][1];// store the value column of the second letter
				$cipher.=$matrix[$c2][$r1];
				$cipher.=$matrix[$c1][$r2];
				//echo $plaintext[$i]."  ".$plaintext[$i+1]."<br>";
			}
		}
		$cipher=str_replace('+', 'i', $cipher);//to convert all + to i
		//$_SESSION['playfire']=$s;
		echo "<div id='id01' class='w3-modal' style='display:block;'>";
				echo "<div class='w3-modal-content' style='width:30%;background-image: linear-gradient(150deg,#d2d4d4,#f5f5f5,#ffffff);'>";
			 		echo"<div class='w3-container'>";
			 		echo"<span onclick=document.getElementById('id01').style.display='none' style='color: #990000;' class='w3-button w3-display-topright w3-red'>";
			      			echo"&times;";
			      	echo"</span>";
			      		echo"<b><center><p style=' margin-top: 7%;'class='w3-green'>";
	                	echo"cipher text";
	             		echo"</p></center></b><br>";
				      	echo"<b><center><p style=' margin-top: 7%;'class='w3-green'>";
	                	echo$cipher;
	             		echo"</p></center></b><br>";
	             		echo"<center>
                			<button type='button'class='w3-button w3-btn w3-red' style='border-radius:10%; margin-top:1%; margin-bottom:5%;' class='w3-button'onclick=document.getElementById('id01').style.display='none' >ok</button>
                		</center>";
			    	echo"</div>";
			  	echo"</div>";
			echo"</div>";
	}
?>
<!DOCTYPE html> 

<html>

	<head>

		<title></title>

		<link rel="stylesheet" type="text/css" href="w3.css">

	</head>

	<body  style="background-image:linear-gradient(50deg,#5c8a8a,#85adad,#a3c2c2);">

		<h1 style="background-image: linear-gradient(150deg,#d2d4d4,#f5f5f5,#ffffff); text-align: center;">
			playfire cipher

		</h1>
		<form action=""  method="post">
		
		<div style=" background-image: linear-gradient(150deg,#d2d4d4,#f5f5f5,#ffffff); margin-left: 35%;  margin-top:4%; width: 35%; height: 40%; font-size: 130%; padding: 2%; border-radius: 10%;">

			<center>

				<b>

					<p >
						please enter a key for the  playfire
					</p>

				</b>

			</center>

			<input class="w3-input  w3-animate-input w3-border w3-hover-gray" type="text" name="key" required placeholder="">

		</div>

		<center>

			<div style="margin-top: 4%;background-image: linear-gradient(150deg,#d2d4d4,#f5f5f5,#ffffff); width: 20%; height: 10%;font-size: 150%; border-radius: 10%;">

				<input class="w3-btn w3-button" type="submit" name="submit" value="continue">

			</div>

		</center>

	</form>
	</body>
	<footer style="margin-top: 4%; margin-bottom: 5%; background-image: linear-gradient(150deg,#d2d4d4,#f5f5f5,#ffffff); width: 100%;">

		<center>

			Copyright &copy; 2019 - Qusai && Ahmad ....

		</center>

	</footer>
</html>
