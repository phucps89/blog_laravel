<?php
/**
 * Created by PhpStorm.
 * User: phuctran
 * Date: 06/07/2016
 * Time: 11:16
 */

namespace App\Libraries;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class Helpers
{
    public static function getTimeZone()
    {
        $request = self::getRequestInstance();
        $timezone = $request->get('timezone');

        return $timezone;
    }

    public static function checkMXRecord($email)
    {
        $domain = explode('@', $email);
        if (isset($domain[1])) {
            $mx = [];
            $result = getmxrr($domain[1], $mx);

            return $result && count($mx);
        }

        return false;
    }

    /**
     * Get value for the object or array with default value
     *
     * @author Binh pham
     *
     * @param object|array $object Object to get value
     * @param string $value key value
     * @param null $defaultValue default value if object's key not exist
     * @param callable $callback function callback
     *
     * @return object|array|string|null value of key in the object
     */
    public static function get($object, $value, $defaultValue = null, $callback = null)
    {
        $value = explode('.', $value);

        $dataReturn = self::getRecursive($object, $value, $defaultValue);

        if (is_callable($callback) && $dataReturn !== null && $dataReturn != '') {
            $callback($dataReturn);
        }

        return $dataReturn;

    }

    /**
     * Get value for the object or array with default value
     *
     * @author Binh pham
     *
     * @param object|array $object Object to get value
     * @param string $value key value
     * @param null $defaultValue default value if object's key not exist
     *
     * @return object|array|string|null value of key in the object
     */
    private static function getRecursive($object, $value, $defaultValue = null)
    {
        if (is_array($value)) {
            $tmpValue = $object;
            for ($i = 0, $len = count($value); $i < $len; $i++) {
                $tmpValue = self::getRecursive($tmpValue, $value[$i], $defaultValue);
            }

            return $tmpValue;
        } else {
            if (!isset($object)) {
                return $defaultValue;
            } elseif (is_array($object)) {
                return isset($object[$value]) ?
                    $object[$value] : $defaultValue;
            } elseif (is_object($object)) {
                return isset($object->$value) ?
                    $object->$value : $defaultValue;
            }
        }
    }

    /**
     * @param $validator
     * @param $string
     *
     * @return array
     */
    public static function getErrorMessage($validator, $string=null)
    {
        $result = $tmp = [];

        $errors = $validator->getMessageBag()->toArray();

        foreach ($errors as $field => $error) {
            $tmp[$field] = implode(',', $error);
        }

        if ($string) {
            $result[$string] = $tmp;
        } else {
            $result = $tmp;
        }

        return $result;
    }

    public static function changeTimeZone($dateString, $timeZoneSource = null, $timeZoneTarget = null)
    {
        if (empty($timeZoneSource)) {
            $timeZoneSource = date_default_timezone_get();
        }
        if (empty($timeZoneTarget)) {
            $timeZoneTarget = date_default_timezone_get();
        }

        $dt = new \DateTime($dateString, new \DateTimeZone($timeZoneSource));
        $dt->setTimezone(new \DateTimeZone($timeZoneTarget));

        return $dt;
    }

    public static function convertDateToDefaultTimeZone(string $dateTime, \DateTimeZone $timeZone) : \DateTime
    {
        return self::convertDateTimeZone($dateTime, $timeZone, new \DateTimeZone(date_default_timezone_get()));
    }

    public static function convertDateTimeZone(string $dateTime, \DateTimeZone $from, \DateTimeZone $to) : \DateTime
    {
        $date = new \DateTime($dateTime, $from);
        $date->setTimezone($to);

        return $date;
    }
    public static function getItemPerPage()
    {
        $request = self::getRequestInstance();
        $itemPerPage = $request->get('length', config('repository.pagination.limit'));

        return $itemPerPage;
    }

    public static function cropImg($imgPath, $width, $height = null, $prefix = 'resize')
    {
        $info = pathinfo($imgPath);
        $newImg = dirname($imgPath) . '/' . $info['filename'] . '_' . $prefix . '.' . $info['extension'];

        $img = Image::make($imgPath);

        $img->resize($width, $height,  function ($constraint) {
            $constraint->aspectRatio();
        })
        ->save($newImg, 60);

        return $newImg;
    }

