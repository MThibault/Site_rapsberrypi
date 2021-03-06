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
						<h1>Sécuriser sa connexion SSH</h1>
						<p>
							Il est important de sécuriser son accès SSH. En effet, cet accès vous permet de vous connecter à votre serveur et d'en prendre le controle.<br />
							Je ne vais pas vous faire un topos sur la nécessité de bien choisir son mot de passe, afin qu'il ne soit pas trop simple, et donc facilement cassable.<br /><br />
							
							Dans ce tutoriel, nous allons changer le port utilisé pour se connecter en SSH, car n'importe qui sait que par défaut il s'agit du 22. Ensuite, nous empêcherons la connexion SSH en tant que root. Il vous faudra donc vous connecter avec un autre utilisateur, puis utiliser la commande "su root" et taper le mot de passe de root. Puis nous verrons comment choisir les utilisateurs autorisés à se connecter en SSH.<br /><br />
							
							Pour effectuer ses modifications, vous devez vous connecter en tant que root, ou utiliser sudo.<br />
							On va ouvrir le fichier de configuration SSH.<br />
							<code>nano /etc/ssh/sshd_config</code><br />
							Vous pouvez effectuer une recherche en utilisant, dans nano, ceci:<br />
							<q>ctrl + w</q><br /><br />
							
							Première chose, trouver la ligne Port 22.<br />
							Vous pouvez choisir un port un peu plus exotique, en prenant garde d'utiliser de préférence un port libre, non utilisé.<br /><br />
					
							Maintenant, nous allons bloquer la connexion avec le compte root.<br />
							Chercher la ligne :<br />
							<q>PermitRootLogin yes</q>
							et remplacer la par :<br />
							<q>PermitRootLogin no</q>
							Vous venez d'empécher root de créer une connexion SSH.<br /><br />
							
							Nous allons restreindre l'accès SSH à certains utilisateurs.<br />
							Chercher la ligne :<br />
							<q>AllowUsers</q>
							Il y a de fortes chances pour qu'elle n'existe pas. Dans ce cas, vous pouvez la créer en dessous de PermitRootLogin.<br />
							Elle se construit comme ceci :<br />
							<q>AllowUsers pseudo1 pseudo2</q>
							Ces utilisateurs seront ainsi autorisés à se connecter en SSH.<br /><br />
							
							Nous allons empêcher la connexion avec des mots de passe vides.<br />
							Modifier la ligne suivante comme ceci :<br />
							<q>PermitEmptyPasswords no</q><br /><br />
							
							Vérifier aussi que le protocole que vous utilisez est :
							<q>Protocol 2</q><br />
							La valeur 2 correspond au protocole SSH2. En effet, SSH peut utiliser deux protocoles, SSH1 et SSH2, le second étant réputé plus fiable.<br /><br />
							
							Vous pouvez aussi modifier la valeur du laps de temps (en secondes) au bout duquel l'utilisiteur sera déconnecté s'il ne parvient pas à s'identifier.<br />
							<q>LoginGraceTime 120</q><br /><br />
							
							Une autre petite sécurité peut être d'activer MaxStartups. Cela vous permet d'attribuer un nombre d'essai puis de bannir l'IP.<br />
							On va commencer par décommenter (enlever le # devant les lignes) ces deux lignes :<br />
							<q>
								MaxStartups AA:BB:CC<br />
								Banner /etc/issue.net
							</q>
							AA permet de donner le nombre de tentatives.<br />
							CC indique le nombre de tentatives avant bannissement.<br />
							BB donne le pourcentage de chances de se voir refuser une nouvelle connexion. Cette probabilité augmente linéairement jusqu'à atteindre le nombre maximal de connexions.<br /><br />
							
							On va maintenant relancer le daemon SSH afin qu'il prenne en compte notre nouvelle configuration.<br />
							<code>/etc/init.d/ssh restart</code><br />
							<q class="important">
								<span class="underline bold">!!!ATTENTION!!!</span><br />
								Ne fermez pas votre connexion avant d'avoir essayer de vous reconnecter avec une autre session SSH.<br />
								Si vous ne faites pas le test, il est possible que vous ne parveniez plus à vous connecter. Il vous faudra donc avoir un accès physique à la machine ou la formater et la réinstaller.
							</q><br /><br />
							
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
