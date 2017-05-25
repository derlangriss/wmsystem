<?php
$hostname = "localhost";
$dbUser = "mkmorgangling";
$dbPass = "nepenthes";
$dbName = "qsbgcoll";
// connect to the database
$objConnect = pg_connect("host=$hostname dbname=$dbName user=$dbUser password=$dbPass") or die("Cannot connect to the database");
	
	$strSQL = "SELECT collectionlocality,collectionlongd,collectionlongm,collectionlongs,collectionlatd,collectionlatm,collectionlats,collectorsen,collectionmasl,coll_code,coll_year,coll_number,provinceen,collectionenddate,collectionmethodsdetails,idspecimens,specimens.torder_idtorder,specimens.family_idfamily,subfamily,specimens.genus_idgenus,subgenus,specimens.species_idspecies,specimens.specimen_number,specimens.taxatypes_idtaxatypes FROM specimens
        left join collection on (collection.idcollection = specimens.collection_idcollection)
        left join collectionmethods on (collectionmethods.idcollectionmethods = collection.collectionmethods_idcollectionmethods)
        left join collectors on (collectors.idcollectors = collection.collectors_idcollectors)
        left join amphurs on (collection.amphurs_idamphurs = amphurs.idamphurs)
        left join province on (amphurs.province_idprovince = province.idprovince)
        left join torder on (specimens.torder_idtorder = torder.idtorder)
        left join family on (specimens.family_idfamily = family.idfamily)
        left join genus on (specimens.genus_idgenus = genus.idgenus)
        left join species on (specimens.species_idspecies = species.idspecies)
        
WHERE idspecimens  = '".$_POST["sCode"]."'";
	
        
	$objQuery = pg_query($strSQL);
	$intNumField = pg_num_fields($objQuery);
	$resultArray = array();
	while($obResult = pg_fetch_array($objQuery))
	{
            
            
		$arrCol = array();
		for($i=0;$i<$intNumField;$i++)
		{
                    
                        if(pg_field_name($objQuery,$i) == 'specimen_number'){
				if($obResult[$i]==null){
					$obResult[$i]=1;
				}
				$obResult[$i] = sprintf('%04d',$obResult[$i]);			
			}
			$arrCol[pg_field_name($objQuery,$i)] = $obResult[$i];
		}
		array_push($resultArray,$arrCol);
	}
	
	pg_close($objConnect);
	
	echo json_encode($resultArray);	
	
?>