    /**
     * map search field
     * @param Builder $query
     * @param array $fieldsSearch, format [KEY => [TABLE_NAME,FIELD,CONDITION],...],
     *  with KEY : key using for search request,
     *  with TABLE_NAME : table name,
     *  with FIELD : field name,
     *  with CONDITION : search condition, acept 2 values "=" and "LIKE", default "=",
     * @return Builder
     */
    public static function searchFieldsMapping($query,array $fieldsSearch, $request=[])
    {
        $request = $request ? $request : app('request')->all();

        if( !empty($fieldsSearch) )
        {
            foreach( $fieldsSearch as $searchKey=>$fieldSearch )
            {
                if( !empty(array_get($request,$searchKey,null)) )
                {
                    $searchValue = array_get($request,$searchKey);
                    $condition = null;
                    $alias = null;
                    $field = null;
                    $countSearch = count($fieldSearch);
                    if( !in_array($countSearch,[2,3]) )
                    {
                        continue;
                    }

                    if( $countSearch == 3 )
                    {
                        $condition = $fieldSearch[2];
                        $field = $fieldSearch[1];
                        $alias = $fieldSearch[0];
                    }
                    else
                    {
                        $condition = '=';
                        $field = $fieldSearch[1];
                        $alias = $fieldSearch[0];
                    }
                    $condition = strtoupper($condition);
                    if( !in_array(strtoupper($condition),['=','LIKE','IN','<','>','<=','=>','>=','=<','!=','<>']) )
                    {
                        continue;
                    }

                    if( $condition == 'IN' )
                    {
                        $searchValue = explode(',',$searchValue);
                        $query->whereIn($alias.'.'.$field,$searchValue);
                    }
                    else
                    {
                        if( $condition == 'LIKE' )
                        {
                            $searchValue = '%'.trim($searchValue).'%';
                        }

                        $query->where($alias.'.'.$field,$condition,$searchValue);
                    }
                }
            }
        }

        return $query;
    }

    /**
     * map filter field
     * @param Builder $query
     * @param array $fieldsSearch, format [KEY => [TABLE_NAME,FIELD],...],
     *  with KEY : key using for search request,
     *  with TABLE_NAME : table name
     *  with FIELD : field name
     * @return Builder
     */
    public static function sortFieldsMapping($query,array $fieldsSearch)
    {
        $request = app('request');
        $order = $request->get('order',null);

        if( empty($order) )
        {
            return $query;
        }

        $orderField = $order;

        if( !empty($fieldsSearch) )
        {
            foreach( $fieldsSearch as $searchKey=>$fieldSearch )
            {
                if( $searchKey == $orderField )
                {
                    $orderType = $request->get('sort','ASC');
                    $alias = null;
                    $field = null;
                    $countSearch = count($fieldSearch);
                    if( $countSearch != 2 )
                    {
                        continue;
                    }

                    $field = $fieldSearch[1];
                    $alias = $fieldSearch[0];

                    $orderType = strtoupper($orderType);
                    if( !in_array($orderType,['ASC','DESC']) )
                    {
                        $orderType = 'ASC';
                    }

                    if( !empty($alias) )
                    {
                        $query->orderBy($alias.'.'.$field,$orderType);
                    }
                    else
                    {
                        $query->orderBy($field,$orderType);
                    }

                    break;
                }
            }
        }

        return $query;
    }

    /**
     * @param $query
     * @param $search
     * @param $fieldSearch
     *
     * @return mixed
     */
    static function addConditionToQuery($query, $search, $fieldSearch)
    {
        if (!$search) {
            return $query;
        }

        foreach ($search as $key => $val) {
            if (!isset($fieldSearch[$key])) {
                continue;
            }
            $field = $fieldSearch[$key]['field'];
            switch ($fieldSearch[$key]['type']) {
                case 'string':
                    $compare = $fieldSearch[$key]['compare'] ?? 'like';

                    if(strtolower(trim($compare)) == 'like') {
                        $val = '%' . $val . '%';
                    }
                    $query->where(DB::raw($fieldSearch[$key]['field']),$compare, $val);

                    break;
                case 'date':

                    $val = str_replace('"', '', $val);
                    $dateFormat = date('Y-m-d', strtotime($val));
                    $query->where(
                        DB::raw('DATE(' . $field . ')'), $fieldSearch[$key]['compare'], $dateFormat);
                    break;
                case 'array':
                    $delimiter = $fieldSearch[$key]['delimiter'] ?? ",";
                    $val = explode($delimiter, $val);
                    $query->whereIn($field, $val);
                    break;
                default:
                    $query->where($fieldSearch[$key]['field'], $fieldSearch[$key]['compare'], $val);
                    break;
            }
        }

        return $query;
    }


    public static function setAuthToObject(array $params, $field = 'created_by')
    {
        $user = self::getAuth();
        $params[$field] = $user ? $user->id : null;

        return $params;
    }

    public static function setUpdatedBy(array $params, $field = 'updated_by')
    {
        $user = self::getAuth();
        $params[$field] = $user ? $user->id : null;

        return $params;
    }

