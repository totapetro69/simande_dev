  <?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

class Marketing extends REST_Controller {

    function __construct($config='rest')
    {
        // Construct the parent class
        parent::__construct($config);
        $this->load->model('Main_model');
        $this->load->helper("zetro");
    }
    /**
    * Generate result data from select query
    * @param string $tabel_name
    * @param array $param
    * @param string $method default 'get'
    * @param boolean $status default FALSE = not authorizes
    */
    function resultdata($tabel_name,$param='',$method='get',$status=FALSE){
        $this->Main_model->tabel_name($tabel_name);
        $this->Main_model->set_parameter($param);
        $result = $this->Main_model->response_result($method,$status);
        $this->response($result);
    }

    /**
     * [appointment_get description]
     * @return [type] [description]
     */
    public function appointment_get(){
        $param = array();$search='';
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("no_trans")){
            $param["NO_TRANS"]     = $this->get("no_trans");
        }
        if($this->get("nama_customer")){
            $param["NAMA_CUSTOMER"]     = $this->get("nama_customer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "NO_TRANS" => $this->get("keyword"),
                "NAMA_CUSTOMER" => $this->get("keyword")
                );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_APPOINTMENT",$param);
    }
    /**
     * [appointment_post description]
     * @return [type] [description]
     */
    public function appointment_post(){
        $param = array();

        $param["NO_TRANS"]      = $this->post('no_trans');
        $this->Main_model->data_sudahada($param,"TRANS_APPOINTMENT");
        $param["KD_DEALER"]  = $this->post('kd_dealer');
        $param["KD_CUSTOMER"]    = $this->post('kd_customer');
        $param["ALAMAT"]    = ($this->post('alamat'));
        $param["KD_PROPINSI"]    = $this->post('kd_propinsi');
        $param["NAMA_PROPINSI"]    = $this->post('nama_propinsi');
        $param["KD_KABUPATEN"]    = $this->post('kd_kabupaten');
        $param["NAMA_KABUPATEN"]    = $this->post('nama_kabupaten');
        $param["KD_KECAMATAN"]    = $this->post('kd_kecamatan');
        $param["NAMA_KECAMATAN"]    = $this->post('nama_kecamatan');
        $param["KD_DESA"]    = $this->post('kd_desa');
        $param["NAMA_DESA"]    = $this->post('nama_desa');
        $param["NO_HP"]    = $this->post('no_hp');
        $param["TANGGAL"]    = tglToSql($this->post('tanggal'));
        $param["JENIS_APPOINTMENT"]    = $this->post('jenis_appointment');
        $param["HUBUNGI_VIA"]    = $this->post('hubungi_via');
        $param["NAMA_SALES"]    = ($this->post('nama_sales'));
        $param["TANGGAL_JANJI"]    = tglToSql($this->post('tanggal_janji'));
        $param["JAM_JANJI"]      = $this->post('jam_janji');
        $param["KETERANGAN"]      = $this->post('keterangan');
        $param["NAMA_CUSTOMER"]      = $this->post('nama_customer');
        $param["KD_SALES"]      = $this->post('kd_sales');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_APPOINTMENT_INSERT",$param,'post',TRUE);
    }
    /**
    /**
     * [appointment_put description]
     * @return [type] [description]
     */
    public function appointment_put(){
        $param = array();
        $param["NO_TRANS"]          = $this->put('no_trans');
        $param["KD_DEALER"]      = $this->put('kd_dealer');
        $param["KD_CUSTOMER"]    = $this->put('kd_customer');
        $param["ALAMAT"]    = ($this->put('alamat'));
        $param["KD_PROPINSI"]    = $this->put('kd_propinsi');
        $param["NAMA_PROPINSI"]    = $this->put('nama_propinsi');
        $param["KD_KABUPATEN"]    = $this->put('kd_kabupaten');
        $param["NAMA_KABUPATEN"]    = $this->put('nama_kabupaten');
        $param["KD_KECAMATAN"]    = $this->put('kd_kecamatan');
        $param["NAMA_KECAMATAN"]    = $this->put('nama_kecamatan');
        $param["KD_DESA"]    = $this->put('kd_desa');
        $param["NAMA_DESA"]    = $this->put('nama_desa');
        $param["NO_HP"]    = $this->put('no_hp');
        $param["TANGGAL"]    = tglToSql($this->put('tanggal'));
        $param["JENIS_APPOINTMENT"]    = $this->put('jenis_appointment');
        $param["HUBUNGI_VIA"]    = $this->put('hubungi_via');
        $param["NAMA_SALES"]    = ($this->put('nama_sales'));
        $param["TANGGAL_JANJI"]    = tglToSql($this->put('tanggal_janji'));
        $param["JAM_JANJI"]      = $this->put('jam_janji');
        $param["KETERANGAN"]      = $this->put('keterangan');
        $param["NAMA_CUSTOMER"]      = $this->put('nama_customer');
        $param["KD_SALES"]      = $this->put('kd_sales');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_APPOINTMENT_UPDATE",$param,'put',TRUE);
    }
    /**
     * [appointment_delete description]
     * @return [type] [description]
     */
    public function appointment_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_APPOINTMENT_DELETE",$param,'delete',TRUE);
    }
    public function appointcoming_put(){
        $param = array();
        $param["NO_TRANS"]   = $this->put('no_trans');
        $param["GUEST_NO"]   = $this->put('guest_no');
        $param["KD_SALES"]   = $this->put('kd_sales');
        $this->resultdata("SP_TRANS_APPOINTMENT_COMING",$param,'put',TRUE);
    }

    /**
     * [typemotor_marketing_get description]
     * @return [type] [description]
     */
    public function typemotor_marketing_get(){
        $param = array();$search='';
        if($this->get("tipe_produksi")){
            $param["TIPE_PRODUKSI"]     = $this->get("tipe_produksi");
        }
        if($this->get("type_marketing")){
            $param["TYPE_MARKETING"]     = $this->get("type_marketing");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "TIPE_PRODUKSI" => $this->get("keyword"),
                "TYPE_MARKETING" => $this->get("keyword")
                );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_TYPEMOTOR_MARKETING",$param);
    }
    /**
     * [typemotor_marketing_post description]
     * @return [type] [description]
     */
    public function typemotor_marketing_post(){
        $param = array();

        $param["TIPE_PRODUKSI"]      = $this->post('tipe_produksi');
        $param["TYPE_MARKETING"]  = $this->post('type_marketing');
        $this->Main_model->data_sudahada($param,"MASTER_TYPEMOTOR_MARKETING");
        $param["DESKRIPSI"]    = $this->post('deskripsi');
        $param["LAST_EFFECTIVE"]    = tglToSql($this->post('last_effective'));
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_TYPEMOTOR_MARKETING_INSERT",$param,'post',TRUE);
    }
    /**
     * [typemotor_marketing_put description]
     * @return [type] [description]
     */
    public function typemotor_marketing_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["TIPE_PRODUKSI"]      = $this->put('tipe_produksi');
        $param["TYPE_MARKETING"]        = $this->put('type_marketing');
        $param["DESKRIPSI"]        = $this->put('deskripsi');
        $param["LAST_EFFECTIVE"]    = tglToSql($this->put('last_effective'));
        $param["ROW_STATUS"]          = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_TYPEMOTOR_MARKETING_UPDATE",$param,'put',TRUE);
    }
    /**
     * [typemotor_marketing_delete description]
     * @return [type] [description]
     */
    public function typemotor_marketing_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_TYPEMOTOR_MARKETING_DELETE",$param,'delete',TRUE);
    }
    
    /**
     * [callvisit_get description]
     * @return [type] [description]
     */
    public function callvisit_get(){
        $param = array();$search='';
        if($this->get("kategori")){
            $param["KATEGORI"]     = $this->get("kategori");
        }
        if($this->get("id")){
            $param["ID"]     = $this->get("id");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KATEGORI" => $this->get("keyword")
                );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("SETUP_CALLVISIT",$param);
    }
    /**
     * [callvisit_post description]
     * @return [type] [description]
     */
    public function callvisit_post(){
        $param = array();
        $param["KATEGORI"]      = $this->post('kategori');
        $param["STATUS"]    = $this->post('status');
        $param["KETERANGAN"]  = ($this->post('keterangan'));
        $param["KLASIFIKASI"]  = $this->post('klasifikasi');
        $this->Main_model->data_sudahada($param,"SETUP_CALLVISIT");
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_SETUP_CALLVISIT_INSERT",$param,'post',TRUE);
    }
    /**
     * [callvisit_put description]
     * @return [type] [description]
     */
    public function callvisit_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["KATEGORI"]      = $this->put('kategori');
        $param["STATUS"]        = $this->put('status');
        $param["KETERANGAN"]        = ($this->put('keterangan'));
        $param["KLASIFIKASI"]        = $this->put('klasifikasi');
        $param["ROW_STATUS"]        = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_SETUP_CALLVISIT_UPDATE",$param,'put',TRUE);
    }
    /**
     * [callvisit_delete description]
     * @return [type] [description]
     */
    public function callvisit_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_SETUP_CALLVISIT_DELETE",$param,'delete',TRUE);
    }


    /**
     * [part_customer_get description]
     * @return [type] [description]
     */
    public function part_customer_get(){
        $param = array();$search='';
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_CUSTOMER" => $this->get("keyword"),
                //"KD_DEALER" => $this->get("keyword")
                );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_PART_CUSTOMER",$param);
    }
    /**
     * [part_customer_post description]
     * @return [type] [description]
     */
    public function part_customer_post(){
        $param = array();
        $param["KD_CUSTOMER"]      = $this->post('kd_customer');
        $this->Main_model->data_sudahada($param,"TRANS_PART_CUSTOMER");
        $param["KD_DEALER"]    = $this->post('kd_dealer');
        $param["KD_JENISCUSTOMER"]  = $this->post('kd_jeniscustomer');
        $param["KD_MAINDEALER"]  = $this->post('kd_maindealer');
        $this->Main_model->data_sudahada($param,"TRANS_PART_CUSTOMER");
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_PART_CUSTOMER_INSERT",$param,'post',TRUE);
    }
    /**
     * [part_customer_put description]
     * @return [type] [description]
     */
    public function part_customer_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["KD_CUSTOMER"]      = $this->put('kd_customer');
        $param["KD_DEALER"]        = $this->put('kd_dealer');
        $param["KD_JENISCUSTOMER"]        = $this->put('kd_jeniscustomer');
        $param["KD_MAINDEALER"]        = $this->put('kd_maindealer');
        $param["ROW_STATUS"]        = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PART_CUSTOMER_UPDATE",$param,'put',TRUE);
    }
    /**
     * [part_customer_delete description]
     * @return [type] [description]
     */
    public function part_customer_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_CUSTOMER_DELETE",$param,'delete',TRUE);
    }


    /**
     * [parts_vs_defaultrak_get description]
     * @return [type] [description]
     */
    public function parts_vs_defaultrak_get(){
        $param = array();$search='';
        if($this->get("part_number")){
            $param["PART_NUMBER"] = $this->get("part_number");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_lokasi")){
            $param["KD_LOKASI"] = $this->get("kd_lokasi");
        }
        if($this->get("kd_gudang")){
            $param["KD_GUDANG"] = $this->get("kd_gudang");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "PART_NUMBER" => $this->get("keyword"),
                "LOKASI_RAK_BIN_ID" => $this->get("keyword"),
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_PART_DEFAULT_RAK",$param);
    }
    /**
     * [parts_vs_defaultrak_post description]
     * @return [type] [description]
     */
    public function parts_vs_defaultrak_post(){
        $param = array();

        $param["KD_DEALER"]      = $this->post('kd_dealer');
        $param["KD_MAINDEALER"]    = $this->post('kd_maindealer');
        $param["PART_NUMBER"]  = $this->post('part_number');
        $param["KD_GUDANG"]    = $this->post('kd_gudang');
        $param["KD_LOKASI"]    = $this->post('kd_lokasi');
        $this->Main_model->data_sudahada($param,"TRANS_PART_DEFAULT_RAK");
        $param["KETERANGAN"]    = $this->post('keterangan');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_PARTS_VS_DEFAULTRAK_INSERT",$param,'post',TRUE);
    }
    /**
     * [parts_vs_defaultrak_put description]
     * @return [type] [description]
     */
    public function parts_vs_defaultrak_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["KD_DEALER"]      = $this->put('kd_dealer');
        $param["KD_MAINDEALER"]        = $this->put('kd_maindealer');
        $param["PART_NUMBER"]        = $this->put('part_number');
        $param["KD_GUDANG"]    = $this->put('kd_gudang');
        $param["KD_LOKASI"]    = $this->put('kd_lokasi');
        $param["KETERANGAN"]        = $this->put('keterangan');
        $param["ROW_STATUS"]        = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PARTS_VS_DEFAULTRAK_UPDATE",$param,'put',TRUE);
    }
    /**
     * [parts_vs_defaultrak_delete description]
     * @return [type] [description]
     */
    public function parts_vs_defaultrak_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PARTS_VS_DEFAULTRAK_DELETE",$param,'delete',TRUE);
    }


    /**
     * [lokasi_rak_bin_get description]
     * @return [type] [description]
     */
    public function lokasi_rak_bin_get(){
        $param = array();$search='';
        if($this->get("kd_lokasi")){
            $param["KD_LOKASI"]     = $this->get("kd_lokasi");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }        
        if($this->get("kd_gudang")){
            $param["KD_GUDANG"]     = $this->get("kd_gudang");
        }
        if($this->get("kd_rak")){
            $param["KD_RAK"]     = $this->get("kd_rak");
        }
        if($this->get("rak_default")){
            $param["RAK_DEFAULT"]     = $this->get("rak_default");
        }
        if($this->get("kd_binbox")){
            $param["KD_BINBOX"]     = $this->get("kd_binbox");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_LOKASI" => $this->get("keyword"),
                "KD_RAK"    => $this->get("keyword"),
                "KD_BINBOX" => $this->get("keyword")
            );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_LOKASI_RAK_BIN",$param);
    }
    /**
     * [lokasi_rak_bin_post description]
     * @return [type] [description]
     */
    public function lokasi_rak_bin_post(){
        $param = array();
        $param["KD_DEALER"]     = $this->post('kd_dealer');
        $param["KD_LOKASI"]     = $this->post('kd_lokasi');
        $param["KD_GUDANG"]     = strtoupper($this->post('kd_gudang'));
        $param["KD_RAK"]        = strtoupper($this->post('kd_rak'));
        $param["KD_BINBOX"]     = strtoupper($this->post('kd_binbox'));
        $param["RAK_DEFAULT"]   = $this->post('rak_default');
        $this->Main_model->data_sudahada($param,"MASTER_LOKASI_RAK_BIN");
        $param["NAMA_GUDANG"]   = (strtoupper($this->post('nama_gudang')));
        $param["DEFAULTS"]      = $this->post('defaults');
        $param["DEFAULTS1"]     = $this->post('defaults1');
        $param["KETERANGAN"]    = $this->post('keterangan');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_LOKASI_RAK_BIN_INSERT",$param,'post',TRUE);
    }
    /**
     * [lokasi_rak_bin_put description]
     * @return [type] [description]
     */
    public function lokasi_rak_bin_put(){
        $param = array();
        $param["ID"]                = $this->put('id');
        $param["KD_LOKASI"]         = $this->put('kd_lokasi');
        $param["KD_DEALER"]         = $this->put('kd_dealer');
        $param["KD_GUDANG"]         = strtoupper($this->put('kd_gudang'));
        $param["KD_RAK"]            = strtoupper($this->put('kd_rak'));
        $param["KD_BINBOX"]         = strtoupper($this->put('kd_binbox'));
        $param["NAMA_GUDANG"]       = $this->put('nama_gudang');
        $param["RAK_DEFAULT"]       = $this->put('rak_default');
        $param["DEFAULTS"]          = $this->put('defaults');
        $param["DEFAULTS1"]         = $this->put('defaults1');
        $param["KETERANGAN"]        = $this->put('keterangan');
        $param["ROW_STATUS"]        = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_LOKASI_RAK_BIN_UPDATE",$param,'put',TRUE);
    }
    /**
     * [lokasi_rak_bin_delete description]
     * @return [type] [description]
     */
    public function lokasi_rak_bin_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_LOKASI_RAK_BIN_DELETE",$param,'delete',TRUE);
    }


    /**
     * [part_groupcustomer_get description]
     * @return [type] [description]
     */
    public function part_groupcustomer_get(){
        $param = array();$search='';
        if($this->get("kd_groupcp")){
            $param["KD_GROUPCP"]     = $this->get("kd_groupcp");
        }
        if($this->get("nama_perusahaan")){
            $param["NAMA_PERUSAHAAN"]     = $this->get("nama_perusahaan");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_GROUPCP" => $this->get("keyword"),
                "NAMA_PERUSAHAAN" => $this->get("keyword")
                );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_PART_GROUPCUSTOMER",$param);
    }
    /**
     * [part_groupcustomer_post description]
     * @return [type] [description]
     */
    public function part_groupcustomer_post(){
        $param = array();

        $param["KD_GROUPCP"]      = $this->post('kd_groupcp');
        $this->Main_model->data_sudahada($param,"TRANS_PART_GROUPCUSTOMER");
        $param["KD_PERUSAHAAN"]  = $this->post('kd_perusahaan');
        $param["NAMA_PERUSAHAAN"]    = $this->post('nama_perusahaan');
        $param["ALAMAT_PERUSAHAAN"]    = $this->post('alamat_perusahaan');
        $param["KD_KOTAPERUSAHAAN"]    = $this->post('kd_kotaperusahaan');
        $param["NOTEL_PERUSAHAAN"]    = $this->post('notel_perusahaan');
        $param["KD_DEALER"]    = $this->post('kd_dealer');
        $param["KET_TAMBAHAN"]    = $this->post('ket_tambahan');
        $param["KD_MAINDEALER"]    = $this->post('kd_maindealer');
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_PART_GROUPCUSTOMER_INSERT",$param,'post',TRUE);
    }
    /**
     * [part_groupcustomer_put description]
     * @return [type] [description]
     */
    public function part_groupcustomer_put(){
        $param = array();
        $param["ID"]    = $this->put('id');
        $param["KD_GROUPCP"]    = $this->put('kd_groupcp');
        $param["KD_PERUSAHAAN"] = $this->put('kd_perusahaan');
        $param["NAMA_PERUSAHAAN"]   = $this->put('nama_perusahaan');
        $param["ALAMAT_PERUSAHAAN"] = $this->put('alamat_perusahaan');
        $param["KD_KOTAPERUSAHAAN"] = $this->put('kd_kotaperusahaan');
        $param["NOTEL_PERUSAHAAN"]  = $this->put('notel_perusahaan');
        $param["KD_DEALER"] = $this->put('kd_dealer');
        $param["KET_TAMBAHAN"]  = $this->put('ket_tambahan');
        $param["KD_MAINDEALER"]  = $this->put('kd_maindealer');
        $param["ROW_STATUS"]  = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PART_GROUPCUSTOMER_UPDATE",$param,'put',TRUE);
    }
    /**
     * [part_groupcustomer_delete description]
     * @return [type] [description]
     */
    public function part_groupcustomer_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_GROUPCUSTOMER_DELETE",$param,'delete',TRUE);
    }


    /**
     * [groupcustomer_get description]
     * @return [type] [description]
     */
    public function groupcustomer_get(){
        $param = array();$search='';
        if($this->get("kd_groupcustomer")){
            $param["KD_GROUPCUSTOMER"]     = $this->get("kd_groupcustomer");
        }
        if($this->get("nama_groupcustomer")){
            $param["NAMA_GROUPCUSTOMER"]     = $this->get("nama_groupcustomer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_GROUPCUSTOMER" => $this->get("keyword"),
                "NAMA_GROUPCUSTOMER" => $this->get("keyword")
                );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_GROUPCUSTOMER",$param);
    }
    /**
     * [groupcustomer_post description]
     * @return [type] [description]
     */
    public function groupcustomer_post(){
        $param = array();

        $param["KD_GROUPCUSTOMER"]      = $this->post('kd_groupcustomer');
        $param["NAMA_GROUPCUSTOMER"]  = $this->post('nama_groupcustomer');
        $param["NO_TELP"]    = $this->post('no_telp');
        $param["KD_PROPINSI"]    = $this->post('kd_propinsi');
        $param["KD_KABUPATEN"]    = $this->post('kd_kabupaten');
        $param["ALAMAT_LENGKAP"]    = $this->post('alamat_lengkap');
        $param["NO_NPWP"]    = $this->post('no_npwp');
        $param["KD_DEALER"]    = $this->post('kd_dealer');
        $param["KD_MAINDEALER"] = $this->post('kd_maindealer');
        $param["CREATED_BY"]    = $this->post('created_by');
        $this->Main_model->data_sudahada($param,"MASTER_GROUPCUSTOMER");

        // print_r($param);
        $this->resultdata("SP_MASTER_GROUPCUSTOMER_INSERT",$param,'post',TRUE);
    }
    /**
     * [groupcustomer_put description]
     * @return [type] [description]
     */
    public function groupcustomer_put(){
        $param = array();
        $param["ID"]    = $this->put('id');
        $param["KD_GROUPCUSTOMER"]    = $this->put('kd_groupcustomer');
        $param["NAMA_GROUPCUSTOMER"] = $this->put('nama_groupcustomer');
        $param["NO_TELP"]   = $this->put('no_telp');
        $param["KD_PROPINSI"] = $this->put('kd_propinsi');
        $param["KD_KABUPATEN"] = $this->put('kd_kabupaten');
        $param["ALAMAT_LENGKAP"]  = $this->put('alamat_lengkap');
        $param["NO_NPWP"]  = $this->put('no_npwp');
        $param["KD_DEALER"] = $this->put('kd_dealer');
        $param["KD_MAINDEALER"] = $this->put('kd_maindealer');
        $param["ROW_STATUS"] = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_GROUPCUSTOMER_UPDATE",$param,'put',TRUE);
    }
    /**
     * [groupcustomer_delete description]
     * @return [type] [description]
     */
    public function groupcustomer_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_GROUPCUSTOMER_DELETE",$param,'delete',TRUE);
    }

    /**
     * [groupcustomer_mapping_get description]
     * @return [type] [description]
     */
    public function groupcustomer_mapping_get(){
        $param = array();$search='';
        if($this->get("kd_groupcustomer")){
            $param["KD_GROUPCUSTOMER"]     = $this->get("kd_groupcustomer");
        }
        if($this->get("kd_typecustomer")){
            $param["KD_TYPECUSTOMER"]     = $this->get("kd_typecustomer");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("kd_maindealer")){
            $param["KD_MAINDEALER"]     = $this->get("kd_maindealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_GROUPCUSTOMER" => $this->get("keyword"),
                "KD_TYPECUSTOMER" => $this->get("keyword")
                );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("MASTER_GROUPCUSTOMER_MAPPING",$param);
    }
    /**
     * [groupcustomer_mapping_post description]
     * @return [type] [description]
     */
    public function groupcustomer_mapping_post(){
        $param = array();

        $param["KD_GROUPCUSTOMER"]      = $this->post('kd_groupcustomer');
        $param["KD_TYPECUSTOMER"]  = $this->post('kd_typecustomer');
        $param["KD_DEALER"]    = $this->post('kd_dealer');
        $param["KD_MAINDEALER"]    = $this->post('kd_maindealer');
        $this->Main_model->data_sudahada($param,"MASTER_GROUPCUSTOMER_MAPPING");
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_MASTER_GROUPCUSTOMER_MAPPING_INSERT",$param,'post',TRUE);
    }
    /**
     * [groupcustomer_mapping_put description]
     * @return [type] [description]
     */
    public function groupcustomer_mapping_put(){
        $param = array();
        $param["ID"]    = $this->put('id');
        $param["KD_GROUPCUSTOMER"]    = $this->put('kd_groupcustomer');
        $param["KD_TYPECUSTOMER"] = $this->put('kd_typecustomer');
        $param["KD_DEALER"]   = $this->put('kd_dealer');
        $param["KD_MAINDEALER"] = $this->put('kd_maindealer');
        $param["ROW_STATUS"] = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_MASTER_GROUPCUSTOMER_MAPPING_UPDATE",$param,'put',TRUE);
    }
    /**
     * [groupcustomer_mapping_delete description]
     * @return [type] [description]
     */
    public function groupcustomer_mapping_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_MASTER_GROUPCUSTOMER_MAPPING_DELETE",$param,'delete',TRUE);
    }

     /**
     * [part_customer_mapping_get description]
     * @return [type] [description]
     */
    public function part_customer_mapping_get(){
        $param = array();$search='';
        if($this->get("kd_customer")){
            $param["KD_CUSTOMER"]     = $this->get("kd_customer");
        }
        if($this->get("kd_groupcustomer")){
            $param["KD_GROUPCUSTOMER"]     = $this->get("kd_groupcustomer");
        }
        if($this->get("id")){
            $param["ID"]     = $this->get("id");
        }
        if($this->get("kd_dealer")){
            $param["KD_DEALER"]     = $this->get("kd_dealer");
        }
        if($this->get("keyword")){
            $param=array();
            $search= array(
                "KD_CUSTOMER" => $this->get("keyword"),
                "KD_GROUPCUSTOMER" => $this->get("keyword"),
                "PART_NUMBER" => $this->get("keyword")
                );
        }
        $this->Main_model->set_search_criteria($search);
        $this->Main_model->set_statusdata($this->get('row_status'));
        $custom="";
        if($this->get("custom")){
            $this->Main_model->set_customcriteria($this->get("custom"));
        }
        $this->Main_model->set_orderby($this->get("orderby"));
        $this->Main_model->set_groupby($this->get("groupby"));
        $this->Main_model->set_selectfield($this->get('field'));
        $this->Main_model->set_offset($this->get("offset"));
        $this->Main_model->set_recordlimit($this->get("limit"));
        $this->Main_model->set_jointable($this->get("jointable"));
        $this->resultdata("TRANS_PART_CUSTOMER_MAPPING",$param);
    }
    /**
     * [part_customer_mapping_post description]
     * @return [type] [description]
     */
    public function part_customer_mapping_post(){
        $param = array();
        $param["KD_MAINDEALER"]      = $this->post('kd_maindealer');
        $param["KD_DEALER"]    = $this->post('kd_dealer');
        $param["KD_CUSTOMER"]      = $this->post('kd_customer');
        $param["KD_GROUPCUSTOMER"]      = $this->post('kd_groupcustomer');
        $param["PART_NUMBER"]    = $this->post('part_number');
        $param["JENIS"]    = $this->post('jenis');
        $this->Main_model->data_sudahada($param,"TRANS_PART_CUSTOMER_MAPPING",TRUE);
        $param["CREATED_BY"]    = $this->post('created_by');

        // print_r($param);
        $this->resultdata("SP_TRANS_PART_CUSTOMER_MAPPING_INSERT",$param,'post',TRUE);
    }
    /**
     * [part_customer_mapping_put description]
     * @return [type] [description]
     */
    public function part_customer_mapping_put(){
        $param = array();
        $param["ID"]          = $this->put('id');
        $param["KD_CUSTOMER"]      = $this->put('kd_customer');
        $param["KD_GROUPCUSTOMER"]      = $this->put('kd_groupcustomer');
        $param["PART_NUMBER"]        = $this->put('part_number');
        $param["KD_MAINDEALER"]      = $this->put('kd_maindealer');
        $param["KD_DEALER"]        = $this->put('kd_dealer');
        $param["JENIS"]    = $this->put('jenis');
        $param["ROW_STATUS"]        = $this->put('row_status');
        $param["LASTMODIFIED_BY"]   = $this->put("lastmodified_by");

        $this->resultdata("SP_TRANS_PART_CUSTOMER_MAPPING_UPDATE",$param,'put',TRUE);
    }
    /**
     * [part_customer_mapping_delete description]
     * @return [type] [description]
     */
    public function part_customer_mapping_delete(){
        $param = array();
        $param["ID"]          = $this->delete('id');
        $param["LASTMODIFIED_BY"]   = $this->delete("lastmodified_by");
        $this->resultdata("SP_TRANS_PART_CUSTOMER_MAPPING_DELETE",$param,'delete',TRUE);
    }

}
?>