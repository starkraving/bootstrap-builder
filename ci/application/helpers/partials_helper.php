<?php


if ( !function_exists('setSpanFromContent') ) {
	function setSpanFromContent($column, $row) {
		$strClass = '';
		if ( isset($column['span']) && $column['span'] != 'auto' ) {
			return 'col-md-' . $column['span'];
		} else if ( count($row) > 1 ) {
			$totalSpan = 12;
			$totalColumns = count($row);
			foreach ($row as $rowColumn) {
				if ( isset($rowColumn['span']) && is_numeric($rowColumn['span']) ) {
					$totalSpan = $totalSpan - intval($rowColumn['span']);
					$totalColumns = $totalColumns - 1;
				}
			}
			$thisSpan = floor($totalSpan / $totalColumns);
			if ( $column['id'] === $row[$totalColumns-1]['id'] ) {
				$thisSpan += ( $totalSpan - ( $thisSpan * $totalColumns ) );
			}
			return 'col-md-' . $thisSpan;
		}
		return $strClass;
	}
}

if ( !function_exists('setClassFromContent') ) {
	function setClassFromContent($columnAttrs) {
		return ( !empty($columnAttrs['class']) ) ? $columnAttrs['class'] : '';
	}
}

if ( !function_exists('setStyleFromContent') ) {
	function setStyleFromContent($contentStyles) {
		$str = '';
		foreach ( $contentStyles as $key => $value ) {
			$str .= $key . ': ' . $value . '; ';
		}
		return $str;
	}
}