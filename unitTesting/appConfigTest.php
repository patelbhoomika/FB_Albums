<?php
include_once '../appConfig.php';


class appConfigTest extends PHPUnit_Framework_TestCase  {
   
    protected $basicFunctionObj;
    
    function setUp() {
      $this->basicFunctionObj = new BasicFunction();
    }
   function test_getAlnumsPhoto_From_FB() {
        $selectedAlbumId=array('rose$751316455046101','whiteRose$751316455046101');
        $actual=$this->basicFunctionObj->getAlnumsPhoto_From_FB($selectedAlbumId);
        $this->assertEquals( $actual, $actual );
    }
    
    function test_remove_directory()
    {  
        $directory=$_SERVER["DOCUMENT_ROOT"]."/FB_Albums/albums/599dcc08543dc/";

        $this->assertTrue(
                !is_dir($directory), 
                'Directory does not exists'
        );
         $actual=$this->basicFunctionObj->remove_directory("D:/xampp/htdocs/FB_Albums/albums/599dcc08543dc/");
         $this->assertEquals( $actual, $actual);
    }
    protected function tearDown() {
      unset($this->basicFunctionObj);
    }

}



 
