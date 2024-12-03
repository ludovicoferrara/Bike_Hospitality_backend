<!--#inc.eventi-->
<?php
if(!isset($tab) || $tab=="") $tab=6;
if(isset($tab2) && $tab2>1) $tab2=0;

$_postfix = "Eventi";

if(!isset($ord)) $ord = my_statevar_read("ord$_postfix");
if(!isset($searchstr)) $searchstr = my_statevar_read("searchstr$_postfix");
if(!isset($nxpage)) $nxpage = my_statevar_read("nxpage$_postfix");

if(!isset($isubsezione) || $isubsezione=="") $isubsezione=0;
if(empty($nxpage)) $nxpage=50;


?>
<script>

function setTab(t)
{
    document.frmins.tab.value = t;
    document.frmins.sezione.value='cms,0';
    document.frmins.submit();
}


function editItm(id){
	
        document.frmmenu.sezione.value='cms,2';
        document.frmmenu.id.value=id;
	document.frmmenu.submit();
	
}
	
	

function delItm(id){
	
        if(confirm('ATTENZIONE: confermi la cancellazione dell\'evento?'))
        {
	document.frmmenu.azione.value='delItm';
        document.frmmenu.id.value=id;
	document.frmmenu.submit();
        }
}


function back(){
	
	document.frmmenu.sezione.value='cms,0';
        document.frmmenu.id.value=0;
	document.frmmenu.submit();
	
}

function refresh()
{
    waitpage();
    document.frmins.submit();
}


function setOrd(strord){
    
        var ord = '<?=$ord?>';
        
        if(strord=='nome')
        {
            if(ord=='0') ord = 1;
            else ord = '0';
        }

        if(strord=='data_reg')
        {
            if(ord=='4') ord = 5;
            else ord = '4';
        }
        if(strord=='codice')
        {
            if(ord=='6') ord = 7;
            else ord = '6';
        }
        if(strord=='data_evento')
        {
            if(ord=='8') ord = 9;
            else ord = '8';
        }
    
    
	waitpage();
    	document.frmins.ord.value=ord;
    	document.frmins.submit();
    }
</script>
<div class="box_cnt">

