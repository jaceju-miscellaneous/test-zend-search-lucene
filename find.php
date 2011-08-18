<?php

// 初始化
require __DIR__ . '/init.php';

// 打開索引檔案
$index = Zend_Search_Lucene::open($indexDir);

// 取得關鍵字
$keyword = $_GET['keyword'];

// 從關鍵字中建立 query 物件
$query = Zend_Search_Lucene_Search_QueryParser::parse($keyword, 'utf-8');

// 搜尋索引
$hits = $index->find($query);

// 如果有找到對應的資訊，就加到結果集中
$result = array();
foreach ($hits as $hit) {
    $result[] = array(
        'id' => $hit->id,
        'score' => $hit->score,
        'title' => $hit->title,
        'filename' => $hit->filename,
        'created' => $hit->created,
        'updated' => $hit->updated,
    );
}

// 以 JSON 格式輸出結果
echo json_encode($result);
