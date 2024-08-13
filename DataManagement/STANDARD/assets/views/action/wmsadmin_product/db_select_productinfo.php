<?php

require '../dbconnect/connectdb_wms.php';

if (isset($_GET['idproduct'])) {

    $idproduct     = $_GET['idproduct'];
    $resultArray   = array();
    $resultArray02 = array();
    $resultArray03 = array();
    $resultArray04 = array();
    $resultArray05 = array();
    $resultArray06 = array();
    $query         = "SELECT * FROM product
              left join material_cost on material_cost.idmaterial_cost = product.material_cost_idmaterial_cost
              left join material_type on material_type.idmaterial_type = material_cost.material_type_idmaterial_type
              left join expense_type on expense_type.idexpense_type = material_cost.expense_type_idexpense_type
              WHERE idproduct =" . $idproduct;

    $stmt = $PDOconn->prepare($query);
    $stmt->execute();

    $num = $stmt->rowCount();
    if ($num == 0) {

        $rowarr = array(
            'sumspec'                   => 0,
            'countbox'                  => 0,
            'newpackageno'              => 1,
            'receivespec_idreceivespec' => $idreceivedetails,
            'idcodename'                => $idcodename,
            'package_qty'               => 0,
            'start_no'                  => '',
            'end_no'                    => '',
        );
        array_push($resultArray, $rowarr);
    } else {
        $intNumField = $stmt->columnCount();
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {

            for ($i = 0; $i < $intNumField; $i++) {
                $col     = $stmt->getColumnMeta($i);
                $columns = $col['name'];

                $arrCol[$columns] = $result[$columns];
            }
            $arrCol['success'] = 1;

            $strSQL02 = "SELECT idsize,size,idpsize,size_desc from psize ";
            $strSQL02 .= "left join product on product.idproduct = psize.product_idproduct ";
            $strSQL02 .= "left join size on size.idsize = psize.size_idsize ";
            $strSQL02 .= "WHERE idproduct ='" . $idproduct . "'";
            $stmt2 = $PDOconn->prepare($strSQL02);
            $stmt2->execute();
            $q            = 1;
            $intNumField2 = $stmt2->columnCount();
            while ($result2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                for ($i = 0; $i < $intNumField2; $i++) {
                    $col2               = $stmt2->getColumnMeta($i);
                    $columns2           = $col2['name'];
                    $arrCol2[$columns2] = $result2[$columns2];
                }
                $arrCol2['sizeseq'] = $q;
                array_push($resultArray02, $arrCol2);
                $arrCol['sizeea'] = $resultArray02;

                $q++;
            }

            $strSQL03 = "SELECT idfeature,feature,idpfeature,feature_desc from pfeature ";
            $strSQL03 .= "left join product on product.idproduct = pfeature.product_idproduct ";
            $strSQL03 .= "left join feature on feature.idfeature = pfeature.feature_idfeature ";
            $strSQL03 .= "WHERE idproduct ='" . $idproduct . "'";
            $stmt3 = $PDOconn->prepare($strSQL03);
            $stmt3->execute();
            $f            = 1;
            $intNumField3 = $stmt3->columnCount();
            while ($result3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
                for ($i = 0; $i < $intNumField3; $i++) {
                    $col3               = $stmt3->getColumnMeta($i);
                    $columns3           = $col3['name'];
                    $arrCol3[$columns3] = $result3[$columns3];
                }
                $arrCol3['featureseq'] = $f;
                array_push($resultArray03, $arrCol3);
                $arrCol['featureea'] = $resultArray03;

                $f++;
            }

            $strSQL05 = "SELECT idbrand,brand,idpbrand,brand_desc from pbrand ";
            $strSQL05 .= "left join product on product.idproduct = pbrand.product_idproduct ";
            $strSQL05 .= "left join brand on brand.idbrand = pbrand.brand_idbrand ";
            $strSQL05 .= "WHERE idproduct ='" . $idproduct . "'";
            $stmt5 = $PDOconn->prepare($strSQL05);
            $stmt5->execute();
            $b            = 1;
            $intNumField5 = $stmt5->columnCount();
            while ($result5 = $stmt5->fetch(PDO::FETCH_ASSOC)) {
                for ($i = 0; $i < $intNumField5; $i++) {
                    $col5               = $stmt5->getColumnMeta($i);
                    $columns5           = $col5['name'];
                    $arrCol5[$columns5] = $result5[$columns5];
                }
                $arrCol5['brandseq'] = $b;
                array_push($resultArray05, $arrCol5);
                $arrCol['brandea'] = $resultArray05;

                $b++;
            }

            $strSQL04 = "SELECT idunit,unit,idpunit,unit_desc from punit ";
            $strSQL04 .= "left join product on product.idproduct = punit.product_idproduct ";
            $strSQL04 .= "left join unit on unit.idunit = punit.unit_idunit ";
            $strSQL04 .= "WHERE idproduct ='" . $idproduct . "'";
            $stmt4 = $PDOconn->prepare($strSQL04);
            $stmt4->execute();
            $g            = 1;
            $intNumField4 = $stmt4->columnCount();
            while ($result4 = $stmt4->fetch(PDO::FETCH_ASSOC)) {
                for ($i = 0; $i < $intNumField4; $i++) {
                    $col4               = $stmt4->getColumnMeta($i);
                    $columns4           = $col4['name'];
                    $arrCol4[$columns4] = $result4[$columns4];
                }
                $arrCol4['unitseq'] = $g;
                array_push($resultArray04, $arrCol4);
                $arrCol['unitea'] = $resultArray04;

                $g++;
            }

            $strSQL06 = "SELECT idvendor,vendor,idpvendor,vendor_desc from pvendor ";
            $strSQL06 .= "left join product on product.idproduct = pvendor.product_idproduct ";
            $strSQL06 .= "left join vendor on vendor.idvendor = pvendor.vendor_idvendor ";
            $strSQL06 .= "WHERE idproduct ='" . $idproduct . "'";
            $stmt6 = $PDOconn->prepare($strSQL06);
            $stmt6->execute();
            $s            = 1;
            $intNumField6 = $stmt6->columnCount();
            while ($result6 = $stmt6->fetch(PDO::FETCH_ASSOC)) {
                for ($i = 0; $i < $intNumField6; $i++) {
                    $col6               = $stmt6->getColumnMeta($i);
                    $columns6           = $col6['name'];
                    $arrCol6[$columns6] = $result6[$columns6];
                }
                $arrCol6['vendorseq'] = $s;
                array_push($resultArray06, $arrCol6);
                $arrCol['vendorea'] = $resultArray06;

                $s++;
            }
            array_push($resultArray, $arrCol);
        }
    }
    echo json_encode($resultArray);
}
