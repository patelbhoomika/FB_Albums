<?php

class delete_directory
{
    public function remove_directory($directory)
    {
        if (isset($directory)) {
            foreach (glob("{$directory}/*") as $file) {
                if (is_dir($file)) {
                    $this->remove_directory($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($directory);
        }
    }
}
