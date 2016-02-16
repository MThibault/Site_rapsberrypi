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
					<a href="./index.php" class="brand">Raspberry Pi</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li><a href="./index.php">Accueil</a></li>
							<li class="active"><a href="./tutoriels.php">Tutoriels</a></li>
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
						<h1>Mes tutoriels pour Raspberry Pi</h1>
						<p>
							J'espère que vous trouverez des informations qui vous aideront.<br />
							J'ai passé beaucoup de temps à faire des tests pour installer ce que je vais vous montrer. J'utilise toujours le même protocole, j'effectue mes tests sur une petite carte SD. Une fois que c'est fonctionnel, je l'intègre sur ma carte SD "finale".
						</p>
		
						<ul>
							<li><a href="./tutoriels/preparation_demarrage.php">Préparation et Démarrage</a></li>
							<li><a href="./tutoriels/premiers_pas.php">Premiers Pas</a></li>
							<li><a href="./tutoriels/installer_xbmc.php">[Installation] XBMC</a></li>
							<li><a href="./tutoriels/installer_tightvncserver.php">[Installation] Un bureau à distance avec TightVNCserver</a></li>
							<li><a href="./tutoriels/installer_lighttpd_rtorrent_rutorrent.php">[Installation] Lighttpd et rtorrent avec interface Rutorrent</a></li>
							<li><a href="./tutoriels/installer_nginx_rtorrent_rutorrent.php ">[Installation] Nginx et rtorrent avec une interface Rutorrent</a></li>
							<li><a href="./tutoriels/installer_owncloud.php">[Installation] Installer Owncloud avec Nginx</a></li>
							<li><a href="./tutoriels/installer_samba.php">[Installation] Installer Samba</a></li>
							<li><a href="./tutoriels/securisation_ssh.php">[Sécurité] Sécuriser sa connexion SSH</a></li>
							<li><a href="./tutoriels/droits_pi.php">[Sécurité] Modifier les droits de l'utilisateur pi</a></li>
							<li><a href="./tutoriels/nmap_fail2ban_ufw.php">[Sécurité] Installer NMAP, Fail2Ban, et le Firewall UFW</a></li>

						</ul>
					</div>
				</div>
			</div>
		</div>
	</body>
		
	<footer>
	<!-- Pied de la page -->
		<?php include("./footer.php"); ?>
	</footer>
	    
</html>
