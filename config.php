<?php
$mysqli = new mysqli('localhost', 'root', '', 'tnts_library_db');
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);
