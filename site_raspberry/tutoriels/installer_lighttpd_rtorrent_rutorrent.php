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
						<h1>Installer rtorrent/Rutorrent sous Lighttpd</h1><br /><br />
						<h2>Les bases</h2>
						<p>
							Voici mon tuto pour installer rtorrent/rutorrent avec lighttpd sur le Raspberry Pi.<br /><br />

							Ce tuto est grandement inspiré de celui de <a href="http://v2.mondedie.fr/knowledgebase/installer-rutorrent-sur-debian-64bits/">nico</a> pour debian squeeze sur serveur, avec quelques petites modifications pour la framboise.<br /><br />

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
 
								aptitude install -y vsftpd htop zip build-essential pkg-config libcurl4-openssl-dev libsigc++-2.0-dev libncurses5-dev lighttpd nano screen subversion libterm-readline-gnu-perl php5-cgi apache2-utils libcurl3 curl php5-curl php5-cli dtach unzip ffmpeg
							</code><br /><br /><br />

							<q class="info">Avant d’installer rtorrent/libtorrent, vérifier le numéro de la dernière version stable ici. Attention à ne pas utiliser les version « unstable », elles ne sont pas acceptées partout !"</q><br /><br />

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

							Passons à l'installation de Rutorrent, je ne prends pas la version du SVN, mais la 3.5<br />
							<code>
								cd /var/www/<br />
								wget http://rutorrent.googlecode.com/files/rutorrent-3.5.tar.gz<br />
								tar zxvf rutorrent-3.5.tar.gz<br />
								rm rutorrent-3.5.tar.gz
							</code><br /><br />

							Et on installe les plugins<br />
							<code>
								cd rutorrent/<br />
								rm -r plugins/<br />
								wget http://rutorrent.googlecode.com/files/plugins-3.5.tar.gz<br />
								tar zxvf plugins-3.5.tar.gz<br />
								rm plugins-3.5.tar.gz
							</code><br /><br />

							On supprime certains plugins dont on ne va pas avoir besoin ou qu'on ne peut utiliser (unpack car rar et unrar n'était pas dispo dans les dépôts officiels).<br />
							Certains sont supprimés par choix car inutiles pour moi, vous n'êtes donc pas obligés de faire exactement comme moi ici.<br />
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
							
							Ensuite on installe buildtorrent<br />
							<code>
								cd /tmp/<br />
								wget http://ftp.de.debian.org/debian/pool/main/b/buildtorrent/buildtorrent_0.8-4_armhf.deb<br />
								dpkg -i buildtorrent*.deb<br />
								rm buildtorrent*.deb
							</code><br /><br />

							On met les liens symboliques à jour, ainsi que les permissions<br />
							<code>
								ldconfig<br />
								chown -R www-data:www-data /var/www/rutorrent
							</code><br /><br />
						</p>
						
						<h2>Configuration</h2>
						<p>
							Cette partie est presque une copie du tuto de Nicobubulle, je le remercie encore pour m'avoir autorisé à reprendre son travail.<br />
							Cependant, j'ai tout de même enlevé une partie, car je n'utilise pas vsftpd, je me connecte simplement en ssh, et en sftp pour le transfert de fichiers.<br /><br />
							
							On va commencer par configurer le plugin create<br />
							<code>nano /var/www/rutorrent/plugins/create/conf.php</code><br />
							Il faut maintenant faire quelques modifications pour vous retrouver avec ces lignes :<br />
							<q>
								$useExternal = "buildtorrent";<br />
								$pathToCreatetorrent = '/usr/bin/buildtorrent';
							</q><br /><br /><br />
							
							
							<h3>Configuration de Lighttpd</h3>
							<p>
								On tape<br />
								<code>nano /etc/lighttpd/lighttpd.conf</code><br />
								On colle ceci à la fin du fichier :<br />
								<q>
									fastcgi.server = ( ".php" => ((<br />
									"bin-path" => "/usr/bin/php5-cgi",<br />
									"socket" => "/tmp/php.socket"<br />
									)))<br />
									$SERVER["socket"] == ":443" {<br />
									ssl.engine = "enable"<br />
									ssl.pemfile = "/etc/lighttpd/certs/lighttpd.pem"<br />
									}<br />
									server.modules += ( "mod_auth" )<br />
									auth.backend = "htdigest"<br />
									auth.backend.htdigest.userfile = "/etc/lighttpd/.auth"<br />
									auth.require = ( "/rutorrent/" =><br />
									(<br />
									"method" => "digest",<br />
									"realm" => "ruTorrent Seedbox",<br />
									"require" => "valid-user"<br />
									),<br />
									)
								</q><br /><br />
							
								On active le module fastcgi :<br />
								<code>/usr/sbin/lighty-enable-mod fastcgi</code><br /><br />
							
								On va maintenant créer le certificat SSL.<br />
								<code>
									mkdir /etc/lighttpd/certs<br />
									cd /etc/lighttpd/certs<br />
									openssl req -new -newkey rsa:1024 -days 365 -nodes -x509 -keyout lighttpd.pem -out lighttpd.pem
								</code><br />
								Vous pouvez appuyer sur entrée à chaque question, vous n'êtes pas obligés de les compléter.<br />
								Et on va maintenant redémarrer le serveur web en le forçant à bien tenir compte de la nouvelle configuration.<br />
								<code>/etc/init.d/lighttpd force-reload</code><br /><br />
							</p>
						
							<h3>Configuration de SSH</h3>
							<p>
								On commence par modifier le fichier sshd_config.<br />
								<code>nano /etc/ssh/sshd_config</code><br />
								Il faut commenter les lignes suivants, en mettant un # devant.<br />
								<q>
									Subsystem sftp /usr/lib/openssh/sftp-server<br />
									UsePAM yes
								</q><br />
								Et terminer en rajoutant la ligne :<br />
								<q>Subsystem sftp internal-sftp</q><br /><br />
							</p>
						</p>
						
						<h2>Ajout d'un utilisateur</h2>
						<p>
							<h4>Important</h4>
							<q class="important">
								<p>
									Pour uniformiser votre installation, on va convenir d’un format d’écrire des utilisateurs :<br />
									<ol>
										<li>Le nom d’utilisateur => <span class="bold">&ltuser&gt</span></li>
										<li>Les 3 premiers caractères du nom d’utilisateur en minuscule => <span class="bold">&ltu&gt</span></li>
										<li>Les 3 premiers caractères du nom d’utilisateur en majuscule => <span class="bold">&ltUU&gt</span></li>
									</ol><br /><br />
									
									Par exemple, pour l’utilisateur nico : &ltuser&gt = nico, &ltu&gt = nic et &ltUU&gt = NIC<br />
									!!! ATTENTION !!! Pour être valide, le nom d’utilisateur doit être <span class="underline">entièrement en minuscules</span>, faire plus de 3 caractères et les 3 premiers caractères doivent être différents entre tous les utilisateurs (pas de user1, user2, etc.).<br />
								</p>
							</q><br /><br />
									
							On va créer un utilisateur pour rtorrent<br />
							<code>
								useradd &ltuser&gt<br />
								passwd &ltuser&gt
							</code><br /><br />
									
							Puis nos répertoires<br />
							<code>
								mkdir /home/&ltuser&gt<br />
								mkdir /home/&ltuser&gt/watch<br />
								mkdir /home/&ltuser&gt/torrents<br />
								mkdir /home/&ltuser&gt/.session
							</code><br /><br />
							
							Et on bloque l'utilisateur dans son home en SFTP<br />
							<code>nano /etc/ssh/sshd_config</code><br />
							Et on met ça à la fin :<br />
							<q>
								Match user &ltuser&gt<br />
								ChrootDirectory /home/%u
							</q><br /><br />
							
							Et on redémarre le serveur SSH pour qu'il tienne compte de nos modifications.<br />
							<code>/etc/init.d/ssh restart</code><br /><br />
							
							<q class="important">
								A noter que cette manipulation aura pour effet de bloquer l’accès au SSH à cet utilisateur. Il convient donc d’ignorer ce paragraphe si ce dernier remplace root pour la connexion SSH.
							</q><br /><br />
							
							On crée le fichier de configuration de rtorrent<br />
							<code>nano /home/&ltuser&gt/.rtorrent.rc</code><br />
							Il faut coller ceci et remplacer les 9 &ltuser&gt et les 4 &ltu&gt.<br />
							<q>
								schedule = chmod,0,0,"execute=chmod,777,/home/&ltuser&gt/.session/&ltu&gt.socket"<br />
								execute = {sh,-c,rm -f /home/&ltuser&gt/.session/&ltu&gt.socket}<br />
								scgi_local = /home/&ltuser&gt/.session/&ltu&gt.socket<br />
								execute = {sh,-c,chmod 0666 /home/&ltuser&gt/.session/&ltu&gt.socket}<br />
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
								execute = {sh,-c,/usr/bin/php /var/www/rutorrent/php/initplugins.php &ltuser&gt &}
							</q><br />					
							
							Vous trouverez de quoi personnaliser votre fichier sur <a href="http://libtorrent.rakshasa.no/">le site de l'auteur</a>.<br /><br />
							
							On établie les permissions :<br />
							<code>
								chown -R &ltuser&gt:&ltuser&gt /home/&ltuser&gt <br />
								chown root:&ltuser&gt /home/&ltuser&gt <br />
								chmod 755 /home/&ltuser&gt
							</code><br /><br />
						
							On configure le serveur web<br />
							<code>nano /etc/lighttpd/lighttpd.conf</code><br />
							On colle à la fin<br />
							<q>
								server.modules += ( "mod_scgi" )<br />
								scgi.server = (<br />
								"/&ltUU&gt0" =><br />
								( "127.0.0.1" =><br />
								(<br />
								"socket" => "/home/&ltuser&gt/.session/&ltu&gt.socket",<br />
								"check-local" => "disable",<br />
								"disable-time" => 0, # don't disable scgi if connection fails<br />
								)<br />
								),<br />
								)
							</q><br />
							Pensez à modifier le &ltuser&gt, le &ltu&gt, et le &ltUU&gt.<br /><br />
						
							<q class="info">
								<span class="underline">Pour les utilisateurs suivant, on procédera ainsi :</span><br /><br />
							
								server.modules += ( « mod_scgi » )<br />
								scgi.server = (<br />
								« /&ltUU1&gt0″ =><br />
								( « 127.0.0.1″ =><br />
								(<br />
								« socket » => « /home/&ltuser1&gt/.session/&ltu1&gt.socket »,<br />
								« check-local » => « disable »,<br />
								« disable-time » => 0, # don’t disable scgi if connection fails<br />
								)<br />
								),<br />
								« /&ltUU2&gt0″ =><br />
								( « 127.0.0.1″ =><br />
								(<br />
								« socket » => « /home/&ltuser2&gt/.session/&ltu2&gt.socket »,<br />
								« check-local » => « disable »,<br />
								« disable-time » => 0, # don’t disable scgi if connection fails<br />
								)<br />
								),<br />
								)
							</q><br /><br />
						
							Trouvez ces lignes :<br />
							<q>
								server.modules += ( "mod_auth" )<br />
								auth.backend = "htdigest"<br />
								auth.backend.htdigest.userfile = "/etc/lighttpd/.auth"<br />
								auth.require = ( "/rutorrent/" =><br />
								(<br />
								"method" => "digest",<br />
								"realm" => "ruTorrent Seedbox",<br />
								"require" => "valid-user"<br />
								),<br />
								"/&ltUU&gt0" => (<br />
								"method" => "digest",<br />
								"realm" => "ruTorrent Seedbox",<br />
								"require" => "user=&ltuser&gt",<br />
								),<br />
								)
							</q><br /><br />
						
							On va créer le mot de passe pour Rutorrent<br />
							<code>
								touch /etc/lighttpd/.auth<br />
								htdigest /etc/lighttpd/.auth 'ruTorrent Seedbox' &ltuser&gt
							</code><br />
							N'oubliez pas de modifier le &ltuser&gt.<br /><br />
						
							Maintenant on redémarre le serveur web.<br />
							<code>/etc/init.d/lighttpd restart</code><br /><br />
						
							On crée le répertoire de configuration de rutorrent en remplacant le &ltuser&gt.<br />
							<code>mkdir /var/www/rutorrent/conf/users/&ltuser&gt</code><br />
							Et on crée le fichier config.php en modifiant &ltuser&gt.<br />
							<code>nano /var/www/rutorrent/conf/users/&ltuser&gt/config.php</code><br />
							Puis on colle ceci, en remplacant les 2 &ltuser&gt, le &ltu&gt et le &ltUU&gt.<br />
							<q>
								&lt?php<br />
								$topDirectory = '/home/&ltuser&gt';<br />
								$scgi_port = 0;<br />
								$scgi_host = "unix:///home/&ltuser&gt/.session/&ltu&gt.socket";<br />
								$XMLRPCMountPoint = "/&ltUU&gt0";<br />
								?&gt
							</q><br /><br />
						
							On va maintenant créer le script de démarrage de rtorrent, en remplacant le &ltu&gt.<br />
							<code>nano /etc/init.d/&ltu&gt.rtord</code><br />
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
						
							On donne les droits d'exécution au script, en remplacant le &ltu&gt<br />
							<code>chmod +x /etc/init.d/&ltu&gt.rtord</code><br />
							On ajoute une tache cron :<br />
							<code>crontab -e</code><br />
							Et on colle, en remplacant le &ltuser&gt et le &ltu&gt.<br />
							<q>*/1 * * * * if ! ( ps -U &ltuser&gt | grep rtorrent > /dev/null ); then /etc/init.d/&ltu&gt.rtord start; fi</q><br /><br />
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
							
							Je remercie à nouveau Nicobubulle, pour tout son travail.<br /><br />
							
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
