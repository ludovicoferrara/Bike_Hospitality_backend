<?
$keyd="";
$keyf="";
$ukey="";

if(isset($_GET['f']))    $f   = $_GET['f'];
if(isset($_GET['keyd'])) $keyd = $_GET['keyd'];
if(isset($_GET['fkey'])) $keyf = $_GET['fkey'];
if(isset($_GET['ukey'])) $ukey = $_GET['ukey'];

$language = "it";
 if(!isset($_incpath)) $_incpath = "./";
 $_main_dir = "./_main/";
 //CLASSI , GESTORI DELLE CLASSI E FILE DI INCLUSIONE GENERALI
include_once $_main_dir."_inc/dbmanager.php";   //istanze database, include db.class.php - definizione delle classi



$scarica = false;
if($keyd!="")
{
$mydb->DoSelect($sql);

}
    
    {
        $path = rtrim($_GET['path'],"/");
        $path = $baseDir.$path."/";
    }
    if(isset($ukey))
    {
        $sql = "SELECT idutente FROM t_utenti WHERE MD5(idutente) = '$ukey'";
        $mydb->DoSelect($sql);
        if(($ru=$mydb->GetRow()))
        {
            $idu = $ru['idutente'];
            $path = $baseDir."userdata/$idu/";
            
        } else die;
    }

    $args = array(
    
} elseif($keyf!="")
{
$sql = "SELECT * FROM t_files WHERE MD5(id_files) = '$keyf'";
$mydb->DoSelect($sql);
if($rf=$mydb->GetRow())
{
    $nome_file = $rf['nome'];
    $fullpath = $rf['path'];
    $scarica = true;
    
    $id = $rf['id_files'];
    
    
   # $sql = "UPDATE t_files SET last_download=NOW() WHERE id_files=$id";
   # $mydb->ExecSql($sql);
    
    if($ukey!="" && $rf['id_utente']==0)
    {
        $sql = "SELECT idutente FROM t_utenti WHERE MD5(idutente)='$ukey'";
        $mydb->DoSelect($sql);
       if( ($ru=$mydb->GetRow()) )
       {
           $idu = $ru['idutente'];
           $sql = "INSERT IGNORE INTO t_files (nome, path, id_utente, id_frel) VALUES ('$nome_file', '$fullpath', $idu, $id) ";
           $mydb->ExecSql($sql);
           $id = $mydb->LastInsertedId;
       }
    }
    
    if(isset($idu) && $idu>0)
    {
        $sql = "UPDATE t_files SET last_download=NOW() WHERE id_utente=$idu AND path='$fullpath' AND nome='$nome_file' ";
        $mydb->ExecSql($sql);
    }
    
    $path = str_replace($nome_file, "", $fullpath);
    $path = rtrim($path,"/")."/";


$args = array(
		'download_path'		=>	$path,
		'file'			=>	$nome_file,		
		'extension_check'	=>	TRUE,
		'referrer_check'	=>	FALSE,
		'referrer'		=>	NULL,
		);

}
//var_dump($args);
}
header("Content-Type: application/pdf; name=".$nome_file);
readfile($path.$nome_file);
?>