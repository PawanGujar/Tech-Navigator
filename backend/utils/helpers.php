<?php
function jsonOk($data){ header('Content-Type: application/json; charset=utf-8'); echo json_encode(array_merge(['ok'=>true], $data)); exit; }
function jsonErr($msg){ header('Content-Type: application/json; charset=utf-8'); echo json_encode(['ok'=>false,'message'=>$msg]); exit; }
