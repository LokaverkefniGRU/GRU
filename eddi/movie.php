<?php 
class Movie
{
	public $id; 
	public $name;

	public function __construct($name)
    {
        $this->id = 123;
        $this->name = $name;

    }
}
?>