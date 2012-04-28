<?php
namespace Xi\Collections\Util;

class CallbackFilterIterator extends \FilterIterator
{
	/**
	 * @var callback($value, $key)
	 */
	protected $callback;

	public function __construct(\Traversable $iterator, $callback)
	{
		parent::__construct($iterator);
		$this->callback = $callback;
	}

	public function accept()
	{
		$callback = $this->callback;
		return $callback($this->current(), $this->key());
	}
}