<?php

// 設定載入路徑
set_include_path(join(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/include'),
    get_include_path(), // 要包含 Zend Framework 的所在路徑
)));

// 設定自動載入
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

// 設定處理的字元編碼
setlocale(LC_ALL, 'zh_TW.UTF-8');

// 指定路徑
$indexDir = realpath(__DIR__ . '/index');
$htmlDir = realpath(__DIR__ . '/html');

// 設定預設的分詞分析器
Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Phpbean_Lucene_Analyzer());
