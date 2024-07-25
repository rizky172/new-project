<?php

namespace App\Libs\Libs;

use App\User;

use App\PurchaseRequestDetail;
use App\PurchaseOrder;
use App\PurchaseOrderDetail;
use App\ItemSupplier;
use App\Category;
use App\Person;
use App\LogM;
use App\Item;
use App\Substance;
use Illuminate\Support\Facades\Log;

/**
 * Help to generate log message
 *
 * @author blackhat
 */
class LogMaker
{
    private $user;
    private $header;
    private $detail;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function setHeader($original, $modified)
    {
        $this->header = [
            'origin' => $original,
            'modified' => $modified
        ];
    }

    /*
    public function addDetail($original, $modified)
    {
        $this->detail[] = [
            'origin' => $original,
            'modified' => $modified
        ];
    }
    */

    public function addDetailByKey($key, $details)
    {
        $this->detail[$key] = $details;
    }

    public function getOnUpdatedLog()
    {
        $detail = collect($this->detail);

        $lines = [];
        $lines[] = '<span>Dimodifikasi: <strong>' . $this->user->name . '</strong></span>';

        $modifiedFlag = false;
        $detailChangedExistFlag = false;

        // Log Header
        $originalData = $this->header['origin'];
        $changedData = $this->header['modified'];

        if($originalData != $changedData) {
            $modifiedFlag = true;
            $modified = array_diff_assoc($changedData, $originalData);
            $indexModified = array_keys($modified);
            $filteredOriginal = [];

            foreach ($indexModified as $x) {
                $filteredOriginal[$x] = $originalData[$x];
            }

            $lines = [];
            $lines[] = '<span>Dimodifikasi: <strong>' . $this->user->name . '</strong>. ' . 'Dari: <strong>' . self::formatted($filteredOriginal) . '</strong>, ' . 'Ke: <strong>' . self::formatted($modified) . '</strong></span>';
        }

        // Log Detail
        if($this->detail) {
            if(count($this->detail) > 0) {

                $originalDetails = collect($this->detail['origin'])->sortBy('id')->toArray();
                $changedDetails = collect($this->detail['modified'])->sortBy('id')->toArray();

                $indexDetail = 1;

                $indexIncrement = 0;
                $countDetailCreation = null;
                $countDetailDeletion = null;

                foreach ($originalDetails as $y) {

                    if(array_key_exists($indexIncrement, $changedDetails)) {

                        $x = $changedDetails[$indexIncrement];

                        //updating
                        if($y != $x) {

                            $modifiedFlag = true;
                            if($detailChangedExistFlag == false) {
                                $lines[] = 'Detail:';
                            }

                            $modifiedDetail = array_diff_assoc($x, $y);
                            $indexModifiedDetail = array_keys($modifiedDetail);
                            $filteredOriginalDetail = [];

                            foreach ($indexModifiedDetail as $n) {
                                $filteredOriginalDetail[$n] = $y[$n];
                            }

                            $lines[] = $indexDetail . '. Modifikasi, dari: <strong>' . self::formatted($filteredOriginalDetail) . '</strong>, Ke: <strong>' . self::formatted($modifiedDetail) . '</strong>';

                            $detailChangedExistFlag = true;
                            $indexDetail++;
                        }
                    }

                    $indexIncrement++;
                }

                //Deleting
                if(count($originalDetails) > count($changedDetails)) {
                    $countDetailDeletion = count($originalDetails) - count($changedDetails);

                    $sliced = array_slice($originalDetails, -$countDetailDeletion, $countDetailDeletion, false);  

                    foreach($sliced as $y) {

                        $modifiedFlag = true;
                        if($detailChangedExistFlag == false) {
                            $lines[] = 'Detail:';
                        }

                        $lines[] = $indexDetail . '. Hapus, <strong>' . self::formatted($y) . '</strong>';

                        $detailChangedExistFlag = true;
                        $indexDetail++;
                    }
                } 
                //Creating
                elseif(count($changedDetails) > count($originalDetails)) {
                    $countDetailCreation = count($changedDetails) - count($originalDetails);

                    $sliced = array_slice($changedDetails, -$countDetailCreation, $countDetailCreation, false);  

                    foreach($sliced as $y) {

                        $modifiedFlag = true;
                        if($detailChangedExistFlag == false) {
                            $lines[] = 'Detail:';
                        }

                        $lines[] = $indexDetail . '. Tambah, <strong>' . self::formatted($y) . '</strong>';

                        $detailChangedExistFlag = true;
                        $indexDetail++;
                    }
                }
            }
        }

        return ($modifiedFlag ? implode('<br>', $lines) : '');
    }

    // Exclude field from log
    public function removeField($onField, $fields) 
    {   
        //on header works
        foreach($fields as $x) {
            foreach ($this->$onField as $key => $y) { 

                if($onField == 'header') {
                    unset($this->$onField[$key][$x]);   
                } else {
                    foreach($y as $keyChild => $child) {
                        unset($this->$onField[$key][$keyChild][$x]);   
                    }
                }
            }
        }
    }

