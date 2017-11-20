<?php

if ( !function_exists('dir_copy') ) {
	function dir_copy($source, $dest) {
		if ( !is_dir($dest) ) {
			mkdir($dest, 0755);
		}
		foreach ( $iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
				RecursiveIteratorIterator::SELF_FIRST) as $item) {
			if ( $item->isDir() 
					&& !is_dir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName())
					&& strpos($iterator->getSubPathName(), '.svn') === FALSE ) {
				mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName(), 0755);
			} else if ( !$item->isDir() 
					&& is_dir(dirname($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName())) ) {
				copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
			}
		}
	}
}
