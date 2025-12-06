<?php
function isInt($v){ return filter_var($v, FILTER_VALIDATE_INT) !== false; }
