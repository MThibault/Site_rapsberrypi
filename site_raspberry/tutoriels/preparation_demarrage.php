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
						<h2>Préparation et Démarrage</h2>
						<p>
							Après de nombreux essais et formatage, je vous expose ici une technique qui marche :)<br /><br />

							Une fois que vous avez reçu votre Raspberry PI, il faut préparer la carte SD afin de pouvoir l'utiliser.<br /><br />

							Vous pouvez choisir un grand nombre de distribution, cela dépendra de vos compétences et de vos besoins.<br />
							Voici le site officiel sur lequel vous trouverez certains distributions. Pour ma part, je test le Raspbian "wheezy".<br />
							http://www.raspberrypi.org/downloads<br /><br />

							Vous trouverez aussi une distribution orientée vers XBMC. Je ne l'ai pas encore testé mais je la trouve assez intéressante.<br />
							http://xbian.org/<br /><br />

							Passons à la préparation de votre carte mémoire. Je recommande une carte d'au moins 4Go (c'est le minimum selon moi mais tout dépend de votre utilisation. Cette carte doit être au minimum de classe 6 pour un minimum de confort.<br /><br />

							Sur votre ordinateur, la première chose à faire est de décompresser l'archive de l'OS que vous venez de télécharger.<br />
							Vous obtenez un .img<br /><br />
				
							<span class="underline">Sous Windows :</span><br />
							Télécharger l'utilitaire <span class="bold">win32diskimager</span> : <a href="http://sourceforge.net/projects/unixtips/files/win32diskimager-binary.zip/stats/timeline">Cliquez ici</a><br />
							Dézipper le dossier.<br /><br />

							Connectez votre carte SD à votre ordinateur (lecteur de carte SD intégré ou externe)<br />
							Lancer Win32DiskImager.exe<br /><br />

							Dans Image File, allez chercher le .img que vous avez récupérer un peu plus tot.<br />
							Dans Device, choisissez la lettre de votre carte SD.<br />
							Puis cliquez sur Write.<br />
							Après quelques minutes, le logiciel aura écrit les fichiers sur votre carte et vous pourrez la retirer.<br /><br />

							<span class="underline">Sous Linux :</span><br />
							Insérer votre carte SD dans votre ordinateur (ou dans un lecteur externe).<br />
							Ouvrez un terminal pour vérifier que votre carte mémoire est bien reconnu en tapant :<br />	<br />
				
							<code>df</code><br /><br />
				
							Vous devez obtenir quelque chose comme cela :<br /><br />

							<q>
								Sys. fich.<span class="tab4"></span><span class="tab4"></span>
								1K-blocks<span class="tab4"></span>
								Util.<span class="tab4"></span>
								Disponible<span class="tab4"></span>
								Uti%<span class="tab4"></span>
								Monté sur<br />
								/dev/sda6<span class="tab4"></span><span class="tab4"></span>
								24606084<span class="tab4"></span>
								11791544<span class="tab2"></span>
								11564596<span class="tab4"></span>
								51%<span class="tab4"></span><span class="tab2"></span>
								/<br />
								udev<span class="tab4"></span><span class="tab4"></span><span class="tab2"></span>
								1909540<span class="tab4"></span><span class="tab2"></span>
								4<span class="tab4"></span>
								1909536<span class="tab4"></span>
								1%<span class="tab4"></span><span class="tab2"></span>
								/dev<br />
								tmpfs<span class="tab4"></span><span class="tab4"></span><span class="tab2"></span>
								768896<span class="tab4"></span><span class="tab2"></span>
								944<span class="tab4"></span>
								767952<span class="tab4"></span>
								1%<span class="tab4"></span><span class="tab2"></span>
								/run<br />
								...<br />
								/dev/sde1<span class="tab4"></span><span class="tab4"></span>
								57288<span class="tab4"></span><span class="tab2"></span>
								16872<span class="tab4"></span>
								40416<span class="tab4"></span>
								30%<span class="tab4"></span><span class="tab2"></span>
								/media/xxxxx/xxxxxxxxx<br />
								/dev/sde2<span class="tab4"></span><span class="tab4"></span>
								3807952<span class="tab4"></span>
								1622180<span class="tab4"></span>
								1992416<span class="tab4"></span>
								45%<span class="tab4"></span><span class="tab2"></span>
								/media/xxxxx/xxxxxxxx<br />
							</q><br /><br />
				
							On voit que la mienne est déjà préparé (sde1 et sde2 pour les deux partitions).<br />
							Vous vous devriez avoir une seule ligne avec la taille de votre carte.<br />
							Exemple:<br /><br />
	
							<q>
								/dev/sde1<span class="tab4"></span><span class="tab4"></span>
								4096<span class="tab4"></span><span class="tab2"></span>
								32<span class="tab4"></span><span class="tab2"></span>
								4064<span class="tab4"></span>
								1%<span class="tab4"></span><span class="tab2"></span>
								/media/NomUtilisateur/NomCarte
							</q><br /><br />

							Retenez bien cette ligne, elle va nous servir.<br />
							Nous allons commencer par démonter la partition (dans mon cas):<br />
							<code>sudo umount /dev/sde1</code><br /><br />

							On peut retaper la commande "df" pour vérifier que notre partition a bien été démontée.<br /><br />

							Nous allons maintenant copier notre image sur la carte, soyez bien attentif, cette étape est très importante.<br /><br />

							<code>sudo dd if=CheminVersLe.img of=VotreCarte</code><br /><br />

							Dans mon cas ca donne:<br /><br />

							<code>sudo dd if=Document/Raspberry_PI/2012-12-16-wheezy-raspbian.img of=/dev/sde</code><br /><br />

							Vous remarquerez que je ne donne pas, en destination, la partition (sde1) mais la carte (sde).<br />
							Il faut absolument respecter ce point. Si vous mettez la partition en destination, il est possible que vous rencontriez des soucis.<br /><br />

							La copie peut prendre un petit moment. Puis vous pourrez retirer votre carte.<br /><br />

							Nous allons passer au moment que vous attendez tous, Le Premier Démarrage.<br />
							Soit vous vous connectez en ssh via putty par exemple, soit vous vous branchez directement sur un écran.<br />
							Pour ce qui se connecte en ssh, je vous ferais un tuto pour obtenir le mode graphique à distance.<br /><br />

							Branchez la carte mémoire sur votre Raspberry, branchez le câble Ethernet, puis l'alimentation.<br />
							Pour trouver l'adresse IP de votre Raspberry, vous pouvez vous connecter à l'interface de gestion de votre Box.<br /><br />

							En ssh, tapez :<br />

							<code>ssh pi@IPduRaspberry</code><br />

							Le mot de passe lors de la première connexion est : raspberry.<br /><br />

							Si vous ne connaissez pas l'adresse IP, vous pouvez, sur votre réseau local, tapez:<br />

							<code>ssh pi@raspberrypi</code><br />

							Vous l'aurez compris, votre nom d'utilisateur est : pi.<br /><br />
			
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
