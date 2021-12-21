<?php

$sess_life_time = 21600; //in seconds
$sess_path = "/";
$sess_domain = ".stadt-hilden.de";
$sess_secure = true; // if you have secured session
$sess_httponly = true; // httponly flag
//session_set_cookie_params($sess_life_time, $sess_path, $sess_domain, $sess_secure, $sess_httponly);
session_start();
$db_host_sl = 'localhost';
$db_user_sl = 'seemlesslogin';
$db_pass_sl = 'seemlesslogin';
$db_base_sl = 'seemlesslogin';

$pdo_sl = new PDO('mysql:host=' . $db_host_sl . ';dbname=' . $db_base_sl, $db_user_sl, $db_pass_sl);
$con_sl = mysqli_connect("$db_host_sl","$db_user_sl", "$db_pass_sl", "$db_base_sl");

include 'functions_sl_hash.php';

function abmelden()
{
	global $pdo_sl;
	global $con_sl;
	$SID = session_id();
	$statement = $pdo_sl->prepare("DELETE FROM sl_sessions WHERE sessions_id = '" . $SID . "';");
	$statement->execute();
	session_destroy();
	//header('Location: login.php');
}

function anmelden($benutzer_name,$benutzer_passwort)
{
	global $pdo_sl;
	global $con_sl;
	
	if(isset($benutzer_name) & isset($benutzer_passwort))
	{
		$statement = $pdo_sl->prepare("SELECT * FROM sl_users WHERE users_username = '" . $benutzer_name . "';");
		$result = $statement->execute();
		$user = $statement->fetch();
		$ser_result = serialize($user);
		if ($user !== false && password_verify($benutzer_passwort, $user['users_password']))
		{
			$_SESSION['sl_users_id'] = $user['users_id'];
			$_SESSION['sl_users_name'] = $user['users_username'];
			/*$SID = session_id();
			$SDATA = serialize($_SESSION);
			$statement = $pdo_sl->prepare("INSERT INTO sl_sessions VALUES ('" . $SID . "'," . $user['users_id'] . ", '" . $SDATA . "')");
			$result = $statement->execute();*/
//			header('Location: index.php');
			return 1;
		} 
		else
		{
			return 0;
		}
		
	}
}

function register($benutzer_name,$benutzer_passwort)
{
	global $pdo_sl;
	global $con_sl;
	
	if(isset($benutzer_name) & isset($benutzer_passwort))
	{
		$statement = $pdo_sl->prepare("INSERT INTO sl_users (users_sid, users_username, users_password) VALUES ('" . $benutzer_name . "','" . $benutzer_name . "','" . password_hash($benutzer_passwort, PASSWORD_DEFAULT) . "');");
		$result = $statement->execute();
	}
}

function anmelden_sl($session)
{
	global $pdo_sl;
	global $con_sl;
	
	if(isset($session))
	{
		$statement = $pdo_sl->prepare("SELECT * FROM sl_sessions WHERE sessions_id = '" . $session . "';");
		$result = $statement->execute();
		$sess = $statement->fetch();
		
		if ($sess !== false)
		{
			$statement = $pdo_sl->prepare("SELECT * FROM sl_users WHERE users_id = " . $sess['sessions_userid'] . ";");
			$result = $statement->execute();
			$user = $statement->fetch();
			
			$_SESSION['sl_users_id'] = $user['users_id'];
			$_SESSION['sl_users_name'] = $user['users_username'];
			
			header('Location: index.php');
			return 1;
		} 
		else
		{
			return 0;
		}
		
	}
}

function sso()
{
	if(isset ($_SERVER['REMOTE_USER']) & ($_SERVER['REMOTE_USER'] !=''))
	{
		if (strpos($_SERVER['REMOTE_USER'], '@') !== false)
		{
			// format: username@domain
			list($username, $domain) = explode('@', $_SERVER['REMOTE_USER'], 2);
		}
		else
		{
			// format: domain\username
			list($domain, $username) = explode("\\", $_SERVER['REMOTE_USER'], 2);
		}
		$tr = anmelden_ldap($username);
		
		
		
		
		return $tr;
	}
	else
	{
		return 0;
	}
	//echo 'No Remote_User';
}

function anmelden_ldap($benutzer_name)
{
		
		$ldap_address = "ldap://dc-2019-vb.stadt-hilden.de";
		$ldap_port = 389;
		
		if ($connect = ldap_connect($ldap_address, $ldap_port)) {
			
			ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);
			
			
			//if ($bind = ldap_bind($connect, $benutzer_domain . "\\" . $benutzer_name, $benutzer_passwort))
			if ($bind = ldap_bind($connect, "stadt-hilden\\kldap", "Hilden40721!"))
			{
				
				$dn = "DC=stadt-hilden,DC=de";
				$person = "$benutzer_name";
				$fields = "(|(sAMAccountName=$person))";
				
				$search = ldap_search($connect, $dn, $fields);
				$res = ldap_get_entries($connect, $search);
			
				$ldap_username = $res[0]['samaccountname'][0];
				ldap_close($connect);
				
				if ($ldap_username !== false)
				{
					return 1;
				} 
				else
				{
					return 0;
				}
				
			}
			else
			{
				return 0;
			}
	
		}
		else 
		{
			return 0;
		}
}



