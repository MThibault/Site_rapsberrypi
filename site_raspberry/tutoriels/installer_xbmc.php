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
						<h2>Installer XBMC</h2>
						<p>
							Voici un nouveau tuto vous permettant d'installer Xbmc sur votre petite framboise.<br /><br />

							On commence.<br />
							<code>
								sudo apt-get update<br />
								sudo apt-get upgrade
							</code><br /><br />
				
							On regarde notre version du raspbian :<br />

							<code>uname -a</code><br /><br />

							Si vous etes en 3.2, je vous conseille de faire :<br />

							<code>sudo apt-get dist-upgrade</code><br />

							Vous allez ainsi etre en 3.6 comme moi.<br />
							On redémarre puis on vérifie qu'on a bien la bonne version :<br />

							<code>
								sudo reboot<br />
								uname -a
							</code><br /><br />


							Voici le tuto dont je m'inspire et qui m'a permis de comprendre une erreur que j'avais (je remercie son auteur) : michael.gorven.za.net/raspberrypi/xbmc<br /><br />
							Nous allons rajouter une source :<br />

							<code>sudo nano /etc/apt/sources.list.d/mene.list</code><br /><br />

							On rajoute la ligne :<br />

							<q>deb http://archive.mene.za.net/raspbian wheezy contrib</q><br />

							et on enregistre avec :<br />

							<q>ctrl +x</q><br /><br /><br />


							Ensuite, on récupère la clé pour utiliser l'archive :<br />

							<code>sudo apt-key adv --keyserver keyserver.ubuntu.com --recv-key 5243CDED</code><br /><br />


							On met a jour le cache, on installe xbmc, et on le lance :<br />

							<code>
								sudo apt-get update<br />
								sudo apt-get install xbmc<br />
								xbmc
							</code><br /><br />


							Si vous avez créé un utilisateur, et que vous voulez que ce soit avec lui que vous souhaitez utiliser XBMC, il faut le rajouter aux groupes suivants : <br />
							<q>audio video input dialout plugdev tty</q><br />

							<code>sudo adduser utilisateur audio</code>
							A refaire pour chacun des groupes cités précédemment.<br /><br />


							Profitez en bien, vous pouvez lire des 1080P sans soucis :)<br />
							Contrairement à ce que j'ai pu lire, le RPi et XBMC sont capables de décoder/lire le DTS HD-MA. J'ai fait l'essai, ca a un peu laguer une fois ou deux le temps de mettre en cache puis plus rien durant le film. Je verrais si l'on peut régler la mise en cache à l'occasion.<br /><br />
			
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
