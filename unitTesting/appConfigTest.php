<?php
include_once '../appConfig.php';


class appConfigTest extends PHPUnit_Framework_TestCase  {

   

    public function setUp() {
      $basicFunctionObj = new BasicFunction();
    }

    public function tearDown() {
      unset($this->basicFunctionObj);
    }
    public function test_getAlnumsPhoto_From_FB() {
        //$this->basicFunctionObj = new BasicFunction();
        $basicFunctionObj = new BasicFunction();
        $selectedAlbumId=array('rose$751316455046101','whiteRose$751316455046101');
        $basicFunctionObj->getAlnumsPhoto_From_FB($selectedAlbumId);
    }
    public function test_remove_directory()
    {
       
        $directory=$_SERVER["DOCUMENT_ROOT"]."/FB_Albums/albums/599dcc08543dc/";
        //$this->assertDirectoryExists('/path/to/directory');

        $this->assertTrue(
                !is_dir($directory), 
                'Directory does not exists'
        );
        
         $basicFunctionObj = new BasicFunction();
         $basicFunctionObj->remove_directory("D:/xampp/htdocs/FB_Albums/albums/599dcc08543dc/");
    }


}



 
