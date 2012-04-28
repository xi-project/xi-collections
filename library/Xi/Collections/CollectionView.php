<?php
namespace Xi\Collections;

/**
 * A collection where transformer operations, ie. those yielding back new
 * collection instances, are applied lazily.
 */
interface CollectionView extends Collection
{
    /**
     * Coerce this view back into the underlying collection type
     *
     * @return Collection
     */
    public function force();
}