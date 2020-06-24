<?php session_start(); ?>
<?php include 'include/header.php'; ?>
	<title>Overall Report Page</title>
  </head>
  <body>
	<?php include 'include/navbar.php'; ?>
<?php
	if(!isset($_COOKIE['user']) && !isset($_COOKIE['admin'])){
?>
  <div class="container">
  <div class="jumbotron">
  <h1 class="display-4">Login Required !</h1>
  <p class="lead">Please Login First to View This Page</p>
  <hr class="my-4">
  <a class="btn btn-primary btn-lg" href="../index.php" role="button">Go Back To Login</a>
  </div>
  </div>
<?php
	}else{
?>
	<?php
		$elections;$rowCount;
		try {
				$connect = new PDO('mysql:host=localhost;dbname=test','root','');
				$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);  
				$universalEID;
				if (isset($_COOKIE['user']) && !empty($_COOKIE['user']))
				{
				    $raw_voterID=$_COOKIE['user'];
					$raw_voterID=preg_split('/\-\|\-/', $raw_voterID);
					$voterID=$raw_voterID[1];    
					$stmt = $connect->prepare('SELECT eid FROM voters WHERE voter_id = :voter_id');
					$stmt->bindParam( ':voter_id', $voterID);
					$stmt->execute();
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					$results = $stmt->fetch();   
					$universalEID = $results['eid'];   
					$stmt = $connect->prepare('SELECT * FROM elections WHERE eid = :eid');
					$stmt->bindParam(':eid',$universalEID);
					$stmt->execute();
					$rowCount = $stmt->rowCount();
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					$elections = $stmt->fetch();     
				}
				else if (isset($_COOKIE['admin']) && !empty($_COOKIE['admin']) )
				{					
					$stmt = $connect->prepare('SELECT elections.* FROM elections INNER JOIN candidateandelection ON elections.eid = candidateandelection.eid INNER JOIN counts ON counts.ceid = candidateandelection.ceid ORDER BY counts.vid DESC');
					$stmt->execute();
					$rowCount = $stmt->rowCount();
					$stmt->setFetchMode(PDO::FETCH_ASSOC);
					$elections = $stmt->fetchAll();
				}

		} catch (PDOException $e) {          
			echo $e->getMessage();
		}   
		$connect = null;
		function isValid( $store, $id)
		{
			$rValue = true;
			if(!empty($store)){
				for ($i=0; $i < count($store) ; $i++) { 
					if($store[$i] === $id){
						$rValue = false;
						break;
					}					
				}
			}
			return $rValue;
		}
	?>
	<div class="container p-0 mt-1">
		<select class="custom-select custom-select-lg" id="election-selectbox">
		<?php if($rowCount == 1) { ?>
			<option value="<?php echo $elections['eid']; ?>"> <?php echo $elections['ename']; ?> , <?php if(!empty($elections['eyear_end'])) { ?>[ From <?php echo $elections['eyear_begin']; ?> ~ To <?php echo $elections['eyear_end']; ?> ]<?php }else { ?>[ <?php echo $elections['eyear_begin']; ?> ]<?php } ?> , <?php $datetime=DateTime::createFromFormat('Y-m-d',$elections['edate']);
				$eday=$datetime->format('jS-F-Y ( l )'); echo $eday;?>
			</option>
		<?php } 
		 	  else
		 	  {
		 	  	 $temp = [];
		 	  	 foreach ($elections as $value) 
		 	  	 {
		 			if(!isValid( $temp, (int)$value['eid'])){
		 				continue;
		 			}
		 			array_push( $temp, (int)$value['eid']);
		?>
			<option value="<?php echo $value['eid']; ?>" <?php if((isset($_SESSION['eid']) ? $_SESSION['eid'] : null) == $value['eid']) echo 'selected'; ?>> <?php echo $value['ename']; ?> , <?php if(!empty($value['eyear_end'])) { ?>[ From <?php echo $value['eyear_begin']; ?> ~ To <?php echo $value['eyear_end']; ?> ]<?php }else { ?>[ <?php echo $value['eyear_begin']; ?> ]<?php } ?> , <?php $datetime=DateTime::createFromFormat('Y-m-d',$value['edate']);
				$eday=$datetime->format('jS-F-Y ( l )'); echo $eday;?>
			</option>		
		<?php	
		 	  	 }
		 	  } 
		?>
		</select>
	</div>
	<div class="container bg-light mt-1 pl-0 pr-0">

		<canvas id="mychart"></canvas>

	</div>
<?php } ?>
	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="bootstrap/js/jquery-3.4.1.js"></script>    
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="node_modules/moment/moment.js"></script>
	<script src="node_modules/chart.js/dist/Chart.js"></script>