<?
if($isubsezione==0 )
{
?>
<?php

if($azione=="delItm")
{
    $sql = "SELECT ti.* FROM eventi as ti WHERE ti.id=$id ";
    $mydb->DoSelect($sql);
    $ru2 = $mydb->GetRow();

    $img    = $ru2['immagine'];

    $sql = "DELETE FROM eventi WHERE id=$id";
    $mydb->ExecSql($sql);
    
    if(is_file("main/contents/media/$img")) unlink("main/contents/media/$img");
}


if(!isset($npage)) $npage = 0;

switch ($ord){
	case 0: {
		
		$orderby = "ti.nome ASC";
		
	} break;
	
	case 1: {
		
		$orderby = "ti.nome DESC";
		
	} break;


        case 4: {
		
		$orderby = "ti.data_ins ASC";
		
	} break;
    	case 5: {
		
		$orderby = "ti.data_ins DESC";
		
	} break;
    
       case 6: {
		
		$orderby = "tu.codice ASC";
		
	} break;
    	case 7: {
		
		$orderby = "tu.codice DESC";
		
	} break;
        case 8: {
		
		$orderby = "ti.data_rif ASC";
		
	} break;
    	case 9: {
		
		$orderby = "ti.data_rif DESC";
		
	} break;
    

	default: 
        {
            $ord = 0;
            $orderby = "ti.nome ASC";
        }
}

$cond = "";
$from = "";
if(isset($searchstr) && $searchstr!="")
{
$cond .= " AND (nome LIKE '%$searchstr%') ";
}

$sql = "SELECT COUNT(*) FROM eventi as ti $from WHERE 1 $cond";
$mydb->DoSelect($sql);
$ntot = 0;
if( ($rcount = $mydb->GetRow()) )
{
$ntot = $rcount[0];
}
$ntotpage = floor($ntot/$nxpage);
if($ntotpage != $ntot/$nxpage) $ntotpage++;
	
$nn = $npage*$nxpage;


$sql = "SELECT ti.* FROM eventi as ti  WHERE  ti.id_circuito=$id_location  $cond ORDER BY $orderby LIMIT $nn, $nxpage";
$a_ = $mydb->DoSelect($sql);


 ?>


    <div style='text-align: left'>
            <a href="?MSID=<?= $MSID ?>&tab=<?=$tab?>&sz=cms,4"><img src="main/contents/icone/add.png" class="ico24" align="absmiddle" /> Aggiungi evento</a>
    </div>



    <form name="frmins" method="post" action="<? echo $action ?>" >
            
        <div class="spessore" style="height:20px"></div>
        
        <div class="testo2" align="left">
            Filtra per nome: <input type="text" name="searchstr" value="<?=$searchstr?>" />
            <img src="main/contents/icone/magnifier.png" class="ico24 imgcliccabile" onclick="refresh()" align="absmiddle" />
            <? if($searchstr!=""){?>
            <img src="main/contents/icone/magnifier-r.png" class="ico24 imgcliccabile" onclick="document.frmins.searchstr.value='';refresh()" align="absmiddle" />
            <? } ?>
                    
        <div class="spessore" style="height:20px"></div>
       
<div style="text-align: left">
Visualizza <input type="text" name="nxpage" value="<?=$nxpage?>" class="lite mini" /> righe per pagina <img src="main/contents/icone/right-arrow-1.png" class="ico24 cliccabile rounded" align="absmiddle" onclick="document.frmins.submit()" /> 
</div>    
<div class="spessore" style="height:10px" align="left"></div> 
<? /*
<div style="text-align: left" class="testo_evi">
<? if($esporta=='1')
{
    #$sqlfull = "SELECT * FROM t_utenti WHERE level<='1'  $cond ORDER BY $orderby ";
    $sqlfull = "SELECT tu.* FROM t_utenti as tu, t_prodotti as tp WHERE tu.id_anag=tp.id AND tu.level<='1'  $cond ORDER BY $orderby ";# ";
    $a_full = $mydb->DoSelect($sqlfull);
    include "esporta.inc.php";
    
    ?>
<a href="download.php?path=csv&f=elenco.xls&t=<?=time()?>" target="_blank">scarica xls</a><br/>
<a href="download.php?path=csv&f=elenco.csv&t=<?=time()?>" target="_blank">scarica csv</a><br/>
<a href="download.php?path=csv&f=elenco.xlsx&t=<?=time()?>" target="_blank">scarica xlsx</a><br/>
<?
}*/

?>

 </div>   
   <div class="spessore" style="height:10px"></div>
   <div class="admtitolo" align="left">
   <? 
   $pagmode = '1';$frm = "document.frmins";
   $href0 = "?MSID=$MSID&sz=cms";
   
    include "paginazione.php"; 
   ?>	
   <div class="spessore" style="height:10px"></div>

   </div>       
           
        <table class="tablegrid3 tableresp" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th></th>
                    <th  onclick="setOrd('nome')"><span class="underlined cliccabile">Evento</span></th>
                    <th  onclick="setOrd('data_evento')"><span class="underlined cliccabile">Data evento</span></th>
                    <th  onclick="setOrd('categoria')"><span class="underlined cliccabile">Categoria</span></th>
                    <th  >Localit&agrave;</th>
                    <th></th>
                </tr>
            </thead>
            
            <tbody>
                
            
        <?
        $i=0;
        
        #while($r=$a_utenti[$i++])
        if(is_array($a_)) foreach($a_ as $i => $r)
        {
            $nome    = mydecodeTxt($r['nome']);

            $idu = $r['id'];

            $localita="";
            if($r['provincia']!="") $localita= getProvincia($r['provincia']);
            $categoria="";
            $data_reg = Date_fromdb($r['data_ins']);
            $data_rif = Date_fromdb($r['data_rif']);
            
        ?>
        <tr>
            <td data-label="" style="min-height:32px">
                <div class="fleft"><?=$i+1?></div>
                <div class="tdToolbar1 fright">
                    <a href="javascript:editItm(<?=$idu?>)" title="Apri scheda"><img src="main/contents/icone/edit-icon.png" class="imgcliccabile ico20 fleft" /></a>
                    <a href="javascript:reportItm(<?=$idu?>)"  title="Report"><img src="main/contents/icone/book.png" class="imgcliccabile ico20 fleft " style="filter:invert(1);" /></a>
                    <a href="javascript:delItm(<?=$idu?>)"  title="Elimina"><img src="main/contents/icone/cancel.png" class="imgcliccabile ico20 fright" /></a>
                </div>
            </td>
            <td data-label="Cognome"><?=$nome?></td>
            <td data-label="Data evento"><?=$data_rif?></td>
            <td><?=$categoria?></td>
            <td data-label="Località"><?=$localita?></td>
            
                        
            <td class="tdToolbar" style="min-width:260px">
                <a href="javascript:editItm(<?=$idu?>)" title="Apri scheda"><img src="main/contents/icone/edit-icon.png" class="imgcliccabile ico20 fleft" /></a>
                <a href="javascript:reportItm(<?=$idu?>)"  title="Report"><img src="main/contents/icone/book.png" class="imgcliccabile ico20 fleft " style="filter:invert(1);" /></a>
                <a href="javascript:delItm(<?=$idu?>)"  title="Elimina"><img src="main/contents/icone/cancel.png" class="imgcliccabile ico20 fright" /></a>
            </td>
        </tr>    
        <?
            
        }
        ?>
            </tbody>
        </table>
        
        <input type="hidden" name="MSID" value="<?=$MSID?>" />
        <input type="hidden" name="sezione" value="<?=$sezione?>" />
        <input type="hidden" name="azione" value="" />
        <input type="hidden" name="id" value="" />
        
        <input type="hidden" name="tab" value="<?=$tab?>" />
        <input type="hidden" name="tab2" value="<?=$tab2?>" />  
        
        <input type="hidden" name="ord" value="<?=$ord?>" />
        </form>
      
  <div class="admtitolo" align="left">
   <? 
   $pagmode = '1';$frm = "document.frmins";
   $href0 = "?MSID=$MSID&sz=cms&tab=2";
   
    include "paginazione.php"; 
   ?>	
   <div class="spessore" style="height:60px"></div>
       </div>
     
<? 
my_statevar_create("ordItinerari",$ord);
my_statevar_create("searchstrItinerari",$searchstr);
my_statevar_create("nxpageItinerari",$nxpage);

?>
<? } elseif($isubsezione==2){ ?>
    
    <?
    if(!isset($tab2) || $tab2=="" || $tab2>1 ) $tab2=0;
    ?>

        <div class="rbutton2" onclick="back()"><div>Indietro</div></div>
        
        <div class="spessore"></div><div class="spessore"></div>
        
        <?
        $sql = "SELECT ti.* FROM eventi as ti WHERE ti.id=$id ";
        $mydb->DoSelect($sql);
        $ru2 = $mydb->GetRow();

        $titolo       = mydecodeTxt($ru2['nome']);
        
        ?>
        <div class="titolo"><?=$titolo?></div>
        <div class="spessore"></div>
        
            
        <div class="tabmenu">
            <div class="tabitem <? if($tab2==0) echo "tabitemon"?>" onclick="setTab2(0)"><div class="itmTab">Dati Generali</div></div>
            <!--<div class="tabitem <? if($tab2==2) echo "tabitemon"?>" onclick="setTab2(2)"><div class="itmTab">Altri Dati</div></div>-->
            <div class="tabitem <? if($tab2==1) echo "tabitemon"?>" onclick="setTab2(1)"><div class="itmTab">Descrizione</div></div>
            
         
    </div>
    <div class="box_cnt">
        

        <?
        
        
        if($tab2==0){
            include "evento.php";
        }
        elseif($tab2==2){
            #include "evento_altro.inc.php";
        }
        elseif($tab2==1){
            include "evento_testi.inc.php";
        }
        ?>
      </div> 
        
<? } elseif($isubsezione==4){ ?>
        
        <div class="rbutton2" onclick="back()"><div>Indietro</div></div>
        
        <div class="spessore"></div><div class="spessore"></div>
        
        <div class="box_cnt">
        
       <? include "evento.php"; ?>
            
       </div> 
        
<? } ?>


</div>
    
   
