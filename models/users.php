<?php
class Users {
   
  private $id;
  private $role;
  private $account;
  private $password;
  private $name;
  private $wish_list;
  private $purchase_list;
  private $created_at;
  private $updated_at;
  private $deleted_at;

  function __set($variable, $value){
    if ($variable == "role")
    {
      $this->parseRole($value);
      return;
    }
    if($variable == "wish_list")
    {
      $this->convertWishList($value);
      return;
    }
    if($variable == "purchase_list")
    {
      $this->convertPurchaseList($value);
      return;
    }
    $this->$variable = $value;
  }

  function __get($variable) {
    return $this->$variable;
  }

  /**
   * 建構式
   */
  public function __construct(array $data = [])
  {
    $this->fill($data);
    $this->role = $this->parseRole($this->role);
    $this->wish_list = $this->convertWishList($this->wish_list);
    $this->purchase_list = $this->convertPurchaseList($this->purchase_list);
  }

  protected function fill(array $data)
  {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->{$key} = $value;
      }
    }
  }

  //將身份代碼轉換為文字
  protected function parseRole($gender)
  {
    return ($gender == 'M') ? '管理者' : '顧客';
  }

  //將願望清單轉換成json格式
  protected function convertWishList($wishList)
  {
    $this->wish_list = json_decode($wishList,JSON_UNESCAPED_UNICODE);
  }

  //將購買列表轉換成json格式
  protected function convertPurchaseList($purchaseList)
  {
    $this->purchase_list = json_decode($purchaseList,JSON_UNESCAPED_UNICODE);
  }
   
}