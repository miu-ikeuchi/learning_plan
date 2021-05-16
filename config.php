<?php

define('DSN', 'mysql:host=db;dbname=learning_plan;charset=utf8');
define('USER', 'admin_user');
define('PASSWORD', '1234');

// エラーメッセージを定数として定義
define('MSG_TITLE_REQUIRED', '学習内容を入力してください'); 
define('MSG_DUE_REQUIRED', '期限日を入力してください'); 
define('MSG_UPDATE_REQUIRED', '変更内容がありません');

define('PLAN_DATE_NULL', NULL);
define('PLAN_DATE_COMP', '');