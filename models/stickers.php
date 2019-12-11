<?php
class Stickers {

  private $id;
  private $title;
  private $author;
  private $description;
  private $price;
  private $created_at;


  function __set($variable, $value)
  { }

  function __get($variable)
  {
    return $this->$variable;
  }

  /**
   * 建構式
   */
  public function __construct(array $data = [])
  {
    $this->fill($data);
  }

  protected function fill(array $data)
  {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->{$key} = $value;
      }
    }
  }
}
