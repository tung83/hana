<?php

function dd() {
    $whitelist = array( '127.0.0.1', '::1' );

    if ( !in_array( $_SERVER[ 'REMOTE_ADDR' ], $whitelist ) )
        return;

    foreach ( func_get_args() as $arg )
        var_dump( $arg );

    die;
}

function fdate( $date, $format = "d/m/Y" ) {
    if ( is_string( $date ) )
        $date = strtotime( $date );
    return date( $format, $date );
}

function fmoney( $num, $withUnit = true ) {
    global $lang;

    if (!is_numeric($num))
        return "";

    if ( $lang === 'en' )
        return number_format($num, 2, '.', ',') . ( $withUnit ? '<sup>USD</sup>' : '' );

    return number_format($num, 0, ',', '.') . ( $withUnit ? '<sup>VNĐ</sup>' : '' );
}

function fnum( $num ) {
    global $lang;

    if ( $lang == 'en' )
        return number_format( $num, 0, '.', ',');

    return number_format( $num, 0, ',', '.' );
}

function checked( $condition ) {
    return $condition ? ' checked="checked"' : '';
}

function selected( $condition ) {
    return $condition ? ' selected="selected"' : '';
}

function submitted( $name ) {
    return postt( $name );
}

function gett( $key ) {
    return isset( $_GET[$key] ) ? $_GET[$key] : FALSE;
}

function postt( $key ) {
    return isset( $_POST[$key] ) ? $_POST[$key] : FALSE;
}

function sessionn( $key, $value = '' ) {
    // getter
    if (func_num_args() < 2)
        return isset( $_SESSION[$key] ) ? $_SESSION[$key] : FALSE;
    // setter
    else
        $_SESSION[$key] = $value;
}

function redirect( $url ) {
    header("Location: {$url}", true);
    die;
}

function is_at( $slug ) {
    global $view;
    return $view == $slug;
}

function choose() {

    foreach ( func_get_args() as $value ) {
        if ( !empty( $value ) )
            return $value;
    }

    return false;
}

function vi() {
    return Lang::get() == 'vi';
}

// translate term
function t( $vi, $en = '' ) {
    return Lang::get() == 'vi' ? $vi : $en;
}

// translate query results
function tt( $rows ) {
    if ( is_array( $rows ) ) {
        foreach ( $rows as $key => $row ) {
            $rows[$key] = tt( $row );
        }
    } else {
        translateField( $rows, 'title' );
        translateField( $rows, 'sum' );
        translateField( $rows, 'content' );
        translateField( $rows, 'desc' );
        translateField( $rows, 'keyword' );
    }
    return $rows;
}

function translateField( $row, $field ) {

    switch (Lang::get()) {
        case 'en':
            $targetField = 'e' . ucfirst( $field );
            break;
        case 'vi':
        default:
            $targetField = $field;
            break;
    }

    if ( !empty( $row ) ) {
        if ( property_exists( $row, $field ) && property_exists( $row, $targetField ) ) {
            $row->$field = $row->$targetField;
        }
    }
}

