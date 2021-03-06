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
						<h1>Installer rtorrent/Rutorrent sous nginx</h1><br /><br />
						<h2>Les bases</h2>
						<p>
							Voici mon tuto pour installer rtorrent/rutorrent avec nginx sur le Raspberry Pi.<br /><br />

							Ce tuto est grandement inspiré de celui de <a href="http://mondedie.fr/viewtopic.php?id=5302">Magicalex</a> pour debian wheezy sur serveur, avec quelques petites modifications pour la framboise.<br /><br />

							La première chose est d'activer le compte root. Vous êtes connectés au compte pi.<br />
							<code>sudo passwd root</code><br />
							Et définissez le mot de passe du compte root.<br /><br />

							Puis connectez vous à root :<br />
							<code>su root</code><br /><br />

							Passons à l'installation de ce qui nous intéresse.<br />
							Première chose à faire, mettre à jour les paquets et installer ceux dont nous allons avoir besoin :<br />
							<code>
								aptitude update<br />
								aptitude safe-upgrade -y<br /><br />

								<!--apt-get install libpcre3-dev<br />-->
 
								aptitude install -y build-essential pkg-config libcurl4-openssl-dev libsigc++-2.0-dev libncurses5-dev nginx vim nano screen subversion apache2-utils curl php5 php5-cli php5-fpm php5-curl php5-geoip git ffmpeg buildtorrent
							</code><br /><br /><br />

							<q class="info">Avant d’installer rtorrent/libtorrent, vérifier le numéro de la dernière version stable <a href="http://libtorrent.rakshasa.no/">ici</a>. Attention à ne pas utiliser les version « unstable », elles ne sont pas acceptées partout !"</q><br /><br />

							On installe libtorrent, ici la version 0.13.2<br />
							<code>
								cd /tmp<br />
								wget http://libtorrent.rakshasa.no/downloads/libtorrent-0.13.2.tar.gz<br />
								tar zxfv libtorrent-0.13.2.tar.gz<br />
								cd libtorrent-0.13.2<br />
								./configure<br />
								make<br />
								make install
							</code><br /><br />

							On installe ensuite XML RPC via le SVN<br />
							<code>
								cd /tmp<br />
								svn checkout http://svn.code.sf.net/p/xmlrpc-c/code/stable xmlrpc-c<br />
								cd xmlrpc-c/<br />
								./configure<br />
								make<br />
								make install
							</code><br /><br />

							Puis rtorrent, ici la version 0.9.2<br />
							<code>
								cd /tmp<br />
								wget http://libtorrent.rakshasa.no/downloads/rtorrent-0.9.2.tar.gz<br />
								tar zxfv rtorrent-0.9.2.tar.gz<br />
								cd rtorrent-0.9.2<br />
								./configure --with-xmlrpc-c<br />
								make<br />
								make install
							</code><br /><br />

							Passons à l'installation de Rutorrent, je prends la version du SVN.<br />
							<code>
								mkdir /var/www/ && cd /var/www/<br />
								svn checkout http://rutorrent.googlecode.com/svn/trunk/rutorrent/<br />
							</code><br /><br />

							Et on installe les plugins<br />
							<code>
								cd rutorrent/<br />
								rm -r plugins/<br />
								wget dl.bintray.com/novik65/generic/plugins-3.6.tar.gz<br />
								tar zxvf plugins-3.6.tar.gz<br />
								rm plugins-3.6.tar.gz
							</code><br /><br />

							On supprime certains plugins dont on ne va pas avoir besoin ou qu'on ne peut utiliser (unpack car rar et unrar n'était pas dispo dans les dépôts officiels).<br />
							Certains sont supprimés par choix car inutiles pour moi, vous n'êtes donc pas obligés de faire exactement comme moi ici.<br />
							Vous pouvez voir ici les divers plugins avec une petite explication : <a href="https://code.google.com/p/rutorrent/wiki/Plugins#Currently_there_are_the_following_plug-ins:">ici</a>.<br />
							<code>
								cd /var/www/rutorrent/plugins<br />
								rm -r unpack<br />
								rm -r mediainfo<br />
								rm -r ratio<br />
								rm -r throttle<br />
								rm -r extratio<br />
								rm -r rss<br />
								rm -r rssurlrewrite<br />
								rm -r screenshots<br />
								rm -r feeds<br />
								rm -r retrackers<br />
								rm -r extsearch<br />
								rm -r rutracker_check<br />
								rm -r chunks
							</code><br /><br />

							Et installation des plugins Logoff, pausewebui, tadd-labels<br />
							<code>
								cd /var/www/rutorrent/plugins/<br />
								svn co http://rutorrent-logoff.googlecode.com/svn/trunk/ logoff<br />
								svn co http://rutorrent-pausewebui.googlecode.com/svn/trunk/ pausewebui<br />
								wget http://rutorrent-tadd-labels.googlecode.com/files/lbll-suite_0.8.1.tar.gz<br />
								tar zxfv lbll-suite_0.8.1.tar.gz<br />
								rm lbll-suite_0.8.1.tar.gz
							</code><br />
							Encore une fois, ce sont des plugins, vous êtes libre d'installer ceux qui vous plaisent.<br />
							Pour ma part, j'ai finalement supprimé logoff et pausewebui.<br /><br />
							
							On va indiquer le bon chemin pour curl :<br />
							<code>nano /var/www/rutorrent/conf/config.php</code><br />

							<q>
								$pathToExternals = array(<br />
								"php"   => '',<span class="tab2"></span>                  // Something like /usr/bin/php. If empty, will be found in PATH.<br />
								"curl"  => '/usr/bin/curl',     // Something like /usr/bin/curl. If empty, will be found in PATH.<br />
								"gzip"  => '',<span class="tab2"></span>                  // Something like /usr/bin/gzip. If empty, will be found in PATH.<br />
								"id"    => '',<span class="tab2"></span>                  // Something like /usr/bin/id. If empty, will be found in PATH.<br />
								"stat"  => '',<span class="tab2"></span>                  // Something like /usr/bin/stat. If empty, will be found in PATH.<br />
								);
							</q><br /><br />

							On met les liens symboliques à jour, ainsi que les permissions<br />
							<code>
								ldconfig<br />
								chown -R www-data:www-data /var/www/rutorrent
							</code><br /><br />
						</p>
						
						<h2>Configuration</h2>
						<p>
							Cette partie est presque une copie du tuto de Magicalex, je le remercie encore pour m'avoir autorisé à reprendre son travail.<br />
							Cependant, j'ai tout de même enlevé une partie, car je n'utilise pas vsftpd, je me connecte simplement en ssh, et en sftp pour le transfert de fichiers.<br /><br />
							
							On va commencer par configurer le plugin create<br />
							<code>nano /var/www/rutorrent/plugins/create/conf.php</code><br />
							Il faut maintenant faire quelques modifications pour vous retrouver avec ces lignes :<br />
							<q>
								$useExternal = "buildtorrent";<br />
								$pathToCreatetorrent = '/usr/bin/buildtorrent';
							</q><br /><br /><br />
							
							
							<h3>Configuration de php-fpm</h3>
							<p>
								Dans le cas où PHP-FPM et Nginx tournent sur la même machine, il vaut mieux utiliser un socket Unix au lieu d’un socket TCP pour des raisons de performances. Pour ça on édite le fichier de configuration du pool par défaut :<br />
								<code>nano /etc/php5/fpm/pool.d/www.conf</code><br />
								On remplace la ligne :<br />
								<q>;listen = 127.0.0.1:9000</q><br />
								Par (il est possible que ce soit déjà fait de base) :
								<q>listen = /var/run/php5-fpm.sock</q><br /><br />

								Modification du php.ini ( correction de la date, et suppression de X-Powered-By dans l'entête http) :<br />
								<code>nano /etc/php5/fpm/php.ini</code><br />
								Trouver ces lignes et remplacer par les bonnes valeurs :<br />
								<q>
									expose_php = Off<br />
									upload_max_filesize = 10M<br />
									date.timezone = Europe/Paris
								</q><br /><br />
							
								On redémarre php-fpm :<br />
								<code>service php5-fpm restart</code><br />
							</p><br /><br />

							
							<h3>Configuration du serveur Web</h3>
							<p>
								<code>
									mkdir /etc/nginx/passwd<br />
									mkdir /etc/nginx/ssl<br />
									touch /etc/nginx/passwd/rutorrent_passwd<br />
									chmod 640 /etc/nginx/passwd/rutorrent_passwd
								</code><br /><br />

								On modifie le fichier nginx.conf :<br />
								<code>
									rm /etc/nginx/nginx.conf<br />
									nano /etc/nginx/nginx.conf
								</code><br />
								On colle le nouveau fichier de configuration :<br />
								<q class="info">Pour coller dans un terminal on effectue : Ctrl + Shift + v.</q><br />
								<q>
									user nginx;<br />
									worker_processes auto;<br /><br />

									pid /var/run/nginx.pid;<br />
									events { worker_connections 1024; }<br /><br />

									http {<br />
										include /etc/nginx/mime.types;<br />
										default_type  application/octet-stream;<br /><br />

										access_log /var/log/nginx/access.log combined;<br />
										error_log /var/log/nginx/error.log error;<br /><br />
										    
										sendfile on;<br />
										keepalive_timeout 20;<br />
										keepalive_disable msie6;<br />
										keepalive_requests 100;<br />
										tcp_nopush on;<br />
										tcp_nodelay off;<br />
										server_tokens off;<br /><br />
															    
										gzip on;<br />
										gzip_buffers 16 8k;<br />
										gzip_comp_level 5;<br />
										gzip_disable "msie6";<br />
										gzip_min_length 20<br />
										gzip_proxied any;<br />
										gzip_types text/plain text/css application/json  application/x-javascript text/xml application/xml application/xml+rss  text/javascript;<br />
										gzip_vary on;<br /><br />

										include /etc/nginx/sites-enabled/*.conf;<br />
									}
								</q><br /><br />

								On ouvre le fichier php :<br />
								<code>nano /etc/nginx/conf.d/php</code><br />
								Et coller :<br />
								<q>
									location ~ \.php$ {<br />
										fastcgi_index index.php;<br />
										fastcgi_pass unix:/var/run/php5-fpm.sock;<br />
										fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;<br />
										include /etc/nginx/fastcgi_params;<br />
									}
								</q><br /><br />

								On ouvre le ficier cache :<br />
								<code>nano /etc/nginx/conf.d/cache</code><br />
								Et coller :<br />
								<q>
									location ~* \.(jpg|jpeg|gif|css|png|js|woff|ttf|svg|eot)$ {<br />
										    expires 7d;<br />
										    access_log off;<br />
									}<br /><br />

									location ~* \.(eot|ttf|woff|svg)$ {<br />
										    add_header Acccess-Control-Allow-Origin *;<br />
									}
								</q><br /><br />

								Configuration du vhost :<br />
								<code>
									mkdir /etc/nginx/sites-enabled<br />
									touch /etc/nginx/sites-enabled/rutorrent.conf<br />
									nano /etc/nginx/sites-enabled/rutorrent.conf
								</code><br />
								Et on colle :<br />
								<q>
									server {<br />
										listen 80 default_server;<br />
										listen 443 default_server ssl;<br />
										server_name _;<br />
										index index.html index.php;<br />
										charset utf-8;<br /><br />

										ssl_certificate /etc/nginx/ssl/server.crt;<br />
										ssl_certificate_key /etc/nginx/ssl/server.key;<br /><br />
									        
										access_log /var/log/nginx/rutorrent-access.log combined;<br />
										error_log /var/log/nginx/rutorrent-error.log error;<br /><br />
														        
										error_page 500 502 503 504 /50x.html;<br />
										location = /50x.html { root /usr/share/nginx/html; }<br /><br />

										auth_basic "seedbox";<br />
										auth_basic_user_file "/etc/nginx/passwd/rutorrent_passwd";<br /><br />
																	    
										location = /favicon.ico {<br />
											access_log off;<br />
											return 204;<br />
										}<br /><br />
																					        
										## début config rutorrent ##<br /><br />

										location ^~ /rutorrent {<br />
											root /var/www;<br />
											include /etc/nginx/conf.d/php;<br />
											include /etc/nginx/conf.d/cache;<br /><br />

											location ~ /\.svn {<br />
												deny all;<br />
											}<br /><br />

											location ~ /\.ht {<br />
												deny all;<br />
											}<br />
										}<br /><br />
										
										location ^~ /rutorrent/conf/ {<br />
											deny all;<br />
										}<br /><br />

										location ^~ /rutorrent/share/ {<br />
											deny all;<br />
										}<br /><br />																	        
										## fin config rutorrent ##<br /><br />

										## Début config cakebox 2.8 ##<br /><br />
										
										#    location ^~ /cakebox {<br />
										#	root /var/www/;<br />
										#	include /etc/nginx/conf.d/php;<br />
										#	include /etc/nginx/conf.d/cache;<br />
										#    }<br /><br />
										
										#    location /cakebox/downloads {<br />
										#	root /var/www;<br />
										#	satisfy any;<br />
										#	allow all;<br />
										#    }<br /><br />
										
										## fin config cakebox 2.8 ##<br /><br />
										
										## début config seedbox manager ##<br /><br />
										
										#    location ^~ / {<br />
										#	root /var/www/manager;<br />
										#	include /etc/nginx/conf.d/php;<br />
										#	include /etc/nginx/conf.d/cache;<br />
										#    }<br /><br />
										
										#    location ^~ /conf/ {<br />
										#	root /var/www/manager;<br />
										#	deny all;<br />
										#    }<br /><br />
										
										## fin config seedbox manager ##<br />										
									}
								</q><br />
							</p><br /><br />



							<h3>Configurer nginx pour du https</h3>
							<p>		
								On va maintenant créer le certificat SSL.<br />
								<code>
									mkdir /etc/nginx/ssl/<br />
									cd /etc/nginx/ssl/<br />
									openssl genrsa -des3 -out secure.key 1024<br />
									openssl req -new -key secure.key -out server.csr<br />
									openssl rsa -in secure.key -out server.key<br />
									openssl x509 -req -days 3650 -in server.csr -signkey server.key -out server.crt<br />
									rm secure.key server.csr
								</code><br />
								Et on redémarre nginx :<br />
								<code>service nginx restart</code><br />
							</p><br/><br />



							<h3>Configurer Logrotate pour nginx (compression des logs)</h3>
							<p>
								Création du fichier de config pour nginx :<br />
								<code>rm /etc/logrotate.d/nginx && nano /etc/logrotate.d/nginx</code><br />
								Et on colle :<br />
								<q>
									/var/log/nginx/*.log {<br />
										daily<br />
										missingok<br />
										rotate 52<br />
										compress<br />
										delaycompress<br/>
										notifempty<br />
										create 640 root<br />
										sharedscripts<br />
										postrotate<br />
									        [ -f /var/run/nginx.pid ] && kill -USR1 `cat /var/run/nginx.pid`<br />
									        endscript<br />
									}
								</q><br />
							</p><br /><br />



							<h3>Configuration de SSH</h3>
							<p>
								<code>nano /etc/ssh/sshd_config</code><br />
								Et on commente les lignes suivantes :<br />
								<q class="info"> Commenter signifie, dans ce tuto, mettre un # devant.</q><br />
								<q>
									Subsystem sftp /usr/lib/openssh/sftp-server<br />
									UsePAM yes
								</q><br />
								Et ajouter à la fin :<br />
								<q>Subsystem sftp internal-sftp</q><br />
							</p><br /><br /><br />
						</p>
						


						<h2>Ajout d'un utilisateur</h2>
						<p>
							<h4>Important</h4>
							<q class="important">
								<p>
									Pour uniformiser votre installation, on va convenir d’un format d’écrire des utilisateurs :<br />
									<ol>
										<li>Le nom d’utilisateur => <span class="bold">&ltuser&gt</span></li>
										<li>Les 3 premiers caractères du nom d’utilisateur en majuscule => <span class="bold">&ltUU&gt</span></li>
									</ol><br /><br />
									
									Par exemple, pour l’utilisateur nico : &ltuser&gt = nico, &ltu&gt = nic et &ltUU&gt = NIC<br />
									!!! ATTENTION !!! Pour être valide, le nom d’utilisateur doit être <span class="underline">entièrement en minuscules</span>, faire plus de 3 caractères et les 3 premiers caractères doivent être différents entre tous les utilisateurs (pas de user1, user2, etc.).<br />
								</p>
							</q><br /><br />
									
							On va créer nos répertoires :<br />
							<code>
								mkdir /home/&ltuser&gt<br />
								mkdir /home/&ltuser&gt/watch<br />
								mkdir /home/&ltuser&gt/torrents<br />
								mkdir /home/&ltuser&gt/.session
							</code><br /><br />
							
							Puis on va créer un utilisateur pour rtorrent :<br />
							<code>
								useradd -s /bin/bash --home /home/&ltuser&gt &ltuser&gt<br />
								passwd &ltuser&gt
							</code><br /><br />
														
							Et on redémarre le serveur SSH pour qu'il tienne compte de nos modifications.<br />
							<code>service ssh restart</code><br /><br />
							
							On crée le fichier de configuration de rtorrent :<br />
							<code>nano /home/&ltuser&gt/.rtorrent.rc</code><br />
							Il faut coller ceci et remplacer les 5 &ltuser&gt ainsi que 500{x} par 5001 ou 5002. Il faut que chaque user ait un port différent.<br />
							<q>
								scgi_port = 127.0.0.1:500{x}<br />
								encoding_list = UTF-8<br />
								port_range = 45000-65000<br />
								port_random = no<br />
								check_hash = no<br />
								directory = /home/&ltuser&gt/torrents<br />
								session = /home/&ltuser&gt/.session<br />
								encryption = allow_incoming, try_outgoing, enable_retry<br />
								schedule = watch_directory,1,1,"load_start=/home/&ltuser&gt/watch/*.torrent"<br />
								schedule = untied_directory,5,5,"stop_untied=/home/&ltuser&gt/watch/*.torrent"<br />
								use_udp_trackers = yes<br />
								dht = off<br />
								peer_exchange = no<br />
								min_peers = 40<br />
								max_peers = 100<br />
								min_peers_seed = 10<br />
								max_peers_seed = 50<br />
								max_uploads = 15<br />
								execute = {sh,-c,/usr/bin/php /var/www/rutorrent/php/initplugins.php &ltuser&gt &}<br />
								schedule = espace_disque_insuffisant,1,30,close_low_diskspace=500M
							</q><br />							
							Vous trouverez de quoi personnaliser votre fichier sur <a href="http://libtorrent.rakshasa.no/">le site de l'auteur</a>.<br /><br />
							
							On établie les permissions :<br />
							<code>
								chown -R &ltuser&gt:&ltuser&gt /home/&ltuser&gt <br />
								chown root:&ltuser&gt /home/&ltuser&gt <br />
								chmod 755 /home/&ltuser&gt
							</code><br /><br />
						
							On configure le serveur web<br />
							<code>nano /etc/nginx/sites-enabled/rutorrent.conf</code><br />
							On colle entre :<br />
							<q>
								server {<br />
									## début config rutorrent ##<br />
									            ...<br />
									## fin config rutorrent ##<br />
								}
							</q><br />
							Ceci (1 &ltuser&gt 1 &ltUU&gt à remplacer et indiquer le bon port 500{x}):<br />
							<q>
								location /&ltUU&gt0 {<br />
									include scgi_params;<br />
									scgi_pass 127.0.0.1:500{x}; #ou socket : unix:/home/user/.session/user.socket<br />
									auth_basic "seedbox";<br />
									auth_basic_user_file "/etc/nginx/passwd/rutorrent_passwd_&ltuser&gt";<br />
								}
							</q><br />
							Cette étape est à faire pour chaque utilisateur.<br /><br />

							On spécifie le mot de passe pour le serveur Web et on va créer le mot de passe pour Rutorrent<br />
							<code>
								htpasswd -s /etc/nginx/passwd/rutorrent_passwd &ltuser&gt<br />
								sed -rn '/&ltusername&gt:/p' /etc/nginx/passwd/rutorrent_passwd > /etc/nginx/passwd/rutorrent_passwd_&ltuser&gt
							</code><br />
							N'oubliez pas de modifier le &ltuser&gt.<br />
							-s pour chiffrer les mots de passe en SHA-1.<br />
							Vous devez taper deux fois le mot de passe.<br /><br />

							On applique les bonnes permissions aux fichiers mots de passe :<br />
							<code>
								chmod 640 /etc/nginx/passwd/*<br />
								chown -c nginx:nginx /etc/nginx/passwd/*
							</code><br />
							Et on redémarre le serveur Web :<br />
							<code>service nginx restart</code><br /><br />

						
							On crée le répertoire de configuration de rutorrent en remplacant le &ltuser&gt.<br />
							<code>mkdir /var/www/rutorrent/conf/users/&ltuser&gt</code><br />
							Et on crée le fichier config.php en modifiant &ltuser&gt.<br />
							<code>nano /var/www/rutorrent/conf/users/&ltuser&gt/config.php</code><br />
							Puis on colle ceci, en remplacant le &ltuser&gt et le &ltUU&gt.<br />
							<q>
								&lt?php<br />
								$topDirectory = '/home/&ltuser&gt';<br />
								$scgi_port = 500{x};<br />
								$scgi_host = '127.0.0.1';<br />
								$XMLRPCMountPoint = "/&ltUU&gt0";<br />
								?&gt
							</q><br /><br />

							On désactive certains plugins inutiles :<br />
							<code>nano /var/www/rutorrent/conf/users/&ltuser&gt/plugins.ini</code><br />
							<q>
								[default]<br />
								enabled = user-defined<br />
								canChangeToolbar = yes<br />
								canChangeMenu = yes<br />
								canChangeOptions = yes<br />
								canChangeTabs = yes<br />
								canChangeColumns = yes<br />
								canChangeStatusBar = yes<br />
								canChangeCategory = yes<br />
								canBeShutdowned = yes<br />
								[ipad]<br />
								enabled = no<br />
								[httprpc]<br />
								enabled = no<br />
								[rpc]<br />
								enabled = no
							</q><br /><br /><br />
						


							On va maintenant créer le script de démarrage de rtorrent, en remplacant les 3 &ltuser&gt.<br />
							<code>nano /etc/init.d/&ltuser&gt-rtord</code><br />
							Et on colle ceci, en remplacant les 3 &ltuser&gt.<br />
							<q>
								#!/bin/bash<br /><br />
 
								### BEGIN INIT INFO<br />
								# Provides:                rtorrent<br />
								# Required-Start:<br />
								# Required-Stop:<br />
								# Default-Start:<span class="tab4"></span>2 3 4 5<br />
								# Default-Stop:<span class="tab4"></span>0 1 6<br />
								# Short-Description:  Start daemon at boot time<br />
								# Description:           Start-Stop rtorrent user session<br />
								### END INIT INFO<br /><br />
 
								user="&ltuser&gt"<br /><br />
 
								# the full path to the filename where you store your rtorrent configuration<br />
								config="/home/&ltuser&gt/.rtorrent.rc"<br /><br />
 
								# set of options to run with<br />
								options=""<br /><br />
 
								# default directory for screen, needs to be an absolute path<br />
								base="/home/&ltuser&gt"<br /><br />
 
								# name of screen session<br />
								srnname="rtorrent"<br /><br />
 
								# file to log to (makes for easier debugging if something goes wrong)<br />
								logfile="/var/log/rtorrentInit.log"<br />
								#######################<br />
								###END CONFIGURATION###<br />
								#######################<br />
								PATH=/usr/bin:/usr/local/bin:/usr/local/sbin:/sbin:/bin:/usr/sbin<br />
								DESC="rtorrent"<br />
								NAME=rtorrent<br />
								DAEMON=$NAME<br />
								SCRIPTNAME=/etc/init.d/$NAME<br /><br />
 
								checkcnfg() {<br />
								<span class="tab2"></span>exists=0<br />
								<span class="tab2"></span>for i in `echo "$PATH" | tr ':' '\n'` ; do<br />
								<span class="tab4"></span>if [ -f $i/$NAME ] ; then<br />
								<span class="tab4"></span><span class="tab2"></span>exists=1<br />
								<span class="tab4"></span><span class="tab2"></span>break<br />
								<span class="tab4"></span>fi<br />
								<span class="tab2"></span>done<br />
								<span class="tab2"></span>if [ $exists -eq 0 ] ; then<br />
								<span class="tab4"></span>echo "cannot find rtorrent binary in PATH $PATH" | tee -a "$logfile" >&2<br />
								<span class="tab4"></span>exit 3<br />
								<span class="tab2"></span>fi<br />
								<span class="tab2"></span>if ! [ -r "${config}" ] ; then<br />
								<span class="tab4"></span>echo "cannot find readable config ${config}. check that it is there and permissions are appropriate" | tee -a "$logfile" >&2<br />
								<span class="tab4"></span>exit 3<br />
								<span class="tab2"></span>fi<br />
								<span class="tab2"></span>session=`getsession "$config"`<br />
								<span class="tab2"></span>if ! [ -d "${session}" ] ; then<br />
								<span class="tab4"></span>echo "cannot find readable session directory ${session} from config ${config}. check permissions" | tee -a "$logfile" >&2<br />
								<span class="tab4"></span>exit 3<br /><br />
 
								<span class="tab4"></span>fi<br /><br />
 
								}<br /><br />
 
								d_start() {<br /><br />
 
								<span class="tab1"></span>[ -d "${base}" ] && cd "${base}"<br /><br />
 
								<span class="tab1"></span>stty stop undef && stty start undef<br />
								<span class="tab1"></span>su -c "screen -ls | grep -sq "\.${srnname}[[:space:]]" " ${user} || su -c "screen -dm -S ${srnname} 2>&1 1>/dev/null" ${user} | tee -a "$logfile" >&2<br />
								<span class="tab1"></span>su -c "screen -S "${srnname}" -X screen rtorrent ${options} 2>&1 1>/dev/null" ${user} | tee -a "$logfile" >&2<br />
								}<br /><br />
 
								d_stop() {<br />
								<span class="tab2"></span>session=`getsession "$config"`<br />
								<span class="tab2"></span>if ! [ -s ${session}/rtorrent.lock ] ; then<br />
								<span class="tab4"></span>return<br />
								<span class="tab2"></span>fi<br />
								<span class="tab2"></span>pid=`cat ${session}/rtorrent.lock | awk -F: '{print($2)}' | sed "s/[^0-9]//g"`<br />
								<span class="tab2"></span>if ps -A | grep -sq ${pid}.*rtorrent ; then # make sure the pid doesn't belong to another process<br />
								<span class="tab4"></span>kill -s INT ${pid}<br />
								<span class="tab2"></span>fi<br />
								}<br /><br />
 
								getsession() {<br />
								<span class="tab2"></span>session=`cat "$1" | grep "^[[:space:]]*session[[:space:]]*=" | sed "s/^[[:space:]]*session[[:space:]]*=[[:space:]]*//" `<br />
								<span class="tab2"></span>echo $session<br />
								}<br /><br />
 
								checkcnfg<br /><br />
 
								case "$1" in<br />
								<span class="tab1"></span>start)<br />
								<span class="tab2"></span>echo -n "Starting $DESC: $NAME"<br />
								<span class="tab2"></span>d_start<br />
								<span class="tab2"></span>echo "."<br />
								<span class="tab2"></span>;;<br />
								<span class="tab1"></span>stop)<br />
								<span class="tab2"></span>echo -n "Stopping $DESC: $NAME"<br />
								<span class="tab2"></span>d_stop<br />
								<span class="tab2"></span>echo "."<br />
								<span class="tab2"></span>;;<br />
								<span class="tab1"></span>restart|force-reload)<br />
								<span class="tab2"></span>echo -n "Restarting $DESC: $NAME"<br />
								<span class="tab2"></span>d_stop<br />
								<span class="tab2"></span>sleep 1<br />
								<span class="tab2"></span>d_start<br />
								<span class="tab2"></span>echo "."<br />
								<span class="tab2"></span>;;<br />
								<span class="tab1"></span>*)<br />
								<span class="tab2"></span>echo "Usage: $SCRIPTNAME {start|stop|restart|force-reload}" >&2<br />
								<span class="tab2"></span>exit 1<br />
 		   						<span class="tab2"></span>;;<br />
								esac<br /><br />
 
								exit 0
							</q><br /><br />
						
							On donne les droits d'exécution au script, en remplacant le &ltuser&gt<br />
							<code>chmod +x /etc/init.d/&ltuser&gt-rtord</code><br />
							On ajoute une tache cron :<br />
							<code>crontab -e</code><br />
							Et on colle, en remplacant le &ltuser&gt et le &ltu&gt.<br />
							<q>* * * * * if ! ( ps -U &ltuser&gt | grep rtorrent > /dev/null ); then /etc/init.d/&ltuser&gt-rtord start; fi</q><br /><br />
						</p>
						
						<h2>Voilà, c'est fini !</h2>
						<p>
							Nous arrivons au terme de ce tutoriel.<br /><br />
							Pour vous connectez à Rutorrent, vous tapez l'adresse IP de la machine sur laquelle vous venez de faire l'installation, en précisant le dossier :<br />
							URL : <br />
							<q>XXX.XXX.XXX.XXX/rutorrent/</q><br /><br />
							
							Pour vous connectez en SFTP, vous devez utiliser un client comme Filezilla par exemple. Il a l'avantage d'être multi-plateforme.<br />
							IP : <br />
							<q>XXX.XXX.XXX.XXX</q><br />
							Port : <br />
							<q>22</q><br /><br />
							
							Je remercie à nouveau Magicalex, pour tout son travail.<br /><br />
							
							<a href="../tutoriels.php">Retour</a><br /><br />
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
