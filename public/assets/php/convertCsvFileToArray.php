<?php
/**
 * [convertCsvFileToArray description]
 * @param  string $filePath CSVファイルパス。デフォルト値 -> '../files/upload.csv'
 * @return [array]          配列。連想配列ではない。
 */
function convertCsvFileToArray($filePath = '../files/upload.csv')
{
    try {
        $csv      = new SplFileObject($filePath);
        $csv->setFlags(SplFileObject::READ_CSV);
        $data = [];
        $i = 0;
        foreach($csv as $key => $line)
        {
            $j = 0;
            foreach ($line as $buf)
            {
                $data[$i][$j] = mb_convert_encoding($buf, 'utf8', 'sjis');
                $j++;
            }
            $i++;
        }
        return $data;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>