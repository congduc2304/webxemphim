<?php
/*
 * phpcount.php Ver.1.1- An "anoymizing" hit counter.
 * Copyright (C) 2013  Taylor Hornby
 * 
 * Chương trình này là phần mềm miễn phí: bạn có thể phân phối lại và / hoặc sửa đổi
 * nó theo các điều khoản của Giấy phép Công cộng GNU được xuất bản bởi
 * Tổ chức Phần mềm Miễn phí, phiên bản 3 của Giấy phép, hoặc
 * (theo tùy chọn của bạn) bất kỳ phiên bản nào mới hơn.
 * 
 * Chương trình này được phân phối với hy vọng rằng nó sẽ hữu ích,
* nhưng KHÔNG CÓ BẤT KỲ BẢO HÀNH NÀO; mà không có bảo hành ngụ ý
* KHẢ NĂNG LAO ĐỘNG hoặc PHÙ HỢP VỚI MỤC ĐÍCH CỤ THỂ. Xem
 * Giấy phép Công cộng GNU để biết thêm chi tiết.
 * 
 * Bạn nên nhận được một bản sao của Giấy phép Công cộng GNU
* cùng với chương trình này. Nếu không, hãy xem <http://www.gnu.org/licenses/>.
* /

/ *
 * Lớp PHP này cung cấp một bộ đếm lượt truy cập có thể theo dõi các lượt truy cập duy nhất
* mà không cần ghi lại địa chỉ IP của khách truy cập trong cơ sở dữ liệu. Nó làm như vậy bởi
 * ghi lại băm của địa chỉ IP và tên trang.
 *
 * Bằng cách băm địa chỉ IP với tên trang là muối, bạn ngăn mình khỏi
* có thể theo dõi người dùng khi họ điều hướng trang web của bạn. Bạn cũng ngăn chặn
 * bạn không thể khôi phục địa chỉ IP của bất kỳ ai mà không bị ép buộc
 * thông qua tất cả các khối địa chỉ IP được chỉ định được sử dụng bởi internet.
 *
 * Liên hệ: havoc AT defuse.ca
 * WWW: https://defuse.ca/
 *
 * SỬ DỤNG:
 * Trong tập lệnh của bạn, sử dụng reqire_once () để nhập tập lệnh này, sau đó gọi
* các hàm như PHPCount :: AddHit (...); Xem từng chức năng để được trợ giúp.
 *
 * LƯU Ý: Bạn phải đặt thông tin xác thực cơ sở dữ liệu trong phương thức InitDB.
 */

class PHPCount
{
   /*
    * Xác định thời gian ghi nhớ một lần truy cập là bao nhiêu giây. Điều này ngăn cản
    * cơ sở dữ liệu từ không ngừng tăng kích thước. Ba mươi ngày (mặc định)
    * hoạt động tốt. Nếu ai đó truy cập một trang và quay lại sau một tháng, nó sẽ
    * được tính là một lần truy cập duy nhất khác.
    */
    const HIT_OLD_AFTER_SECONDS = 2592000; // mặc định: 30 days.

    // Không tính lần truy cập từ rô bốt tìm kiếm và trình thu thập thông tin.
    const IGNORE_SEARCH_BOTS = true;

    // Không tính lần truy cập nếu trình duyệt gửi tiêu đề DNT: 1.
    const HONOR_DO_NOT_TRACK = false;

    private static $IP_IGNORE_LIST = array(
        '127.0.0.1',
    );

    private static $DB = false;

    private static function InitDB($mysql_host,$mysql_user,$mysql_pass,$mysql_dbname)
    {
        if(self::$DB)
            return;

        try
        {
            // TODO: Đặt thông tin đăng nhập cơ sở dữ liệu.
            self::$DB = new PDO(
                'mysql:host='.$mysql_host.';dbname='.$mysql_dbname.'',
                ''.$mysql_user.'', // Username
                ''.$mysql_pass.'', // Password
                array(PDO::ATTR_PERSISTENT => true)
            );
        }
        catch(Exception $e)
        {
            die('Failed to connect to phpcount database');
        }
    }

    public static function setDBAdapter($db)
    {
        self::$DB = $db;
        return $db;
    }

    /*
     * Thêm lần truy cập vào một trang được chỉ định bởi chuỗi $ pageID duy nhất.
     */
    public static function AddHit($pageID,$mysql_host,$mysql_user,$mysql_pass,$mysql_dbname)
    {
        if(self::IGNORE_SEARCH_BOTS && self::IsSearchBot())
            return false;
        if(in_array($_SERVER['REMOTE_ADDR'], self::$IP_IGNORE_LIST))
            return false;
        if(
            self::HONOR_DO_NOT_TRACK &&
            isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT'] == "1"
        ) {
            return false;
        }

        self::InitDB($mysql_host,$mysql_user,$mysql_pass,$mysql_dbname);

        self::Cleanup();
        if(self::UniqueHit($pageID))
        {
            self::CountHit($pageID, true);
            self::LogHit($pageID);
        }
        self::CountHit($pageID, false);

        return true;
    }
    
