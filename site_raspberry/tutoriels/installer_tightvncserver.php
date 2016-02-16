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
						<h1>Un bureau à distance avec TightVNCserver</h1>
						<h2>Introduction</h2>

						<p>
							Quand vous travaillez sur un serveur, ici un Raspberry Pi, il peut être pratique d'obtenir à distance un accès graphique.<br />
							Pour les débutants en Linux, il faut reconnaitre que c'est assez pratique, pour les plus avertis, ca peut toujours servir. De plus, le raspberry offre quelques logiciels graphiques qu'il peut être intéressant de tester.<br /><br />
							
							Dans ce tutoriel, nous allons voir comment installer TightVNCserver, et ce qu'il faut faire pour l'utiliser en dehors du réseau local.<br /><br />
							
						</p>

						<h2>Installation</h2>

						<p>
							<code>
							apt-get install tightvncserver<br />
							tightvncserver
							</code><br />
							Vous devez choisir un mot de passe de 8 caractères maximums (si vous faites plus il sera tronqué) qui vous sera demandé pour accêder au bureau à distance.<br /><br />

							Ici je vous conseille de répondre non. Il s'agit de mettre en place un mot de passe pour l'accès en lecture seule.<br />
							<q>Would you like to enter a view-only password (y/n)?</q><br /><br />

							Ensuite, il vous faut un client VNC pour pouvoir déporter votre affichage. Vous avez pas mal de choix. Personnellement, j'utilise <a href="http://www.realvnc.com/download/viewer/">VNC Viewer</a>, mais il y a aussi un viewer en Java proposé sur le site de <a href="http://www.tightvnc.com/download.php">TightVNC</a>. Vous trouverez encore d'autres outils avec une petite recherche.<br /><br />
						</p>

						<h2>Déporter son affichage</h2>

						<p>
							Peu importe le logiciel que vous avez choisi, on va vous demander de renseigner l'adresse IP du serveur à joindre. Vous pouvez soit rentrer l'adresse IP si vous la connaissez, soit le nom de domaine.<br />
							Comme vous venez de lancer tightvncserver pour configurer le mot de passe, vous avez par la même occasion lancer une première instance de vncserver. De ce fait, pour vous connecter, il vous faut préciser l'instance que vous voulez joindre, en rajoutant ":1" à la fin de votre adresse IP ou de votre nom de domaine.<br /><br />
						</p>

						<h3>Se connecter sur un réseau local</h3>
						
						<p>
							Nous sommes dans le cas ou votre serveur est sur le même réseau que l'ordinateur sur lequel vous utilisez le viewer de votre choix. Il est conseillé d'attribuer une adresse IP fixe à votre serveur. Pour cela, il faut passer par l'interface de configuration de votre routeur et aller dans la partie DHCP. Vous trouverez des infos un peu plus bas.<br /><br />
							Sur un réseau local, cela va donc donner quelque chose comme :<br />
							<q>192.168.1.2:1</q><br /><br />

						</p>

						<h3>Se connecter hors du réseau local</h3>

						<p>
							Ici nous allons voir comment nous connecter quand nous ne sommes par sur le même réseau que le serveur. Par exemple, vous avez votre RPi chez vous, et vous voulez le joindre en étant au travail, ou en cours.<br />
La configuration va s'avérer être un peu plus complexe.<br /><br />

							Le début est semblable, vous allez ouvrir votre viewer, et rentrer l'adresse IP, mais cette fois, par l'adresse IP de votre raspberry car nous ne sommes plus sur le réseau local, mais l'adresse IP de votre routeur/box visible sur internet, et que vous pouvez trouver en vous rendant sur le site <a href="http://www.hostip.fr">hostip.fr</a>.<br />
							Comme tout à l'heure, il faut rajouter :1 pour joindre l'instance de vncserver.<br /><br />

							Malheureusement, ce n'est pas suffisant. Maintenant il faut se rendre sur la page de configuration de votre routeur/box afin de rediriger le flux extérieur vers votre serveur. Généralement cette page de configuration se trouve à l'adresse 192.168.0.1, ou 192.168.1.1. Mais ce ne sont pas les seules possibilités, cela dépend de votre routeur/box.<br />
							Une fois que vous avez trouvé cette page, il vous faut vous authentifier pour pouvoir modifier la configuration. Vous trouverez l'identifiant et le mot de passe sur votre box ou dans les papiers fournis avec.<br /><br />
							
							Passons à la configuration. Il faut chercher la partie qui s'appelle NAT/PAT, souvent située dans les paramètres avancés. Ici, on vous demande de donner un nom à cette règle. Ensuite, il vous faut spécifier le port externe, c'est à dire celui utilisé par vnc. Vous pouvez observer quels sont les ports utilisés grâce à la commande :<br />
							<q>netstat -tulpn</q><br />
							J'ai observé que la première instance de TightVNCserver utilisait deux ports, le 5901 et le 6001. La deuxième instance utilise les ports 5902 et 6002, et ainsi de suite.<br />
							Après vous spécifiez le port interne (je vous conseille de garder les mêmes car ce sont ceux attendu par VNC) et enfin de spécifier le périphérique concerné par la règle ou son IP.<br /><br />

							Voici un exemple :<br />
							<a href="../img/nat_orange.jpeg"><img src="../img/nat_orange.jpeg" alt="Exemple NAT box Orange"/></a>
							Source : http://feuillard.dyndns.org/spip.php?article50 .<br /><br />

							Maintenant que vous avez bien redirigé les ports, vous allez pouvoir vous connecter en dehors de votre réseau local.<br /><br />

						</p>

						<h2>Bonus</h2>

						<p>
							A l'avenir, si vous avez besoin d'ouvrir une nouvelle instance de VNC, ou après un redémarrage, il vous suffit de taper :<br />
							<code>vncserver</code><br /><br />

							Si vous voulez lancer une instance de VNC au démarrage du serveur, nous allons modifer le crontab :<br />
							<code>crontab -e </code><br />
							Et vous écrivez à la fin :<br />
							<q>@reboot vncserver</q><br /><br />


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
