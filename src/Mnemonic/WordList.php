<?php

namespace BitWasp\Bitcoin\Mnemonic;

abstract class WordList implements WordListInterface, \Countable
{
    /**
     * @return int
     */
    public function count()
    {
        return count($this->getWords());
    }

    /**
     * @param $index
     * @return mixed
     */
    public function getWord($index)
    {
        $words = $this->getWords();
        if (!isset($words[$index])) {
            throw new \InvalidArgumentException(__CLASS__ . " does not contain a word for index [{$index}]");
        }

        return $words[$index];
    }
}
