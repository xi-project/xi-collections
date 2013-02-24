<?php

namespace Xi\Collections\Util;

/**
 * An IteratorIterator that reindexes the underlying iterator. Provides numeric
 * indexes starting from zero.
 */
class ReindexIterator extends \IteratorIterator
{
    private $index;

    public function rewind()
    {
        parent::rewind();
        $this->index = 0;
    }

    public function key()
    {
        return $this->index++;
    }
}
