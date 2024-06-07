<?php

class Block
{
	protected $data;

	protected $object = false;

	protected $array = false;

	protected $scalar = false;


	/**
	 * Set data
	 * @param type $data
	 */
	public function __construct($data = null)
	{
		$this->data = $data;

		if(is_object($data)){
			$this->object = true;
			$this->array = $data instanceof ArrayAccess;
			$this->methods = get_class_methods($data);
		} elseif(is_array($data)){
			$this->array = true;
			$this->iterable = true;
			$this->countable = true;
		} elseif(is_scalar($data)) {
			$this->scalar = true;
		}
	}


	/**
	 * Get a property and wrap it in a block
	 * @param string $name
	 * @return Block
	 */
	public function __get($name)
	{
		if($this->array && isset($this->data[$name])) {
			return new Block($this->data[$name]);
		} elseif($this->object && isset($this->data->{$name})) {
			return new Block($this->data->{$name});
		} else {
			return new Block(null);
		}
	}


	/**
	 * Get the raw data of a block
	 * @return type
	 */
	public function __invoke()
	{
		return $this->data;
	}


	/**
	 * Check if a property was set
	 * @param type $name
	 * @return type
	 */
	public function __isset($name)
	{
		return ($this->array && isset($this->data[$name]))
		|| ($this->object && isset($this->data->{$name}));
	}


	/**
	 * Get raw data of a property
	 * @param string $name
	 * @param array $args
	 * @return mixed
	 */
	public function __call($name, $args) {
		if ($this->array && isset($this->data[$name])) {
			return $this->data[$name];
		}

		if ($this->object) {
			if(in_array($name, $this->methods)) {
				return call_user_func_array([$this->data, $name], func_get_args());
			}
			if (isset($this->data->{$name})) {
				return $this->data->{$name};
			}
		}
		return null;
	}


	/**
	 * Convert data to string
	 * @return string
	 */
	public function __toString()
	{
		if($this->scalar) {
			return (string) $this->data;
		} else {
			return '';
		}
	}
}