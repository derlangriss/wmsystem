<?php

class postgresql2jsonPDO{
 function getJSON($resultSet,$affectedRecords){
 
 $numberRows=0;
 $arrfieldName=array();
 $i=0;
 $json="";
	//print("Test");
 	while ($i < $resultSet->columnCount())  {
 		$meta = $resultSet->getColumnMeta($i);
                if (!$meta) {
		}else{
                $arrfieldName[$i]=$meta['name'];
		}
		$i++;
 	}
        
        
	$i=0;
	$json="[\n";
	while($row=$resultSet->fetch(PDO::FETCH_BOTH)) {
              
		$i++;
                $json.="{\n";
		for($r=0;$r < count($arrfieldName);$r++) {
                        
			$json.=" \"$arrfieldName[$r]\" :	\"$row[$r]\"";
			if($r < count($arrfieldName)-1){
				$json.=",\n";
			}else{
				$json.="\n";
			}
		}
		if($i!=$affectedRecords){
		 	$json.="\n},\n";
		 }else{
		 	$json.="\n}\n";
		}
	}
	$json.="\n]";
	
	return $json;
 }
}
class postgresql2jsonPDOaddrow {
    public function getJSON($resultSet, $affectedRecords, $rows) {

        $numberRows   = 0;
        $arrfieldName = array();
        $resultArray  = array();
        $i            = 0;
        $json         = "";
        //print("Test");
        while ($i < $resultSet->columnCount()) {
            $meta = $resultSet->getColumnMeta($i);
            if (!$meta) {
            } else {
                $arrfieldName[$i] = $meta['name'];
            }
            $i++;
        }

        $i = 0;

        while ($row = $resultSet->fetch(PDO::FETCH_ASSOC)) {
            $mergerow = $row + $rows;
            array_push($resultArray, $mergerow);
        }

        echo json_encode($resultArray);
    }
}
?>