    /**
     * @param $string
     *
     * @return mixed
     */
    public static function alias($string)
    {
        $string = self::convertVNtoUnicode($string);
        $string = str_replace(' ', '_', $string);
        $charFilter = [
            '*',
            '@',
            '+',
            '®',
            '(',
            ')',
            '{',
            '}',
            '$',
            '!',
            '^',
            '%',
            '#',
            '/',
            '|',
            '\\',
            ',',
            ';',
            '?',
            '$',
            '™',
            '-',
            ':',
            ';',
            '<',
            '>',
            '~',
            "'",
            '"',
            '&',
            '¿',
            'ñ',
            'Ñ',
            '    ¡',
            'ª',
            'º',
            '€',
            '[',
            ']',
            '`',
            '~',
            '=',
            '^',
        ];
        foreach ($charFilter as $c) {
            $string = str_replace($c, '', $string);
        }

        return $string;
    }

    /**
     * @param $string
     *
     * @return mixed
     */
    public static function convertVNtoUnicode($string)
    {
        $unicode = [
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        ];
        foreach ($unicode as $nonUnicode => $uni) {
            $string = preg_replace("/($uni)/i", $nonUnicode, $string);
        }

        return $string;
    }

    /**
     * Get real IP from client
     *
     * @return string
     */
    public static function getRealClientIP()
    {
        $headers = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'HTTP_VIA',
            'HTTP_X_COMING_FROM',
            'HTTP_COMING_FROM',
            'HTTP_CLIENT_IP'
        ];

        foreach ($headers as $header) {
            if (isset ($_SERVER [$header])) {
                //Check server
                if (($pos = strpos($_SERVER [$header], ',')) != false) {
                    $ip = substr($_SERVER [$header], 0, $pos);//True
                }
                else {
                    $ip = $_SERVER [$header]; //False
                }
                $ipnumber = ip2long($ip);
                if ($ipnumber !== -1 && $ipnumber !== false && (long2ip($ipnumber) === $ip)) {
                    if (($ipnumber - 184549375) && // Not in 10.0.0.0/8
                        ($ipnumber - 1407188993) && // Not in 172.16.0.0/12
                        ($ipnumber - 1062666241)
                    ) // Not in 192.168.0.0/16
                        if (($pos = strpos($_SERVER [$header], ',')) != false) {
                            $ip = substr($_SERVER [$header], 0, $pos);
                        }
                        else {
                            $ip = $_SERVER [$header];
                        }
                    return $ip;
                }
            }

        }
        return $_SERVER ['REMOTE_ADDR'];
    }

    public static function runningInConsole() : bool
    {
        return app()->runningInConsole();
    }

    /**
     * @return User
     */
    public static function getAuth()
    {
        //return User::find(1);

        return Auth::user();
    }

    public static function trimArray($input)
    {
        return array_map('trim', $input);
    }

    public static function isAbsolutePath($file)
    {
        return strspn($file, '/\\', 0, 1)
            || (strlen($file) > 3 && ctype_alpha($file[0])
                && substr($file, 1, 1) === ':'
                && strspn($file, '/\\', 2, 1)
            )
            || null !== parse_url($file, PHP_URL_SCHEME)
            ;
    }

    public static function makePath($path)
    {
        $dir = pathinfo($path , PATHINFO_DIRNAME);

        if( is_dir($dir) )
        {
            return true;
        }
        else
        {
            if( self::makePath($dir) )
            {
                if( mkdir($dir) )
                {
                    chmod($dir , 0777);
                    return true;
                }
            }
        }

        return false;
    }

    public static function replaceExtensionPath($filename, $new_extension) {
        $info = pathinfo($filename);
        return ($info['dirname'] ? $info['dirname'] . DIRECTORY_SEPARATOR : '')
            . $info['filename']
            . '.'
            . $new_extension;
    }

    public static function formatPaginationDataTable(LengthAwarePaginator $paginator){
        return [
            'recordsTotal' => $paginator->total(),
            'recordsFiltered' => $paginator->total(),
            'data' => $paginator->items(),
        ];
    }

    public static function formatMappingDatatable($arrayKey){
        $result = [];
        foreach ($arrayKey as $key => $item) {
            $result[] = [
                'targets' => $key,
                'data' => $item,
            ];
        }
        return json_encode($result);
    }

    /**
     * @param Request|null $request
     * @return \Illuminate\Foundation\Application|Request
     */
    public static function mergePageByDataTable(Request $request = null)
    {
        if (empty($request)) {
            $request = app('request');
        }
        if ($request->get('ajax', 0) == 1) {
            $page = $request->get('start') / $request->get('length') + 1;
            $request->merge([
                'page' => $page
            ]);
        }
        return $request;
    }

    /**
     * @return Request
     */
    public static function getRequestInstance(){
        return app('request');
    }

    /**
     * @param null $disk
     * @return FilesystemAdapter
     */
    public static function getStorage($disk = null){
        if(empty($disk)){
            return \Storage::cloud();
        }

        return \Storage::disk($disk);
    }

    public static function getGoogleDriveFile(){

    }
}
