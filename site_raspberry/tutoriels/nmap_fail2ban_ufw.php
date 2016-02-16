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
						<h1>Installer un firewall sur son Raspberry Pi</h1>
						<p>
							Voici un nouveau petit tuto pour vous faire part des nouveautés que je viens de mettre en place sur mon Raspberry Pi.<br />
							Par sécurité, on va protéger un minimum notre framboise.<br /><br />
							
							Ici, nous allons commencer par installer nmap afin de pouvoir d'analyser le trafic sur notre serveur. Ensuite nous installerons fail2ban afin de se protéger contre les tentatives d'intrusion (brute-force, dictionnaire, DDOS). Puis nous terminerons en installant un Firewall.<br /><br />
							
							La première chose à faire, comme toujours, est de mettre à jour les paquets :<br />
							<code>
								apt-get update<br />
								apt-get upgrade
							</code><br />
						</p>
						
						<h2>Analyser son trafic avec NMAP</h2>
						<p>							
							On va d'abord installer notre outil :<br />
							<code>apt-get install nmap</code><br /><br />
							
							Ici, nous allons l'utiliser simplement, en regardant simplement les ports utilisés par notre RPi :<br />
							<code>nmap adresse_ip</code><br />
							<q class="info">A noter que vous pouvez utiliser une adresse IP "interne" si vous travaillez en local.</q><br />
							Vous vous retrouvez avec quelque chose de cette forme :<br />
							<q>
								root@raspberrypi:/home/pi# nmap XXX.XXX.XXX.XXX<br /><br />

								Starting Nmap 6.00 ( http://nmap.org ) at 2013-05-28 01:14 CEST<br />
								Nmap scan report for raspberrypi.lan (XXX.XXX.XXX.XXX)<br />
								Host is up (0.00012s latency).<br />
								Not shown: 996 closed ports<br />
								PORT     STATE SERVICE<br />
								80/tcp   open  http<br />
								443/tcp  open  https<br />
								XX/tcp	 open  vnc-1<br />
								XX/tcp   open  X11:1<br /><br />

								Nmap done: 1 IP address (1 host up) scanned in 1.13 seconds
							</q><br />
							Voici donc la liste des ports en écoute sur notre RPi, mais aussi le service concerné, ce qui est très pratique pour connaitre l'utilité du port. Cela nous sera d'ailleurs très utile lors de la configuration du firewall.<br /><br />
							
							Dans ce tableau, vous trouverez une liste loin d'être exhaustive des ports les plus courants.<br />
							<table>
								<caption>Liste de ports courants</caption>
								<tr>
									<td>Service</td>
									<td>Protocole</td>
									<td>N° de port</td>
								</tr>
								<tr>
									<td>HTTP</td>
									<td>TCP</td>
									<td>80</td>
								</tr>
								<tr>
									<td>HTTPS</td>
									<td>TCP</td>
									<td>443</td>
								</tr>
								<tr>
									<td>SSH</td>
									<td>TCP</td>
									<td>22 (à moins que vous l'ayez changer comme conseillé dans un autre de mes tutos)</td>
								</tr>
								<tr>
									<td>FTP</td>
									<td>TCP</td>
									<td>20 & 21</td>
								</tr>
								<tr>
									<td>SMTP (mail)</td>
									<td>TCP</td>
									<td>25</td>
								</tr>
								<tr>
									<td>POP3 (mail)</td>
									<td>TCP</td>
									<td>110</td>
								</tr>
								<tr>
									<td>IMAP (mail)</td>
									<td>TCP</td>
									<td>143</td>
								</tr>
								<tr>
									<td>DNS</td>
									<td>TCP/UDP</td>
									<td>53</td>
								</tr>
							</table>
						</p>
						
						<h2>Protéger ses ports ouverts avec Fail2Ban</h2>
						<p>
							Fail2Ban va nous permettre de protéger les ports ouverts contre d'éventuelles petites attaques comme le Brute-Force ou le DDOS.<br />
							Fail2Ban va ainsi nous donner des logs que nous pourrons analyser. En cas d'attaques, il pourra bannir des IPs grâce à IPTables.<br />
							De plus, il est assez léger.<br /><br />
							
							Maintenant, passons à l'installation :<br />
							<code>apt-get install fail2ban</code><br /><br />
							
							Puis la configuration :<br />
							<code>nano /etc/fail2ban/jail.conf</code><br />
							Je vous conseille de remplir les champs suivants :<br />
							<span class="bold">destemail :</span> En indiquant votre E-mail, vous recevrez des mails d'informations/d'alertes.<br />
							<span class="bold">bantime :</span> Permet de choisir le temps de bannissement des IPs.<br />
							<span class="bold">maxretry :</span> Donne le nombre de tentatives avant bannissement.<br /><br />
							
							Descendez plus bas dans le fichier pour trouver ceci :<br />
							<q>
								[ssh]<br /><br />

								enabled  = true<br />
								port     = ssh<br />
								filter   = sshd<br />
								logpath  = /var/log/auth.log<br />
								maxretry = 6<br /><br />

								[dropbear]<br /><br />

								enabled  = false<br />
								port     = ssh<br />
								filter   = sshd<br />
								logpath  = /var/log/dropbear<br />
								maxretry = 6
							</q><br />
							Si vous descendez encore, vous en trouverez d'autres. Ils vous permettent d'affiner vos paramètres. Vous pouvez ici préciser un nombre spécifique d'essais.<br />
							Pour ma part, j'ai aussi modifié [ssh-ddos] en l'activant.<br /><br />
							
							Voici d'autres paramètres que vous pouvez rajouter et que j'ai trouvé sur <a href="http://mondedie.fr/forum/8-tutos/3131-tuto-securisation-logs">ici</a> grâce au tuto de XciD69 dont j'ai repris quelques éléments.<br /><br />
							
							<q>
								#Pour lighttpd :<br /><br /> 
 
								[lighttpd]<br />
								enabled = true<br />
								port  = http,https<br />
								filter  = apache-auth<br />
								logpath = /var/log/lihttpd/*error.log<br />
								maxretry = 6 <br /><br />
 
								[lighttpd-noscript]<br />
								enabled = true<br />
								port    = http,https<br />
								filter  = apache-noscript<br />
								logpath = /var/log/lighttpd/*error.log<br />
								maxretry = 6 <br /><br />
 
								[lighttpd-overflows]<br />
								enabled = true<br />
								port    = http,https<br />
								filter  = apache-overflows<br />
								logpath = /var/log/lighttpd/*error.log<br />
								maxretry = 2
							</q><br /><br />
							
							Puis on redémarre afin de prendre en compte les modifications :<br />
							<code>/etc/init.d/fail2ban restart</code><br /><br />
							
							<q class="important">
								<span class="underline bold">!!!ATTENTION!!!</span><br />
								Avant de fermer votre terminal, il est très important de tester votre connexion pour vérifier que vous ne vous etes pas bloqués votre accès SSH (Et oui ça m'est déjà arrivé et c'est très embétant :/ ). Pour cela, ouvrez donc un autre terminal et relancer une connexion SSH. Si vous parvenez à vous connecter c'est que tout va bien, sinon c'est que votre configuration bloque la connexion. Dans ce cas, vérifiez bien que vous avez respecté le tuto, ou remettez la configuration de base.
							</q><br /><br /><br />
						</p>
							
							
						<h2>Le Firewall UFW (Uncompilated Firewall) (En cours)</h2>
						<p>
							UFW est un nouvel outil de configuration simplifié en ligne de commande de <a class="interwiki iw_wpfr" title="http://fr.wikipedia.org/wiki/Netfilter" href="http://fr.wikipedia.org/wiki/Netfilter">Netfilter</a>, qui donne une alternative à l'outil iptables. UFW devrait à terme permettre une configuration automatique du pare-feu lors de l'installation de programmes en ayant besoin. (Sources : doc.ubuntu-fr.org).<br /><br />
							
							<q class="info">Il existe une interface graphique vous permettant de gérer UFW, elle s'appelle Gufw. Je ne m'étendrais pas sur le sujet, elle est relativement simple à prendre en main.</q><br /><br />
						
							Passons à l'installation :(Pour ma part, je passe en root pour le tuto)<br />
							<code>apt-get install ufw</code><br /><br />
												
							Le parefeu n'est pas activé par défaut. Vous avez besoin des droits root en utilisant sudo ou avec l'utilisateur root.<br />
							Pour activer UFW :<br />
							<code>ufw enable</code><br />
							Et pour le désactiver :<br />
							<code>ufw disable</code><br /><br />
						
							Le première chose à faire est de bloquer tout le traffic :<br /> 
							<code>ufw default deny</code><br /> 
							Si jamais vous avez besoin de tout réouvrir pour faire des tests, ca peut etre utile de connaitre la commande suivante :<br />
							<code>ufw default allow</code><br /><br />
							
							Pour lister les règles mises en place :<br />
							<code>ufw status verbose</code><br />
							Et ceci pour afficher les règles avec leur numéro, cela sera très pratique pour supprimer une règle :<br />
							<code>ufw status numbered</code><br /><br />
							
							Activer ou désactiver la journalisation :<br />
							<code>
								ufw logging on<br />
								ufw logging off
							</code><br /><br />
							
							Nous allons voir maintenant comment ajouter des règles. Il y a deux types de règles de base, Autoriser/Refuser.<br />
							<code>
								ufw allow &ltrègle&gt<br />
								ufw deny &ltrègle&gt
							</code><br /><br />
							
							Pour supprimer une règle :<br />
							<code>ufw delete allow &ltrègle&gt</code><br />
							ou
							<code>ufw delete &ltNUM_regle&gt</code><br />							
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
