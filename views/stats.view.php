<?php require 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<style>
		#container {
			display: flex;
			flex-direction: row;
		}
		.block-div {
			background-color: #ffffff;
			box-sizing: border-box;
			width: 25%;
			margin: 50px auto;
			padding: 50px;
		}
		ul {
			padding: 0;
		}
		li {
			padding-bottom: 20px;
		}
		li a {
			text-decoration: none;
			color: #000000;
		}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
	<div id="container">
	<div class="block-div">	 
		<h3 style="color: #505050;">Test matches</h3>
		<hr style="background-color: #E0E0E0; height: 1px; border: 0;">
	  	<ul>
		    <li><button id="test1">Team records</button>
		    	<ul id="testdrop1" style="list-style-type: none;">
			        <li><a href="statmtype.php?mtype=1&qtype=0&qno=0">Most wins</a></li>
			        <li><a href="statmtype.php?mtype=1&qtype=0&qno=1">Highest innings totals</a></li>
			        <li><a href="statmtype.php?mtype=1&qtype=0&qno=2">Lowest innings totals</a></li>
			    </ul>
		    </li>
		    <li><button id="test2">Batting records</button>
				<ul id="testdrop2" style="list-style-type: none;">
			        <li><a href="statmtype.php?mtype=1&qtype=1&qno=0">Most runs in career</a></li>
			        <li><a href="statmtype.php?mtype=1&qtype=1&qno=1">Most runs in an innings</a></li>
			        <li><a href="statmtype.php?mtype=1&qtype=1&qno=2">Most sixes in career</a></li>
			    </ul>
		    </li>
		    <li><button id="test3">Bowling records</button>
				<ul id="testdrop3" style="list-style-type: none;">
			        <li><a href="statmtype.php?mtype=1&qtype=2&qno=0">Most wickets in career</a></li>
			        <li><a href="statmtype.php?mtype=1&qtype=2&qno=1">Most runs conceded in career</a></li>
			        <li><a href="statmtype.php?mtype=1&qtype=2&qno=2">Most runs conceded in an innings</a></li>
			    </ul>
		    </li>
	  	</ul>
	</div> 

	<div class="block-div">	 
		<h3 style="color: #505050;">One-Day Internationals</h3>
		<hr style="background-color: #E0E0E0; height: 1px; border: 0;">
	  	<ul>
		    <li><button id="odi1">Team records</button>
		    	<ul id="odidrop1" style="list-style-type: none;">
			        <li><a href="statmtype.php?mtype=2&qtype=0&qno=0">Most wins</a></li>
			        <li><a href="statmtype.php?mtype=2&qtype=0&qno=1">Highest innings totals</a></li>
			        <li><a href="statmtype.php?mtype=2&qtype=0&qno=2">Lowest innings totals</a></li>
			    </ul>
		    </li>
		    <li><button id="odi2">Batting records</button>
				<ul id="odidrop2" style="list-style-type: none;">
			        <li><a href="statmtype.php?mtype=2&qtype=1&qno=0">Most runs in career</a></li>
			        <li><a href="statmtype.php?mtype=2&qtype=1&qno=1">Most runs in an innings</a></li>
			        <li><a href="statmtype.php?mtype=2&qtype=1&qno=2">Most sixes in career</a></li>
			    </ul>
		    </li>
		    <li><button id="odi3">Bowling records</button>
				<ul id="odidrop3" style="list-style-type: none;">
			        <li><a href="statmtype.php?mtype=2&qtype=2&qno=0">Most wickets in career</a></li>
			        <li><a href="statmtype.php?mtype=2&qtype=2&qno=1">Most runs conceded in career</a></li>
			        <li><a href="statmtype.php?mtype=2&qtype=2&qno=2">Most runs conceded in an innings</a></li>
			    </ul>
		    </li>
	  	</ul>
	</div> 

	<div class="block-div">	 
		<h3 style="color: #505050;">Twenty20 Internationals</h3>
		<hr style="background-color: #E0E0E0; height: 1px; border: 0;">
	  	<ul>
		    <li><button id="t201">Team records</button>
		    	<ul id="t20drop1" style="list-style-type: none;">
			        <li><a href="statmtype.php?mtype=3&qtype=0&qno=0">Most wins</a></li>
			        <li><a href="statmtype.php?mtype=3&qtype=0&qno=1">Highest innings totals</a></li>
			        <li><a href="statmtype.php?mtype=3&qtype=0&qno=2">Lowest innings totals</a></li>
			    </ul>
		    </li>
		    <li><button id="t202">Batting records</button>
				<ul id="t20drop2" style="list-style-type: none;">
			        <li><a href="statmtype.php?mtype=3&qtype=1&qno=0">Most runs in career</a></li>
			        <li><a href="statmtype.php?mtype=3&qtype=1&qno=1">Most runs in an innings</a></li>
			        <li><a href="statmtype.php?mtype=3&qtype=1&qno=2">Most sixes in career</a></li>
			    </ul>
		    </li>
		    <li><button id="t203">Bowling records</button>
				<ul id="t20drop3" style="list-style-type: none;">
			        <li><a href="statmtype.php?mtype=3&qtype=2&qno=0">Most wickets in career</a></li>
			        <li><a href="statmtype.php?mtype=3&qtype=2&qno=1">Most runs conceded in career</a></li>
			        <li><a href="statmtype.php?mtype=3&qtype=2&qno=2">Most runs conceded in an innings</a></li>
			    </ul>
		    </li>
	  	</ul>
	</div> 
	</div>



	<script>
		$(document).ready(function() {
			$('#testdrop1').hide();
			$('#testdrop2').hide();
			$('#testdrop3').hide();
			$("#test1").click(function () {
				if($('#testdrop1').is(':hidden'))
				{
					$('#testdrop1').show();
				} 
				else 
				{
					$('#testdrop1').hide();
				}   			
  			});
  			$("#test2").click(function () {
				if($('#testdrop2').is(':hidden'))
				{
					$('#testdrop2').show();
				} 
				else 
				{
					$('#testdrop2').hide();
				}   			
  			});
  			$("#test3").click(function () {
				if($('#testdrop3').is(':hidden'))
				{
					$('#testdrop3').show();
				} 
				else 
				{
					$('#testdrop3').hide();
				}   			
  			});

  			$('#odidrop1').hide();
			$('#odidrop2').hide();
			$('#odidrop3').hide();
			$("#odi1").click(function () {
				if($('#odidrop1').is(':hidden'))
				{
					$('#odidrop1').show();
				} 
				else 
				{
					$('#odidrop1').hide();
				}   			
  			});
  			$("#odi2").click(function () {
				if($('#odidrop2').is(':hidden'))
				{
					$('#odidrop2').show();
				} 
				else 
				{
					$('#odidrop2').hide();
				}   			
  			});
  			$("#odi3").click(function () {
				if($('#odidrop3').is(':hidden'))
				{
					$('#odidrop3').show();
				} 
				else 
				{
					$('#odidrop3').hide();
				}   			
  			});

  			$('#t20drop1').hide();
			$('#t20drop2').hide();
			$('#t20drop3').hide();
			$("#t201").click(function () {
				if($('#t20drop1').is(':hidden'))
				{
					$('#t20drop1').show();
				} 
				else 
				{
					$('#t20drop1').hide();
				}   			
  			});
  			$("#t202").click(function () {
				if($('#t20drop2').is(':hidden'))
				{
					$('#t20drop2').show();
				} 
				else 
				{
					$('#t20drop2').hide();
				}   			
  			});
  			$("#t203").click(function () {
				if($('#t20drop3').is(':hidden'))
				{
					$('#t20drop3').show();
				} 
				else 
				{
					$('#t20drop3').hide();
				}   			
  			});
		});
	</script>
	
</body>
</html>