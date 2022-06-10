<?php 
namespace App\Helper;
class Recusive{
    private $data;
    private $html = '';

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function dataRecusive($parent_id, $id = 0,$text='')
    {
        foreach ($this->data as $key => $value) {
            if($value->parent_id == $id){
                // if(!empty($parent_id) && $parent_id == $value->id ){
                    $this->html .= "<option value='".$value->id."'>".$text . $value->name."</option>";
                // }else{
                //     $this->html .= "<option value='".$value->id."'>'.$text . $value->name.'</option>";
                // }
                $this->dataRecusive($parent_id,$value->id,$text.'-- ');
            }
        }

        return $this->html;
    }
}