    /*
     * Trả về (int) số lần truy cập mà một trang có
     * $ pageID - mã định danh trang
     * $ unique - true nếu bạn muốn số lần truy cập duy nhất
     */
    public static function GetHits($pageID, $unique = false, $mysql_host,$mysql_user,$mysql_pass,$mysql_dbname)
    {
        self::InitDB($mysql_host,$mysql_user,$mysql_pass,$mysql_dbname);

        $q = self::$DB->prepare(
            'SELECT hitcount FROM hits
             WHERE pageid = :pageid AND isunique = :isunique'
        );
        $q->bindParam(':pageid', $pageID);
        $q->bindParam(':isunique', $unique);
        $q->execute();

        if(($res = $q->fetch()) !== FALSE)
        {
            return (int)$res['hitcount'];
        }
        else
        {
            //die("Missing hit count from database!");
            return 0;
        }
    }
    
    /*
     * Trả về tổng số lượt truy cập cho toàn bộ trang web
     * Khi $ duy nhất là FALSE, nó trả về tổng của tất cả số lần truy cập không phải là duy nhất
     * cho mọi trang. Khi $ duy nhất là TRUE, nó trả về tổng của tất cả
     * số lần truy cập cho mỗi trang, vì vậy giá trị được trả về KHÔNG PHẢI là 
     * số lần truy cập duy nhất trên toàn trang web, nó là tổng số lần truy cập duy nhất của mỗi trang
     * số lần nhấn.
     */
    public static function GetTotalHits($unique = false,$mysql_host,$mysql_user,$mysql_pass,$mysql_dbname)
    {
        self::InitDB($mysql_host,$mysql_user,$mysql_pass,$mysql_dbname);

        $q = self::$DB->prepare(
            'SELECT hitcount FROM hits WHERE isunique = :isunique'
        );
        $q->bindParam(':isunique', $unique);
        $q->execute();
        $rows = $q->fetchAll();

        $total = 0;
        foreach($rows as $row)
        {
            $total += (int)$row['hitcount'];
        }
        return $total;
    }
    
    /*====================== PRIVATE METHODS =============================*/
    
    private static function IsSearchBot()
    {
        // Of course, this is not perfect, but it at least catches the major
        // search engines that index most often.
        $keywords = array(
            'bot',
            'spider',
            'spyder',
            'crawlwer',
            'walker',
            'search',
            'yahoo',
            'holmes',
            'htdig',
            'archive',
            'tineye',
            'yacy',
            'yeti',
        );

        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

        foreach($keywords as $keyword) 
        {
            if(strpos($agent, $keyword) !== false)
                return true;
        }

        return false;
    }

    private static function UniqueHit($pageID)
    {
        $ids_hash = self::IDHash($pageID);

        $q = self::$DB->prepare(
            'SELECT `time` FROM nodupes WHERE ids_hash = :ids_hash'
        );
        $q->bindParam(':ids_hash', $ids_hash);
        $q->execute();

        if(($res = $q->fetch()) !== false)
        {
            if($res['time'] > time() - self::HIT_OLD_AFTER_SECONDS)
                return false;
            else
                return true;
        }
        else
        {
            return true;
        }
    }
    
    private static function LogHit($pageID)
    {
        $ids_hash = self::IDHash($pageID);

        $q = self::$DB->prepare(
            'SELECT `time` FROM nodupes WHERE ids_hash = :ids_hash'
        );
        $q->bindParam(':ids_hash', $ids_hash);
        $q->execute();

        $curTime = time();

        if(($res = $q->fetch()) !== false)
        {
            $s = self::$DB->prepare(
                'UPDATE nodupes SET `time` = :time WHERE ids_hash = :ids_hash'
            );
            $s->bindParam(':time', $curTime);
            $s->bindParam(':ids_hash', $ids_hash);
            $s->execute();
        }
        else
        {
            $s = self::$DB->prepare(
                'INSERT INTO nodupes (ids_hash, `time`)
                 VALUES( :ids_hash, :time )'
            );
            $s->bindParam(':time', $curTime);
            $s->bindParam(':ids_hash', $ids_hash);
            $s->execute();
        }
    }
    
    private static function CountHit($pageID, $unique)
    {
        $q = self::$DB->prepare(
            'INSERT INTO hits (pageid, isunique, hitcount) VALUES (:pageid, :isunique, 1) ' .
            'ON DUPLICATE KEY UPDATE hitcount = hitcount + 1'
        );
        $q->bindParam(':pageid', $pageID);
        $unique = $unique ? '1' : '0';
        $q->bindParam(':isunique', $unique);
        $q->execute();
    }
    
    private static function IDHash($pageID)
    {
        $visitorID = $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'];
        return hash("SHA256", $pageID . $visitorID);
    }

    private static function Cleanup()
    {
        $last_interval = time() - self::HIT_OLD_AFTER_SECONDS;

        $q = self::$DB->prepare(
            'DELETE FROM nodupes WHERE `time` < :time'
        );
        $q->bindParam(':time', $last_interval);
        $q->execute();
    }
}
