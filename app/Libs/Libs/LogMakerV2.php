<?php

namespace App\Libs\Libs;

use App\WevelopeLibs\Helper\DateFormat;

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
class LogMakerV2
{
    private $user;
    private $header;
    private $detail;
    private $ignoreIndexName = [];

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->clearDetail('detail');
    }

    public function setHeader($original, $modified)
    {
        $this->header = [
            'origin' => $original,
            'modified' => $modified
        ];
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function addDetailAsArray($key, $original, $modified)
    {
        if(!empty($original))
            foreach($original as $x)
                $this->addDetail($key, $x, null);

        if(!empty($modified))
            foreach($modified as $x)
                $this->addDetail($key, null, $x);
    }

    public function addDetail($key, $original, $modified)
    {
        if(!empty($original))
            $this->detail[$key]['origin'][] = $original;

        if(!empty($modified))
            $this->detail[$key]['modified'][] = $modified;
    }

    public function getDetail($key)
    {
        return $this->detail[$key];
    }

    public function clearDetail($key)
    {
        $this->detail[$key] = [
            'origin' => [],
            'modified' => []
        ];
    }

    public function addDetailByKey($key, $details)
    {
        $this->detail[$key] = $details;
    }

    public function getOnUpdatedLog()
    {
        $modifiedFlag = false;

        $headerLines = [];
        $lines = [];
        $detailLines = [];

        // Craete header lines, who is modified
        $headerLines[] = '<span>Dimodifikasi: <strong>' . $this->user->name . '</strong> ';

        // Log Header
        $originalData = $this->header['origin'];
        $changedData = $this->header['modified'];

        $temp = self::compareArray($originalData, $changedData, $this->ignoreIndexName);
        if(!empty($temp)) {
            $modifiedFlag = true;

            $filteredOriginal = $temp['original'];
            $modified = $temp['modified'];

            $lines[0] = 'Dari: <strong>' . $this->formatted($filteredOriginal) . '</strong>, ' . 'Ke: <strong>' . $this->formatted($modified) . '</strong></span>';
        }

        // Log Detail
        // Key would be used when multiples detail is needed
        $key = 'detail';
        $detailLines = $this->getDetailMessages($key);

        if(!empty($detailLines)) {
            array_unshift($detailLines, 'Detail:');
        }

        $temp = null;

        if($modifiedFlag) {
            $temp = implode('<br>', array_merge($headerLines, $lines));
        }

        if(!empty($detailLines)) {
            $allLines = array_merge($headerLines, $lines, $detailLines);

            $temp = implode('<br>', $allLines);
        }

        return $temp;
    }

    /*
     * Generate lines of message of detail changes
     *
     * @params string index name of detail key
     *
     * @return array of messages
     */
    public function getDetailMessages($key)
    {
        $detail = $this->detail[$key];
        if(!empty($detail) && count($detail) > 0) {
            // Sorted
            $originalDetails = collect($detail['origin']);
            $changedDetails = collect($detail['modified']);

            $list = [];
            foreach($originalDetails as $x) {
                $origin = $x;
                $modified = $changedDetails->firstWhere('id', $origin['id']);

                $list[] = [
                    'origin' => $origin,
                    'modified' => $modified
                ];
            }


            foreach($changedDetails->whereNotIn('id', $originalDetails->pluck('id')) as $x) {
                $list[] = [
                    'origin' => null,
                    'modified' => $x
                ];
            }

            $counter = 1;
            $detailLines = [];
            foreach($list as $x) {
                $origin = $x['origin'];
                $modified = $x['modified'];

                // Updated
                if(isset($origin) && isset($modified)) {
                    $temp = self::compareArray($origin, $modified, $this->ignoreIndexName);
                    if(!empty($temp) && (!empty($temp['original']) || !empty($temp['modified']))) {
                        $originData = $temp['original'];
                        $modifiedData = $temp['modified'];

                        $detailLines[] = $counter++ . '. Modifikasi, dari: <strong>' . $this->formatted($originData) . '</strong>, Ke: <strong>' . $this->formatted($modifiedData) . '</strong>';
                    }
                } elseif(isset($origin)) { // Deleted
                    $detailLines[] = $counter++ . '. Hapus, <strong>' . $this->formatted($origin) . '</strong>';
                } elseif(isset($modified)) { // Add new item(Created)
                    $detailLines[] = $counter++ . '. Tambah, <strong>' . $this->formatted($modified) . '</strong>';
                } else {
                    var_dump($origin, $modified);
                    die('Should not goes here');
                }
            }
        }

        return $detailLines;
    }

    /*
     * Cast all DateTime object to string with $format
     *
     * @params string Date format
     */
    public function castDateTime($format)
    {
        foreach(['origin', 'modified'] as $x) {
            // For header
            $temp = collect($this->header[$x]);
            // Parse DateTime if any into string
            $temp = $temp->map(function($val) use ($format) {
                if($val instanceof \DateTime)
                    $val = $val->format($format);

                return $val;
            });
            $this->header[$x] = $temp->toArray();

            // For detail
            // Parse DateTime if any into string
            $detail = collect($this->detail['detail'][$x]);
            $detail = $detail->map(function($y) use ($format) {
                $temp = $y;
                foreach($temp as $key => $val) {
                    if($temp[$key] instanceof \DateTime)
                        $temp[$key] = $val->format($format);
                }

                return $temp;
            });
            $this->detail['detail'][$x] = $detail->toArray();
        }
    }

    /* Update value of array by index
     *
     * @param string | int Index name
     * @param array [ 'old_value' => xxx, 'new_value' => yyy]
     */
    public function mapValueByIndex($index, $mapper)
    {
        foreach(['origin', 'modified'] as $x) {
            // For header
            // Change array index
            $this->header[$x] = self::updateArrayValue($this->header[$x], $index, $mapper);

            // For detail
            for($i = 0; $i < count($this->detail['detail'][$x]); $i++) {
                $this->detail['detail'][$x][$i] = self::updateArrayValue($this->detail['detail'][$x][$i], $index, $mapper);
            }
        }
    }

    // Rename array index
    public function mapArrayIndex($oldIndex, $newIndex)
    {
        foreach(['origin', 'modified'] as $x) {
            // For header
            // Change array index
            $this->header[$x] = self::renameArrayIndex($this->header[$x], $oldIndex, $newIndex);
        }

        $this->mapArrayDetailIndex($oldIndex, $newIndex);
    }

    public function mapArrayDetailIndex($oldIndex, $newIndex) {

        foreach(['origin', 'modified'] as $x) {

            // For detail
            for($i = 0; $i < count($this->detail['detail'][$x]); $i++) {
                $this->detail['detail'][$x][$i] = self::renameArrayIndex($this->detail['detail'][$x][$i], $oldIndex, $newIndex);
            }
        }
    }

    public function removeArrayIndex($arrayIndex)
    {
        $this->ignoreIndexName = $arrayIndex;

        /*
        foreach(['origin', 'modified'] as $x) {
            foreach($arrayIndex as $index) {
                if(array_key_exists($index, $this->header[$x]))
                    unset($this->header[$x][$index]);

                // For detail
                for($i = 0; $i < count($this->detail['detail'][$x]); $i++) {
                    if(array_key_exists($index, $this->detail['detail'][$x][$i]))
                        unset($this->detail['detail'][$x][$i][$index]);
                }
            }
        }
        */
    }

    public function formatted($arr)
    {
        return self::arrayToString($arr, $this->ignoreIndexName);
    }

    /*
     * Comparing two array(one dimension) and return modified value
     *
     * @params array
     * @params array
     *
     * @return array ['original' => [], 'modified' => []]
     */
    public static function compareArray($original, $modified, $ignoreArrayIndex = [])
    {
        foreach($ignoreArrayIndex as $x) {
            if(isset($original[$x]))
                unset($original[$x]);

            if(isset($modified[$x]))
                unset($modified[$x]);
        }

        $temp = null;
        if($original != $modified) {
            $temp = [];

            $modified = array_diff_assoc($modified, $original);
            $indexModified = array_keys($modified);
            $filteredOriginal = [];

            foreach ($indexModified as $x) {
                $filteredOriginal[$x] = $original[$x];
            }

            $temp['original'] = $filteredOriginal;
            $temp['modified'] = $modified;
        }

        return $temp;
    }

    /*
     * Parse array to string message
     *
     * @param array Array to be convert to string
     * @param array Name of array that would be ignore when parse to string
     *
     * @return String
     */
    public static function arrayToString($arr, $ignoreArrayIndex = [])
    {
        $list = [];
        foreach($arr as $key => $value) {
            // Parse datetime to default format
            if($value instanceof \DateTime) {
                $value = DateFormat::national($value);
            }

            // Ignore array index
            if(!in_array($key, $ignoreArrayIndex))
                $list[] = $key . '=' . $value;
        }

        return implode(', ', $list);
    }

    public static function renameArrayIndex($arr, $oldIndex, $newIndex)
    {
        $temp = [];
        foreach($arr as $key => $val) {
            if($key == $oldIndex)
                $temp[$newIndex] = $val;
            else
                $temp[$key] = $val;
        }

        return $temp;
    }

    /* Update value of array by index
     *
     * @param array target array to be mapped
     * @param string | int Index name
     * @param array [ 'old_value' => xxx, 'new_value' => yyy]
     *
     * @return new array with upated value
     */
    public static function updateArrayValue($arr, $index, $mapper)
    {
        $mapper = collect($mapper);
        if(array_key_exists($index, $arr)) {
            $found = $mapper->firstWhere('old_value', $arr[$index]);
            if(!empty($found))
                $arr[$index] = $found['new_value'];
        }

        return $arr;
    }
}
