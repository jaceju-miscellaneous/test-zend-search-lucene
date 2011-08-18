<?php

// 初始化
require __DIR__ . '/init.php';

// 在索引目錄中建立索引相關檔案
$index = Zend_Search_Lucene::create($indexDir);

// 取得要建立索引的 HTML 檔案所在路徑
$iterator = new DirectoryIterator($htmlDir);

// 一一為 HTML 檔案建立索引
foreach ($iterator as $entry) {
    /* @var $entry DirectoryIterator */
    if ($entry->isFile()) {

        $file = $entry->getPath() . '/' . $entry->getFilename();
        echo $file, "\n";

        // 載入 HTML 檔案，並產生索引文件
        $doc = Zend_Search_Lucene_Document_Html::loadHTMLFile($file);

        // 加入檔案名稱欄位，但不加入索引 (僅儲存值)
        $doc->addField(Zend_Search_Lucene_Field::unIndexed('filename', $entry->getFilename(), 'utf-8'));

        // 加入建立時間欄位，但不加入索引 (僅儲存值)
        $doc->addField(Zend_Search_Lucene_Field::unIndexed('created', time(), 'utf-8'));

        // 加入更新時間欄位，但不加入索引 (僅儲存值)
        $doc->addField(Zend_Search_Lucene_Field::unIndexed('updated', time(), 'utf-8'));

        // 加入內容欄位，但不儲存 (僅加入索引)
        $doc->addField(Zend_Search_Lucene_Field::unStored('content', $doc->getHtmlBody(), 'utf-8'));

        // 將索引文件加入索引檔案中
        $index->addDocument($doc);
    }
}

// 完成索引的建立
$index->commit();