function root_path()
{
	$path1 = '';
	if(!preg_match("/^(http:\/\/)/", $_SERVER['HTTP_HOST']))
	{
		$server = "http://" . $_SERVER['HTTP_HOST'];
	}
	else
	{
		$server = $_SERVER['HTTP_HOST'];
	}
	
	if(!preg_match("/(\/)$/", $server)) $server = $server . '/';
	$path = explode('/', dirname(htmlentities($_SERVER['PHP_SELF'])));
			
	for($i=1; $i < (count($path)); $i++)
	{
		$path[$i] = '..';
		$path1 .= $path[$i] . '/';
	}
	
	return $path1;
}

function rec_rmdir ($path) {
    //prüfe ob Verzeichnis
    if (!is_dir ($path)) {
        return -1;
    }
    // oeffne das Verzeichnis
    $dir = @opendir ($path);
    
    // Fehler?
    if (!$dir) {
        return -2;
    }
    
    // gehe durch das Verzeichnis
    while ($entry = @readdir($dir)) {
        // wenn der Eintrag das aktuelle Verzeichnis oder das Elternverzeichnis
        // ist, ignoriere es
        if ($entry == '.' || $entry == '..') continue;
        // wenn der Eintrag ein Verzeichnis ist, dann 
        if (is_dir ($path.'/'.$entry)) {
            // rufe mich selbst auf
            $res = rec_rmdir ($path.'/'.$entry);
            // wenn ein Fehler aufgetreten ist
            if ($res == -1) { // dies duerfte gar nicht passieren
                @closedir ($dir); // Verzeichnis schliessen
                return -2; // normalen Fehler melden
            } else if ($res == -2) { // Fehler?
                @closedir ($dir); // Verzeichnis schliessen
                return -2; // Fehler weitergeben
            } else if ($res == -3) { // nicht unterstuetzer Dateityp?
                @closedir ($dir); // Verzeichnis schliessen
                return -3; // Fehler weitergeben
            } else if ($res != 0) { // das duerfe auch nicht passieren...
                @closedir ($dir); // Verzeichnis schliessen
                return -2; // Fehler zurueck
            }
        } else if (is_file ($path.'/'.$entry) || is_link ($path.'/'.$entry)) {
            // ansonsten loesche diese Datei / diesen Link
            $res = @unlink ($path.'/'.$entry);
            // Fehler?
            if (!$res) {
                @closedir ($dir); // Verzeichnis schliessen
                return -2; // melde ihn
            }
        } else {
            // ein nicht unterstuetzer Dateityp
            @closedir ($dir); // Verzeichnis schliessen
            return -3; // tut mir schrecklich leid...
        }
    }
    
    // schliesse nun das Verzeichnis
    @closedir ($dir);
    
    // versuche nun, das Verzeichnis zu loeschen
    $res = @rmdir ($path);
    
    // gab's einen Fehler?
    if (!$res) {
        return -2; // melde ihn
    }
    
    // alles ok
    return 0;
}

/* Aufruf:

if(zipFolder('bilder','bilder-archive')):
    print("ZIP File Erstellt");
endif;

*/

function zipFolder(string $folder,string $target){
    $files  = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder,FilesystemIterator::SKIP_DOTS));
    $zip    = new ZipArchive;
    $create = $target . '.zip';

    if($zip->open($create,ZipArchive::CREATE)):

        foreach($files as  $file):
            $zip->addFile(realpath($file),$file);

            print($file . " - Datei Hinzugefügt ". PHP_EOL);
        endforeach;

        $zip->close();    
    endif;

    return file_exists($create);
}

/* Aufruf:

if(zipFolder('bilder','bilder-archive-pwd','passwort')):
    print("ZIP File Erstellt");
endif;

*/

function zipFolderPassword(string $folder,string $target,string $password = ""):bool{
    $files  = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder,FilesystemIterator::SKIP_DOTS));
    $zip    = new ZipArchive;
    $create = $target . '.zip';

    if($zip->open($create,ZipArchive::CREATE)):

        if(!empty($password)):
            $zip->setPassword($password);
        endif;

        foreach($files as  $file):
            $zip->addFile(realpath($file),$file);

            if(!empty($password)):
                $zip->setEncryptionName($file, ZipArchive::EM_AES_256);
            endif;

            print($file . " - Datei Hinzugefügt ". PHP_EOL);
        endforeach;

        $zip->close();    
    endif;

    return file_exists($create);
}

function zipUnpack($inpZip,$unpFolder)
{
	$zip = new ZipArchive();

	if($zip->open($inpZip)):
		$zip->extractTo($unpFolder);
		$zip->close();
	endif;
}

function zipUnpackPassword($inpZip,$unpFolder,$password)
{
	$zip = new ZipArchive();

	if($zip->open($inpZip)):
		$zip->setPassword($password);
		$zip->extractTo($unpFolder);
		$zip->close();
	endif;
}

function download($url,$path)
{
	// Use basename() function to return the base name of file 
	
	if ($path === '')
	{
		$file_name = basename($url);
	}
	else
	{
		$file_n = basename($url);
		$file_name = $path . '/' . $file_n;
	}
	
   
	// Use file_get_contents() function to get the file
	// from url and use file_put_contents() function to
	// save the file by using base name
	if(file_put_contents($file_name,file_get_contents($url)))
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

?>