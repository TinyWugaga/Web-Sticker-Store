<?php
class Wishes {

  private $id;
  private $user_id;
  private $sticker_id;
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
