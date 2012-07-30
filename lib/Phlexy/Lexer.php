<?php

class Phlexy_Lexer {
    protected $regex;
    protected $offsetToToken;

    public function __construct(array $tokenMap) {
        $this->regex = '((' . implode(')|(', array_keys($tokenMap)) . '))A';
        $this->offsetToToken = array_values($tokenMap);
    }

    public function lex($string) {
        $tokens = array();

        $offset = 0;
        while (isset($string[$offset])) {
            if (!preg_match($this->regex, $string, $matches, 0, $offset)) {
                throw new Phlexy_LexingException(sprintf('Unexpected character "%s"', $string[$offset]));
            }

            // find the first non-empty element (but skipping $matches[0]) using a quick for loop
            for ($i = 1; '' === $matches[$i]; ++$i);

            $tokens[] = array($matches[0], $this->offsetToToken[$i - 1]);

            $offset += strlen($matches[0]);
        }

        return $tokens;
    }
}