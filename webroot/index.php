<?php
require_once dirname(__DIR__) . '/x2ts.php';

use x2ts\route\http\Request;
use x2ts\route\http\Response;


X::router()->route(new Request(), new Response());
