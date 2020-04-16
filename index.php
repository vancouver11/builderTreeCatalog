<?php

 $dir = $argv[1] ?? __DIR__;
$typeFiles = $argv[2] ?? 'all';

function tree($dir,$typeFiles){
    
    $array=[];
    if (is_dir($dir)) {
        
        if ($dh = opendir($dir)) {
            $absPath =realpath($dir);
            while (($file = readdir($dh)) !== false) {
                 
                $path = $absPath."\\".$file;

                 if($file !="." && $file !=".." && is_dir($path)){
                   
                    $arrayFolder = [];
                    $arrayFolder[$file]=tree($path,$typeFiles);
                    //$arrayFolder[$file] = empty($arrayFolder[$file])? "Empty Folder":$arrayFolder[$file];
                    $arrayFolder['size']=getSizeFolder($path)." Byte";
                    $array[] = $arrayFolder; 
                }else{

                        if($file !="." && $file !=".." && checkTypeFile($file, $typeFiles)){  
                            $absPathFile = $absPath. "\\".$file;
                            $arrayNew = [];
                            $arrayNew['name']=$file;
                            //$arrayNew['absPathCatalog']=$absPath;
                            $arrayNew['size'] = round(filesize($absPathFile)/1024, 2,PHP_ROUND_HALF_UP) ." kB";
                            $array[]=$arrayNew;
                        }
                    }
   
            }    
            
        }
        closedir($dh);     
    }
      return $array;      
}


 echo "<pre>";
print_r(tree($dir,$typeFiles));
echo "<pre>";  


function getSizeFolder($path){
    $summ=0;
    $array = scandir($path);
    foreach ($array as $key => $value) {

        if($value !="." && $value !=".."){
            $pathToFile = $path."\\".$value;
            if(is_dir($pathToFile)){
                $summ +=getSizeFolder($pathToFile);
            }
            $summ +=filesize(realpath($pathToFile));
        }
        
        
    }
return $summ;
} ;
 

function checkTypeFile($file, $typeFiles){
    if ($typeFiles ==='all'){
        return true;
    } 
    $positionPoint = strripos($file, '.');
    $nameFileType = substr($file, ++$positionPoint);
    if(strripos($typeFiles, $nameFileType)!== false){
        return true;
    }
    return false;
} 

