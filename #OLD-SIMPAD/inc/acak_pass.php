<?php
function acakpass($long){
		$huruf = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';//buat karakter yang akan digunakan sebagai password
		$st = '';
		for($i=0; $i<$long; $i++){
			$p = rand(0, strlen($huruf)-1);
			$st .=$huruf{$p};
		}
		return $st;
	}
	$us = acakpass(6); // untuk mencetak
	$ps = acakpass(8);
?>	