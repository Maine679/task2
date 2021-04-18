<?php
session_start();
require_once 'function.php';

logout();
set_flash_message('success','Вы вышли из системы');
redirect_to('../page_login.php');





