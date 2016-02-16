<!DOCTYPE html>
<html>
	<head>
        <!-- En-tête de la page -->
		<?php include("../head.php"); ?>
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
					<a href="../index.php" class="brand">Raspberry Pi</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li><a href="../index.php">Accueil</a></li>
							<li><a href="../tutoriels.php">Tutoriels</a></li>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>
	</header>
	
	<body>
		<div class="container">    
			<div class="row-fluid">
				<div class="span12">
					<div class="moncadre">
						<h1>Modifier les droits de l'utilisateur pi</h1>
						<p>							
							Vous l'avez surement remarqué, l'utilisateur pi peut prendre les droits root sans avoir à taper de mot de passe.<br />
							Si, comme moi, cela vous dérange, nous allons voir comment modifier cela.<br /><br />
							
							Taper dans un terminal ou une console :<br />
							<code>visudo</code><br /><br />
							
							Chercher la ligne :<br />
							<q>pi<span class="tab2"></span>ALL=(ALL)<span class="tab2"></span>NOPASSWD: ALL</q>
							Et remplacer la par :
							<q>pi<span class="tab2"></span>ALL=(ALL)<span class="tab2"></span>ALL</q><br />
							
							Vous pouvez aussi demander à ce que le mot de passe attendu lors de l'utilisation de sudo soit celui de root.<br />
							Pour cela, rajouter :<br />
							<q>Defaults<span class="tab2"></span>rootpw</q><br />
							Pour ma part, je vais surement supprimer sudo une fois le RPi en place, car hormis pour faire des mises à jours, cela ne devrait pas trop bouger.<br /><br />
							
							<a href="../tutoriels.php">Retour</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</body>
	
	<footer>
	<!-- Pied de la page -->
		<?php include("../footer.php"); ?>
	</footer>
    
</html>
