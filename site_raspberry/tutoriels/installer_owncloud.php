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

						<p><b>MAJ :</b> 23/12/2014. Simplification de l'installation de Nginx. Changement de dossier pour les sites Web. Modification du fichier de configuration. Ajout de MySQL</p>
						<p><b>MAJ :</b> 20/01/2015. Corrections de quelques bugs du tuto.</p>

						<h1>Installer Owncloud sous Nginx</h1>
						<p>							
							Vous en avez surement déjà entendu parler. Owncloud est une sorte de Dropbox qu'on peut mettre sur son propre serveur histoire d'avoir son cloud personnel, sans avoir à passer par une entreprise tierce.<br />
							Nous allons voir ici comment l'installer et le configurer.<br />
							Ce tuto n'est pas propre au Raspberry Pi, il peut donc être mi en place sur n'importe quelle machine.<br /><br />
						</p>
						
						<h2>Installer Nginx</h2>

						<p>Pour faire fonctionner la majorité des commandes de ce tutoriel, vous devez soit être root, soit écrire sudo devant ces commandes, notamment quand on vous dit que vous n'avez pas les droits, ou "permission non accordée".</p>
						
						<p>
							Alors ca c'est assez facile.<br />
							
							Taper dans un terminal ou une console :<br />
							<code>apt-get install nginx-light php5-fpm php5-gd php5-curl</code><br /><br />
							
						</p>

						<h2>Installation d'Owncloud et Configuration</h2>

						<p>
							On attaque maintenant la partie qui nous intéresse.<br />

							On va maintenant se déplacer dans le dossier /srv/http/. S'il n'existe pas, nous devrons le créer.<br /><br />

							<code>cd /srv/http</code><br /><br />
							Si le dossier http n'existe pas, nous allons le créer avec :<br/>
							<code>mkdir /srv/http</code><br/>
							Vous devrez utiliser sudo si vous n'avez pas les droits root.<br/>
							Ensuite effectuez la commande cd précédente.<br /><br/>

							Ensuite on va télécharger la dernière version d'Owncloud.<br />
							<code>
								cd /tmp <br />
								wget https://download.owncloud.org/community/owncloud-8.2.2.tar.bz2<br />
								tar jxvf owncloud-8.2.2.tar.bz2<br/>
								mv owncloud /srv/http/<br/>
								chown www-data:www-data -R /srv/http/owncloud/<br/>
								rm owncloud-8.2.2.tar.bz2
							</code><br /><br />

							On va créer les certificats dans le répertoire prévu à cet effet : /etc/ssl/certs.<br />
							<code> cd /etc/ssl/certs</code><br />
							On va maintenant générer les certificats. Ceci sont valables 365 jours, il faudra donc les générer de nouveau au bout d'un an. De plus, utiliser un mot de passe fort.<br />
							<code>
								openssl genrsa -des3 -out owncloud.key 2048<br />
								openssl req -new -key owncloud.key -out owncloud.csr<br />
								cp owncloud.key owncloud.key.org<br />
								openssl rsa -in owncloud.key.org -out owncloud.key<br />
								openssl x509 -req -days 365 -in owncloud.csr -signkey owncloud.key -out owncloud.crt<br />
								rm owncloud.csr owncloud.key.org
							</code><br /><br />
							
							Maintenant nous allons créer un fichier, et y coller cette configuration :<br />
							<code>nano /etc/nginx/sites-available/owncloud.conf</code><br />
							<q>
								upstream php-handler {<br/>
									#server 127.0.0.1:9000;<br/>
									server unix:/var/run/php5-fpm.sock;<br/>
								}<br/><br/>

								#server {<br/>
								#	listen 8082;<br/>
								#	server_name _;<br/>
									# Enforce https<br/>
								#	return 301 https://$server_name$request_uri;<br/>
								#}<br/><br/>

								server {<br />
									listen 8082 ssl;<br/>
									server_name _;<br/>
									root /srv/http/owncloud;<br/>
									index index.php index.html;<br/><br/>

									ssl_certificate /etc/ssl/certs/owncloud.crt;<br/>
									ssl_certificate_key /etc/ssl/certs/owncloud.key;<br/><br/>

									# set max upload size<br/>
									client_max_body_size 10G;<br/>
									fastcgi_buffers 64 4K;<br/><br/>

						 			rewrite ^/caldav(.*)$ /remote.php/caldav$1 redirect;<br/>
									rewrite ^/carddav(.*)$ /remote.php/carddav$1 redirect;<br/>
									rewrite ^/webdav(.*)$ /remote.php/webdav$1 redirect;<br/><br/>

			 						error_page 403 /core/templates/403.php;<br/>
		 							error_page 404 /core/templates/404.php;<br/><br/>

									location / {<br/>
										# The following 2 rules are only needed with webfinger<br/>
										rewrite ^/.well-known/host-meta /public.php?service=host-meta last;<br/>
										rewrite ^/.well-known/host-meta.json /public.php?service=host-meta-json last;<br/><br/>

										rewrite ^/.well-known/carddav /remote.php/carddav/ redirect;<br/>
										rewrite ^/.well-known/caldav /remote.php/caldav/ redirect;<br/><br/>

										rewrite ^(/core/doc/[^\/]+/)$ $1/index.html;<br/><br/>

										try_files $uri $uri/ /index.html;<br/>
									}<br/><br/>

									location ~ \.php(?:$|/) {<br/>
										#fastcgi_pass unix:/var/run/php5-fpm.sock;<br/>
										#fastcgi_index index.php;<br/>
										#fastcgi_param SCRIPT_FILENAME /srv/http$fastcgi_script_name;<br/>
										#include fastcgi_params;<br/><br/>
																		
										fastcgi_split_path_info ^(.+\.php)(/.+)$;<br/>
										include fastcgi_params;<br/>
										fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;<br/>
										fastcgi_param PATH_INFO $fastcgi_path_info;<br/>
										fastcgi_param HTTPS on;<br/>
										fastcgi_pass php-handler;<br/>
									}<br/><br/>

									location = /robots.txt {<br/>
										allow all;<br/>
										log_not_found off;<br/>
										access_log off;<br/>
									}<br/><br/>

									location ~ ^/(?:\.htaccess|data|config|db_structure\.xml|README){<br/>
										deny all;<br/>
									}<br/>						
								}
							</q><br /><br />

								Ensuite, on crée un lien symbolique.<br />
								<code>ln -s /etc/nginx/sites-available/owncloud.conf /etc/nginx/sites-enabled/owncloud.conf</code><br /><br />

								Maintenant, si vous voulez stocker vos fichiers sur un disque dur externe, ce que je vous conseille car le Raspberry Pi à peu de mémoire, on va créer le répertoire de stockage d'Owncloud (vous pouvez choisir un autre répertoire que moi) et changer le propriétaire du dossier de stockage.<br />
								<code>
									mkdir /media/MonDisque/Owncloud<br/>
									chown -R www-data:www-data /media/MonDisque/Owncloud</code><br /><br />

								Pour modifier la taille des fichiers que l'on va vouloir partager avec Owncloud, il faut modifier le fichier /etc/php5/fpm/php.ini et modifier les valeurs suivantes :<br />
								<q>
									upload_max_filesize = 10000M<br /><br />

									post_max_size = 10000M
								</q><br /><br />

								Et maintenant on redémarre le tout.<br />
								<code>
									service nginx restart<br />
									service php5-fpm restart
								</code><br /><br />

								Si vous souhaitez utiliser MySQL comme serveur de base de données, il vous faut un minimum de configuration :<br/>
								Lors de l'installation au début, MySQL vous a demandé de choisir un mot de passe. C'est celui-ci que vous allez devoir utiliser.<br/>
								Merci à santory du site mondedie.fr pour m'avoir laissé reprendre sa partie sur MySQL.<br/>
								On commance par installer MySQL qui vous demandera de choisir un mot de passe pour l'utilisateur root de la base de données.<br/>
								<code>
									apt-get install mysql-server php5-mysql<br/>
									mysql -u root -p
								</code><br/><br />

								Après avoir tapé le mot de passe de MySQL, vous voilà dans MySQL. Nous allons créer un utilisateur pour Owncloud, lui attribuer un mot de passe, lui créer une base de données, et tout bien configurer :<br/>
								<code>CREATE DATABASE owncloud;<br/>
									CREATE USER "owncloud"@"localhost";<br/>
									SET password FOR "owncloud"@"localhost" = password('password_de_votre_choix');<br/>
									GRANT ALL PRIVILEGES ON owncloud.* TO "owncloud"@"localhost" IDENTIFIED BY "password_de_votre_choix";<br/>
									FLUSH PRIVILEGES;<br/>
									EXIT
								</code><br/><br/>

								Vous pouvez maintenant vous y connecter comme ceci :<br />
								<q>https://"IP_du_Raspberry_Pi":"Port Choisi"</q><br /><br/>

								Maintenant votre navigateur vous invite à accepter le certificat autosigné que vous avez créé.<br />
								<a href="../img/owncloud/owncloud1.png"><img src="../img/owncloud/owncloud1.png" alt="Page certificat Owncloud" /></a><br />
								On développe la partie "Je comprends les risques", et on clique sur le bouton pour faire apparaitre cette fenêtre :<br />
								<a href="../img/owncloud/owncloud2.png"><img src="../img/owncloud/owncloud2.png" alt"Acceptation du certificat" /></a><br />
								Vous accédez maintenant à Owncloud comme ceci :<br />
								<a href="../img/owncloud/owncloud3.png"><img src="../img/owncloud/owncloud3.png" alt="Page de connexion d'Owncloud" /></a><br />
								Ici vous devez choisir le nom d'utilisateur et le mot de passe du compte d'admin. Ensuite vous précisez le répertoire de partage, chez moi un disque dur externe. Si vous voulez utilisez MySQL, c'est ici que vous indiquez la configuration que nous avons mise en place : <br/>
								<q>
									Nom d'utilisateur : owncloud<br/>
									Mot de passe : password_de_votre_choix<br/>
									Base de données : owncloud<br/>
									localhost
								</q><br /><br />
								
								Maintenant vous pouvez profiter pleinement du service Owncloud.<br /><br />
								
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
