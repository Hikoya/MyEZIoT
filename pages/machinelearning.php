<?php 
declare(strict_types=1);

namespace PhpmlExamples;
include 'vendor/autoload.php';
use Phpml\Metric\Accuracy;
use Phpml\Regression\SVR;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\Dataset\ArrayDataset;
use Phpml\Regression\LeastSquares;
use Phpml\Preprocessing\Imputer;
use Phpml\Preprocessing\Imputer\Strategy\MeanStrategy;
use Phpml\CrossValidation\RandomSplit;
use Phpml\Classification\SVC;
use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\Dataset\Demo\WineDataset;

date_default_timezone_set('Asia/Singapore');
		
$config = parse_ini_file('../private/config.ini'); 
$link = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);
$sql = "SELECT * FROM ".$config['tablenamenode']."  ";
$query = mysqli_query($link,$sql);

if(mysqli_num_rows($query) > 0 )
{
	while($result = mysqli_fetch_array($query,MYSQLI_ASSOC))
	{
		$gatewayno = $result['gatewayno'];
		$list[] = $gatewayno;
	}
	
	foreach($list as $key => $value)
	{
		$sql2 = "SELECT * FROM ".$value." ORDER BY TIMESTAMP DESC LIMIT 200";
		$query2 = mysqli_query($link,$sql2);
		if(mysqli_num_rows($query2) > 0)
		{
			while($result2 = mysqli_fetch_array($query2,MYSQLI_ASSOC))
			{
				$column1 = (float)$result2['column1'];
				$column2 = (float)$result2['column2'];
				
				$time = $result2['timestamp'];
				$time = strtotime($time);
			
				$data[] = $column1;
				$data2[] = $column2;
				$timestamp[] = array($time);
				
				$data3[] = array($time,$column1);
				$data4[] = array($time,$column2);
			}
			
			$sql3 = "SELECT * FROM ".$config['tablenamenode']." WHERE gatewayno = '".$value."' ";
			$query3 = mysqli_query($link,$sql3);
			if(mysqli_num_rows($query3) > 0)
			{
				if($result3 = mysqli_fetch_array($query3,MYSQLI_ASSOC))
				{
					$thres1 = (float)$result3['threshold1'];
					$thres2 = (float)$result3['threshold2'];
					
					$name1 = $result3['column1'];
					$name2 = $result3['column2'];
				}
			
			}
			
			
			$regression = new SVR(Kernel::RBF,10000);
			$regression->train($timestamp, $data);

			$regression2 = new SVR(Kernel::RBF,10000);
			$regression2->train($timestamp, $data2);
					
			$date = Date('Y-m-d H:i:s');
			$date = (int)strtotime($date);
				
			
			$dates = $date + 600 ;
			$result = $regression->predict(array($dates));
			$result2 = $regression2->predict(array($dates));
						
			$predicted = date('Y-m-d h:i:s',$dates);
				
			echo nl2br ($name1 . ": " . $result.", ". $name2.": ".$result2." Time: ".$predicted." ".$value." "."\n");
				
			
			
		}
			
	}
	
}


?>