    // Parse field date to \DateTime object
    public function parseDateField($onField, $fields) 
    {
        foreach($fields as $y) {
            foreach ($this->$onField as $key => $x) {
                if($onField == 'header') { 
                    if(array_key_exists($y, $this->$onField[$key])) {
                        if(!($this->$onField[$key][$y] instanceof \DateTime)) {
                            $this->$onField[$key][$y] = new \DateTime ($this->$onField[$key][$y]);
                        }
                        $this->$onField[$key][$y] = $this->$onField[$key][$y]->format('Y-m-d');
                    }
                } else {
                    foreach($x as $keyChild => $child) {
                        if(array_key_exists($y, $this->$onField[$key][$keyChild])) {
                            if(!($this->$onField[$key][$keyChild][$y] instanceof \DateTime)) {
                                $this->$onField[$key][$keyChild][$y] = new \DateTime ($this->$onField[$key][$keyChild][$y]);
                            }
                            $this->$onField[$key][$keyChild][$y] = $this->$onField[$key][$keyChild][$y]->format('Y-m-d');
                        }
                    }
                }
            }
        }
    }

    public static function formatted($arr)
    {
        $list = [];
        foreach($arr as $key => $value) {
            switch($key) {
                case 'status_id':
                    $status = Category::find($value);
                    $list['status'] = ($status ? $status->label : null);
                    break;

                case 'item_id':
                    $item = Item::find($value);
                    $list['item'] = ($item ? $item->name : null);
                    break;
               
                case 'unit_id':
                    $unit = explode("_-_", $value);
                    if (count($unit) > 1) {
                        $cat = Category::whereIn('id', $unit)->get()->pluck('label')->toArray();

                        $list['unit'] = ($cat ? implode(",", $cat) : null);
                    } else {
                        if ($value) {
                            $unit = Category::find($value);
                            $list['unit'] = ($unit ? $unit->label : null);
                        }
                    }
                    break;
                
                case 'is_additional':
                    $list['tambahan'] = ($value == 0 ? 'Tidak' : 'Ya');
                    break;
                
                case 'sales_id':
                case 'customer_id':
                case 'person_id':
                    $person = Person::find($value);

                    if($person) {
                        $output = sprintf('kode: %s / nama: %s',
                            $person->ref_no, $person->name);
                    } else {
                        $output = null;
                    }

                    if($key == 'person_id') {
                        $list['supplier'] = $output;
                    } elseif($key == 'customer_id') {
                        $list['customer'] = $output;
                    } elseif($key == 'sales_id') {
                        $list['sales'] = $output;
                    }
                   
                    break;

                case 'created':
                    $datetime = null;
                    if(!($value instanceof \DateTime)) {
                        $datetime = new \DateTime($value);
                    }

                    $list['created'] = $datetime->format('d-m-Y');
                    break;
                
                /* NPD */ 
                case 'dimension_length':
                    $list['panjang'] = $value;
                case 'dimension_width':
                    $list['lebar'] = $value;
                    break;
                case 'dimension_height':
                    $list['tinggi'] = $value;
                    break;
                case 'substance_id':
                    $substance = Substance::where('id', $value)->first();
                    $list['substansi'] = ($substance ? $substance->ref_no  : null);
                    break;

                case 'product_category_id':
                case 'box_category_id':
                case 'hardness_id':
                case 'color_id':
                case 'font_type_id':
                case 'font_color_id':
                case 'background_color_id':
                case 'printing_material_id':
                case 'printing_type_id':
                case 'item_category_id':

                    if(strpos($value, '_-_') !== false) {
                        $value = explode('_-_', $value);
                        $label = Category::whereIn('id', $value)->pluck('label')->toArray();
                        
                        if($label) {
                            if(is_array($label)) {
                                $label = implode(',', $label);
                            } else {
                                $label = $label;
                            }
                        }
                    } else {
                        $label = Category::find($value);
                        $label = ($label ? $label->label : null);
                    }
                    
                    if($key == 'item_category_id') {
                        $list['item kategori'] = $label;
                    } elseif($key == 'color_id') {
                        $list['warna'] = $label;
                    } elseif($key == 'hardness_id') {
                        $list['hardness'] = $label;
                    } elseif($key == 'item_category_id') {
                        $list['item kategori'] = $label;
                    } elseif($key == 'product_category_id') {
                        $list['produk kategori'] = $label;
                    } elseif($key == 'box_category_id') {
                        $list['jenis box'] = $label;
                    } elseif($key == 'font_type_id') {
                        $list['jenis font'] = $label;
                    } elseif($key == 'printing_material_id') {
                        $list['bahan'] = $label;
                    } elseif($key == 'font_color_id') {
                        $list['warna font'] = $label;
                    } elseif($key == 'background_color_id') {
                        $list['warna background'] = $label;
                    } elseif($key == 'printing_type_id') {
                        $list['jenis printing'] = $label;
                    }

                    break;
                /* END NPD */
                
                case 'due_date':
                    $datetime = null;
                    if(!($value instanceof \DateTime)) {
                        $datetime = new \DateTime($value);
                    }

                    $list['due_date'] = $datetime->format('d-m-Y');
                    break;

                default:
                    $list[$key] = $value;
                    break;
            }
        }

        return self::arrayToString($list);
    }

    public static function arrayToString($arr)
    {
        $list = [];
        foreach($arr as $key => $value) {
            $list[] = $key . '=' . $value;
        }

        return implode(', ', $list);
    }
}
