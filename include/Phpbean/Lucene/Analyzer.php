<?php

require_once 'Zend/Search/Lucene/Analysis/Analyzer/Common.php';

class Phpbean_Lucene_Analyzer extends Zend_Search_Lucene_Analysis_Analyzer_Common
{

    private $_position;
    private $_cnStopWords = array('的', '是', '地', '了');
    private $_inputLength;

    /**
     *
     * @param array $cnStopWords
     */
    public function setCnStopWords(array $cnStopWords)
    {
        $this->_cnStopWords = $cnStopWords;
    }

    /**
     * Reset token stream
     */
    public function reset()
    {
        $this->_position = 0;
        $search = array(",", "/", "\\", ".", ";", ":", "\"", "!", "~", "`", "^", "(", ")", "?", "-", "\t", "\n", "'", "<", ">", "\r", "\r\n", "$", "&", "%", "#", "@", "+", "=", "{", "}", "[", "]", "：", "）", "（", "．", "。", "，", "！", "；", "“", "”", "‘", "’", "［", "］", "、", "—", "　", "《", "》", "－", "…", "【", "】",);
        $this->_input = str_replace($search, ' ', $this->_input);
        $this->_input = str_replace($this->_cnStopWords, ' ', $this->_input);
        $this->_input = strtolower($this->_input);
    }

    /**
     * Tokenization stream API
     * Get next token
     * Returns null at the end of stream
     *
     * @return Zend_Search_Lucene_Analysis_Token|null
     */
    public function nextToken()
    {
        if ($this->_input === null) {
            return null;
        }

        $this->_inputLength = strlen($this->_input);

        while ($this->_position < $this->_inputLength) {
            while ($this->_position < $this->_inputLength &&
            $this->_input[$this->_position] == ' ') {
                $this->_position++;
            }
            $termStartPosition = $this->_position;
            $temp_char = isset($this->_input[$this->_position])
                       ? $this->_input[$this->_position]
                       : '';
            $isCnWord = false;
            if (ord($temp_char) > 127) {
                $i = 0;
                while ($this->_position < $this->_inputLength &&
                ord($this->_input[$this->_position]) > 127) {
                    $this->_position = $this->_position + 3;
                    $i++;
                    if ($i == 2) {
                        $isCnWord = true;
                        break;
                    }
                }
                if ($i == 1)
                    continue;
            }elseif (47 < ord($temp_char) && ord($temp_char) < 58) {
                while ($this->_position < $this->_inputLength &&
                ctype_digit($this->_input[$this->_position])) {
                    $this->_position++;
                }
            } else {
                while ($this->_position < $this->_inputLength &&
                ctype_alpha($this->_input[$this->_position])) {
                    $this->_position++;
                }
            }
            if ($this->_position == $termStartPosition) {
                return null;
            }

            $token = new Zend_Search_Lucene_Analysis_Token(
                            substr($this->_input, $termStartPosition, $this->_position - $termStartPosition),
                            $termStartPosition,
                            $this->_position);
            $token = $this->normalize($token);
            if ($isCnWord)
                $this->_position = $this->_position - 3;
            if ($token !== null) {
                return $token;
            }
        }
        return null;
    }

}