function close_html_tags( $html, $encoding = 'UTF-8' ) {
    $doc = new DOMDocument();

    // use this hack to force DOMDocument to be loaded as UTF-8
    $encoding = "<?xml encoding=\"$encoding\">";
    @$doc->loadHTML( $encoding . $html );

    $html = $doc->saveHTML();

    // remove <?xml encoding="UTF-8"> part
    $html = str_replace( $encoding, '', $html );

    // remove <!DOCTYPE
    // remove <html><body></body></html>
    $html = preg_replace('~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $html);

    return $html;
}

function post_to_insert( $table, $fields ) {

    $name = explode(',', $fields);
    $fields = array();
    $values = array();
    foreach ($name as $field) {
        $fields[] = "`${field}`";
        if (postt($field) == 'on')
            $values[] = "1";
        elseif ($field == 'dates')
            $values[] = "NOW()";
        else
            $values[] = "'" . postt($field) . "'";
    }

    $sql = "INSERT INTO ${table}(" . implode(',', $fields) . ") VALUES(" . implode(',', $values) . ")";
    return $sql;
}

function post_to_update( $table, $fields, $id ) {

    $name = explode(',', $fields);
    $values = array();
    foreach ($name as $field) {
        if (postt($field) == 'on')
            $values[] = "`" . $field . "`=1";
        elseif ($field == 'dates')
            $values[] = "`" . $field . "`=NOW()";
        else
            $values[] = "`" . $field . "`='" . postt($field) . "'";
    }

    $sql = "UPDATE ${table} SET " . implode(',', $values) . " WHERE id=${id}";
    return $sql;
}

function flash() {

    // retrieve and clear flash tuple
    if ( func_num_args() < 2 ) {
        $msgs = $_SESSION['flash'];

        if (!is_array($msgs))
            return array();

        unset($_SESSION['flash']);
        return $msgs;
    }

    // push new message
    if ( !isset($_SESSION['flash']) )
        $_SESSION['flash'] = array();

    $msg = new stdClass();
    $msg->type = func_get_arg(0);
    $msg->msg = func_get_arg(1);

    $_SESSION['flash'][] = $msg;
}

function price( $value ) {
    return t( priceVi( $value ), priceEn( $value ) );
}

function priceVi( $value ) {
    $value = floatval( $value );
    $default = '<a href="' . url('contact-us') . '">Liên hệ</a>';
    return $value == 0 ? $default : number_format($value, 0, ',', '.') . '₫';
}

function priceEn( $value ) {
    $value = floatval( $value );
    $default = '<a href="' . url('contact-us') . '">Please contact us</a>';
    return $value == 0 ? $default : '$' . number_format($value, 2, '.', ',');
}

function status( $value ) {
    if (empty( $value ))
        return t("Còn hàng", "Available");

    return $value;
}

function safe_html( $obj ) {
    $array = (array) $obj;
    foreach ($array as $key => $value) {
        if ( is_string( $value ) )
            $obj->{$key} = htmlspecialchars( $value );
    }
    return $obj;
}

function escape_post( $fields ) {
    $name = explode(',', $fields);
    foreach ($name as $key) {
        if (postt($key)) {
            $_POST[$key] = mysql_real_escape_string(postt($key));
        }
    }
}

function uploaded( $name ) {
    if( ! file_exists($_FILES[$name]['tmp_name']) || ! is_uploaded_file($_FILES[$name]['tmp_name']) )
        return false;

    return $_FILES[$name];
}

function one_line_text( $text ) {
    $text = strip_tags( $text );
    $text = trim( $text );
    $text = preg_replace( "/\r|\n/", "", $text );
    return $text;
}

function get_attachment( $table_dot_field, $id ) {
    list( $table, $field ) = explode( '.', $table_dot_field );
    $row = DB::select_one("SELECT * FROM {$table} WHERE id={$id}");
    if (empty($row)) return '';
    return dirname(__FILE__) . '/..' . PATH_UPLOAD . $row->$field;
}

function delete_attachment( $table_dot_field, $id ) {
    @unlink( get_attachment( $table_dot_field, $id ) );
}


function slug( $str, $options = array() ) {
    // Make sure string is in UTF-8 and strip invalid UTF-8 characters

    $str = vn_str_filter( $str );
    $str = mb_convert_encoding( (string)$str, 'UTF-8', mb_list_encodings() );
    $defaults = array(
        'delimiter' => '-',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => false,
    );
    // Merge options
    $options = array_merge( $defaults, $options );
    $char_map = array(
        // Latin
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
        'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
        'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
        'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
        'ß' => 'ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
        'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
        'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
        'ÿ' => 'y',

        // Latin symbols
        '©' => '(c)',

        // Greek
        'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
        'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
        'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
        'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
        'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
        'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
        'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
        'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',

        // Turkish
        'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
        'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',

        // Russian
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
        'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
        'я' => 'ya',

        // Ukrainian
        'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
        'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',

        // Czech
        'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
        'Ž' => 'Z',
        'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
        'ž' => 'z',

        // Polish
        'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
        'Ż' => 'Z',
        'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
        'ż' => 'z',

        // Latvian
        'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
        'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
        'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
        'š' => 's', 'ū' => 'u', 'ž' => 'z'
    );
    // Make custom replacements
    $str = preg_replace( array_keys( $options[ 'replacements' ] ), $options[ 'replacements' ], $str );
    // Transliterate characters to ASCII
    if ( $options[ 'transliterate' ] ) {
        $str = str_replace( array_keys( $char_map ), $char_map, $str );
    }
    // Replace non-alphanumeric characters with our delimiter
    $str = preg_replace( '/[^\p{L}\p{Nd}]+/u', $options[ 'delimiter' ], $str );
    // Remove duplicate delimiters
    $str = preg_replace( '/(' . preg_quote( $options[ 'delimiter' ], '/' ) . '){2,}/', '$1', $str );
    // Truncate slug to max. characters
    $str = mb_substr( $str, 0, ( $options[ 'limit' ] ? $options[ 'limit' ] : mb_strlen( $str, 'UTF-8' ) ), 'UTF-8' );
    // Remove delimiter from ends
    $str = trim( $str, $options[ 'delimiter' ] );
    return $options[ 'lowercase' ] ? mb_strtolower( $str, 'UTF-8' ) : $str;
}

function vn_str_filter( $str ) {

    $unicode = array(
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
    );

    foreach ( $unicode as $nonUnicode => $uni ) {
        $str = preg_replace( "/($uni)/i", $nonUnicode, $str );
    }

    return $str;
}

function validImg( $fileName ) {
    if ( $fileName == "" ) {
        return false;
    } else {
        $reg = "png,jpeg,jpg,gif";
        $tail = getFileExt( $fileName );
        if ( strstr( $reg, $tail ) != NULL ) return true;
        else return false;
    }
}

function getFileExt( $file ) {
    $arr = explode( '.', $file );
    return strtolower( $arr[ ( sizeof( $arr ) - 1 ) ] );
}

function json($obj) {
    header('Content-Type: application/json');
    echo json_encode($obj);
    die;
}

function handleUploaded( $name, $width = PRODUCT_THUMB_WIDTH, $height = PRODUCT_THUMB_HEIGHT, $crop = TRUE ) {

    $file = uploaded( $name );
    $hasFile = $file && validImg( $file['name'] );

    if ( $hasFile ) {
        $name = time() . $file['name'];
        $path = dirname(__FILE__) . '/..' . PATH_UPLOAD . $name;

        move_uploaded_file( $file['tmp_name'], $path );

        $image = new ImageResize( $path );
        $image->quality_jpg = 95;
        $image->quality_png = 9;

        if ($crop)
            $image->crop( $width, $height );
        else
            $img->resizeToBestFit($width, $height);

        $image->save( $path, NULL );

        return $name;
    }

    return FALSE;
}
