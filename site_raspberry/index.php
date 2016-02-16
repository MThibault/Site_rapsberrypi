<!DOCTYPE html>
<html>
	<head>
        <!-- En-tête de la page -->
		<?php include("./head.php"); ?>
	</head>
	
	<header>
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="index.php" class="brand">Raspberry Pi</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li class="active"><a href="./index.php">Accueil</a></li>
							<li><a href="./tutoriels.php">Tutoriels</a></li>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>
	</header>

	<body>
        <!-- Corps de la page -->
		<div class="container">    
			<div class="row-fluid">
				<div class="span12">		    	
					<div class="moncadre">
						<h1>Ma Raspberry Pi</h1>
						<p>
							Vous en avez surement déjà entendu parler. J'ai, à mon tour, fait l'aquisition d'une petite framboise cette année.<br />
							Je vais, sur ce site, vous faire partager mes tests et mes tutoriels.
						</p>
					</div><br /><br />
				</div>
			</div>	
        	<div class="row-fluid">
        		<div class="span12">
        			<div class="carousel slide span8 offset2" id="MyCarousel">
        				<ol class="carousel-indicators">
        					<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
 							<li data-target="#myCarousel" data-slide-to="1"></li>
							<li data-target="#myCarousel" data-slide-to="2"></li>
							<li data-target="#myCarousel" data-slide-to="3"></li>
							<li data-target="#myCarousel" data-slide-to="4"></li>
							<li data-target="#myCarousel" data-slide-to="5"></li>
							<li data-target="#myCarousel" data-slide-to="6"></li>
							<li data-target="#myCarousel" data-slide-to="7"></li>
							<li data-target="#myCarousel" data-slide-to="8"></li>
							<li data-target="#myCarousel" data-slide-to="9"></li>
						</ol>
				        <div class="carousel-inner">
				            <div class="active item"><a href="img/photo/rasp1.jpg"><img src="img/photo/rasp1.jpg" alt="Photo raspberry" width="900" height="500" /></a></div>
				    		<div class="item"><a href="img/photo/rasp2.jpg"><img src="img/photo/rasp2.jpg" alt="Photo raspberry" width="900" height="500" /></a></div>
					    	<div class="item"><a href="img/photo/rasp3.jpg"><img src="img/photo/rasp3.jpg" alt="Photo raspberry" width="900" height="500" /></a></div>
							<div class="item"><a href="img/photo/rasp4.jpg"><img src="img/photo/rasp4.jpg" alt="Photo raspberry" width="900" height="500" /></a></div>
							<div class="item"><a href="img/photo/rasp5.jpg"><img src="img/photo/rasp5.jpg" alt="Photo raspberry" width="900" height="500" /></a></div>
							<div class="item"><a href="img/photo/rasp6.jpg"><img src="img/photo/rasp6.jpg" alt="Photo raspberry" width="900" height="500" /></a></div>
							<div class="item"><a href="img/photo/rasp7.jpg"><img src="img/photo/rasp7.jpg" alt="Photo raspberry" width="900" height="500" /></a></div>
							<div class="item"><a href="img/photo/rasp8.jpg"><img src="img/photo/rasp8.jpg" alt="Photo raspberry" width="900" height="500" /></a></div>
							<div class="item"><a href="img/photo/rasp9.jpg"><img src="img/photo/rasp9.jpg" alt="Photo raspberry" width="900" height="500" /></a></div>
							<div class="item"><a href="img/photo/rasp10.jpg"><img src="img/photo/rasp10.jpg" alt="Photo raspberry" width="900" height="500" /></a></div>
						</div>
						<a class="carousel-control left" data-slide="prev" href="#MyCarousel">‹</a>
						<a class="carousel-control right" data-slide="next" href="#MyCarousel">›</a>
					</div>
				</div>
			</div>
		</div> <!-- /container -->
	</body>
	
	<footer>
	<!-- Pied de la page -->
		<?php include("./footer.php"); ?>
	</footer>
	
</html>