<script>
$(document).ready(function(){
var eid=$('#election-selectbox option:selected').val();
$.ajax({
	'url': 'dbprocessor.php?pid=2&eid='+eid,
	'type': 'GET',
	'contentType': 'application/x-www-form-urlencoded;charset=utf-8',
	'dataType': 'text',
	'success': function (result){
		buildChart(result);
	}
});
$('#election-selectbox').on( 'change', function (event){
	var selectedValue = $(this).val();
	$.ajax({
		'url': 'dbprocessor.php?pid=2&eid='+selectedValue,
		'type': 'GET',
		'contentType': 'application/x-www-form-urlencoded;charset=utf-8',
		'dataType': 'text',
		'success': function (result){
			buildChart(result);
		}
	})
})
});
var myChart;
function buildChart(result){
	var obj = JSON.parse(result);
	var ctx = document.getElementById('mychart');
	var chart_data = [];
	var chart_label = [];
	var chart_bgcolor = [];
	var chart_bdcolor = [];
	if(myChart){
		myChart.destroy();
	}
	$.each( obj, function ( index, value){
		let num = Number(value['percentage']);
		let roundedValue = Number(num.toFixed(2));
		chart_data[index] = roundedValue;
		chart_label[index] = value['party_name_S'] ? value['party_name_S'] : 'None('+value['name']+')';

		if(roundedValue <= 20){
			chart_bgcolor[index] = 'rgba(255, 99, 132, 0.2)';
			chart_bdcolor[index] = 'rgba(255, 99, 132, 1)';
		}
		if(roundedValue > 20 && roundedValue <= 40){
			chart_bgcolor[index] = 'rgba(255, 159, 64, 0.2)';
			chart_bdcolor[index] = 'rgba(255, 159, 64, 1)';
		}
		if(roundedValue > 40 && roundedValue <= 60){
			chart_bgcolor[index] = 'rgba(255, 206, 86, 0.2)';
			chart_bdcolor[index] = 'rgba(255, 206, 86, 1)';
		}
		if(roundedValue > 60 && roundedValue <= 80){
			chart_bgcolor[index] = 'rgba(54, 162, 235, 0.2)';
			chart_bdcolor[index] = 'rgba(54, 162, 235, 1)';
		}
		if(roundedValue > 80 && roundedValue <= 100){
			chart_bgcolor[index] = 'rgba(75, 192, 192, 0.2)';
			chart_bdcolor[index] = 'rgba(75, 192, 192, 1)';
		}
	})
	myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chart_label,
        datasets: [{
            label: 'Vote Percentage',
            data: chart_data,
            backgroundColor: chart_bgcolor,
            borderColor: chart_bdcolor,
            borderWidth: 1
        }]
    },
    options: {
    	tooltips: {
    		callbacks: {
    			label: function ( tooltipItem, data){
    				var label = data.datasets[tooltipItem.datasetIndex].label || '';
    				if (label) {
    					label += ' : '+tooltipItem.yLabel+'% ';
    				}
    				return label;
    			}
    		}
    	},
    	legend: {
            display: true,
            reverse: true,
            fontSize: 17,
            labels: {
            	boxWidth: 50,
            	padding: 20,
            	generateLabels: function (chart){
            		var data = chart.data;
            		if(data.labels.length && data.datasets.length){
            			return [
            			{
            				index: 0,
            				text: 'Very Poor',
            				fillStyle: 'rgba(255, 99, 132, 0.2)',
            				strokeStyle: 'rgba(255, 99, 132, 1)',
            				lineWidth: 1,
            				hidden: false,
            			},
            			{
            				index: 1,
            				text: 'Poor',
            				fillStyle: 'rgba(255, 159, 64, 0.2)',
            				strokeStyle: 'rgba(255, 159, 64, 1)',
            				lineWidth: 1,
            				hidden: false,
            			},
            			{
            				index: 2,
            				text: 'Fair',
            				fillStyle: 'rgba(255, 206, 86, 0.2)',
            				strokeStyle: 'rgba(255, 206, 86, 1)',
            				lineWidth: 1,
            				hidden: false,          				
            			},
            			{
            				index: 3,
            				text: 'Good',
            				fillStyle: 'rgba(54, 162, 235, 0.2)',
            				strokeStyle: 'rgba(54, 162, 235, 1)',
            				lineWidth: 1,
            				hidden: false,
            			},
            			{
            				index: 4,
            				text: 'Very Good',
            				fillStyle: 'rgba(75, 192, 192, 0.2)',
            				strokeStyle: 'rgba(75, 192, 192, 1)',
            				lineWidth: 1,
            				hidden: false,
            			}
            			]
            		}
            		return [];
            	}
            },
            onClick: function(e, legendItem) {
            	//Legend Item Click Event
			}
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    max: 100,
                    stepSize: 10,
                    callback: function( value, index, values){
                    	return value + '%';
                    }
                },
                scaleLabel: {
                	display: true,
                	fontSize: 18,
                	labelString: 'Overall Vote Percentage'
                }
            }],
            xAxes: [{
            	gridLines: {
            		display: false
            	},
            	scaleLabel: {
            		display: true,
            		fontSize: 18,
            		labelString: 'Parties Acronym'
            	}
            }]
        }
    }
});
}
</script>
</body>
</html>