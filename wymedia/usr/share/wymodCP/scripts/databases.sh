#!/bin/sh
echo "<br><h3> T_ANTENNA </h3><br><table id=gradient-style >" 
sqlite3 -header -html /etc/params/wyscan/wyscan.db "select * from T_ANTENNA;"
echo "</table><br><h3>T_CONFIGURATION</h3><br><table id=gradient-style >" 
sqlite3 -header -html /etc/params/wyscan/wyscan.db "select * from T_CONFIGURATION;" 
echo "</table><br><h3>T_DEVICE</h3><br><table id=gradient-style >" 
sqlite3 -header -html /etc/params/wyscan/wyscan.db "select * from T_DEVICE;"
echo "</table><br><h3>T_FAVORITE</h3><br><table id=gradient-style >" 
sqlite3 -header -html /etc/params/wyscan/wyscan.db "select * from T_FAVORITE ;"
echo "</table><br><h3>T_FAVORITE_SET</h3><br><table id=gradient-style >" 
sqlite3 -header -html /etc/params/wyscan/wyscan.db "select * from T_FAVORITE_SET;"
echo "</table><br><h3>T_LNB_TYPE</h3><br><table id=gradient-style >"
sqlite3 -header -html /etc/params/wyscan/wyscan.db "select * from T_LNB_TYPE;" 
echo "</table><br><h3>T_PROGRAM</h3><br><table id=gradient-style >" 
sqlite3 -header -html /etc/params/wyscan/wyscan.db "select * from T_PROGRAM ;"
echo "</table><br><h3>T_PROGRAM_DETAIL</h3><br><table id=gradient-style >" 
sqlite3 -header -html /etc/params/wyscan/wyscan.db "select * from T_PROGRAM_DETAIL;"
echo "</table><br><h3>T_PROGRAM_DETAIL_ITEM</h3><br><table id=gradient-style >" 
sqlite3 -header -html /etc/params/wyscan/wyscan.db "select * from T_PROGRAM_DETAIL_ITEM;"
echo "</table><br><h3>T_SERVICE</h3><br><table id=gradient-style >" 
sqlite3 -header -html /etc/params/wyscan/wyscan.db "select * from T_SERVICE;"
echo "</table><br><h3>T_TRANSPONDER</h3><br><table id=gradient-style >" 
sqlite3 -header -html /etc/params/wyscan/wyscan.db "select * from T_TRANSPONDER;"
echo "</table><br><h3>T_TRANSPONDER_SET</h3><br><table id=gradient-style >" 
sqlite3 -header -html /etc/params/wyscan/wyscan.db "select * from T_TRANSPONDER_SET;"
echo "</table><br><h3>Config</h3><br><table id=gradient-style >" 
sqlite3 -header -html /etc/params/wymedia/.wyplay_db.-1.db "select * from config;"
echo "</table><br><h3>Object</h3><br><table id=gradient-style >" 
sqlite3 -header -html /etc/params/wymedia/.wyplay_db.-1.db "select * from object;"
echo "</table><br><h3>Property</h3><br><table id=gradient-style >"
sqlite3 -header -html /etc/params/wymedia/.wyplay_db.-1.db "select * from property;"
echo "</table>" 
echo "</body></html>" 