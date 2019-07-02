<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Custom_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper("zetro_helper");
	}
	function Leasing_Achieve($param=null){
		$query="SELECT SL.KD_MAINDEALER,SL.KD_DEALER,SL.KD_LEASING,SL.TARGET_LEASING,SL.TAHUN,
				CASE WHEN SL.KD_LEASING='OTH' THEN ISNULL(PP.JUMLAH,0) ELSE ISNULL(P.JUMLAH,0) END TOTAL_SALES,
				CASE WHEN SL.KD_LEASING='OTH' THEN 'OTHERS LEASING' ELSE MF.NAMA_LEASING END NAMA_LEASING,
				CASE WHEN SL.KD_LEASING='OTH' THEN ISNULL(CAST((PP.JUMLAH/(SELECT dbo.fnTotalPenjualan(SL.TAHUN,SL.KD_DEALER))) AS DECIMAL(5,3)),0) ELSE
				ISNULL(CAST((P.JUMLAH/(SELECT dbo.fnTotalPenjualan(SL.TAHUN,SL.KD_DEALER))) AS DECIMAL(5,3)),0) END ACHIEVE,SL.RANGKING_LEASING
				FROM TRANS_SPK_LEASING_KOMPOSISI SL
				LEFT JOIN (
				SELECT TSL.KD_FINCOY,COUNT(TSL.KD_FINCOY)JUMLAH,YEAR(TS.TGL_SPK)TAHUN,TS.KD_DEALER FROM TRANS_SPK_LEASING TSL
				LEFT JOIN TRANS_SPK TS ON TS.ID=TSL.SPK_ID
				WHERE TSL.HASIL='Approve' AND TSL.ROW_STATUS>-1 
				AND TS.STATUS_SPK >1 AND TS.ROW_STATUS >=0
				GROUP BY TSL.KD_FINCOY,YEAR(TS.TGL_SPK),TS.KD_DEALER
				) AS P ON P.KD_FINCOY=SL.KD_LEASING AND P.TAHUN=SL.TAHUN AND p.KD_DEALER=SL.KD_DEALER
				LEFT JOIN (
				SELECT 'OTH' KD_FINCOY,COUNT(TSL.KD_FINCOY)JUMLAH,YEAR(TS.TGL_SPK)TAHUN,TS.KD_DEALER 
				FROM TRANS_SPK_LEASING TSL
				LEFT JOIN TRANS_SPK TS ON TS.ID=TSL.SPK_ID
				WHERE TSL.HASIL='Approve' AND TSL.ROW_STATUS>-1 
				AND TSL.KD_FINCOY  NOT IN(SELECT K.KD_LEASING FROM TRANS_SPK_LEASING_KOMPOSISI K WHERE K.KD_LEASING !='OTH')
				AND TS.STATUS_SPK > 1 AND TS.ROW_STATUS >=0 
				GROUP BY YEAR(TS.TGL_SPK),TS.KD_DEALER
				) AS PP ON PP.KD_FINCOY=SL.KD_LEASING AND PP.TAHUN=SL.TAHUN  
				AND PP.TAHUN=SL.TAHUN AND PP.KD_DEALER=SL.KD_DEALER AND PP.TAHUN=SL.TAHUN
				LEFT JOIN MASTER_COM_FINANCE MF ON MF.KD_LEASING=SL.KD_LEASING ";
		$query .=$param;

		return $query;
	}

	function simpan_sales($json=null){
		$query="";
		if($json){
			$query='TRUNCATE TABLE MASTER_SALESMAN_TMP; DECLARE @json nvarchar(max)=\''.$json.'\';INSERT INTO MASTER_SALESMAN_TMP (KD_SALES,NAMA_SALES,KD_HSALES,KD_DEALER,STATUS_SALES,GROUP_SALES,CREATED_BY) SELECT * FROM OPENJSON(@json) WITH (KD_SALES varchar(10),NAMA_SALES varchar(150),KD_HSALES varchar(10), KD_DEALER varchar(5),STATUS_SALES VARCHAR(1),GROUP_SALES varchar(2),CREATED_BY varchar(50)) SELECT * FROM MASTER_SALESMAN_TMP WHERE KD_DEALER =\''.$this->session->userdata("kd_dealer").'\'';

		}
		return $query;
	}

	/*function simpan_desa($json=null){
		$query="";
		if($json){
			$query='TRUNCATE TABLE MASTER_DESA_TMP; DECLARE @json nvarchar(max)=\''.$json.'\';INSERT INTO MASTER_DESA_TMP (KD_DESA,KD_KOTA,KD_KECAMATAN,NAMA_PROPINSI,NAMA_KOTA,NAMA_KECAMATAN,NAMA_DESA,KODE_POS,STATUS,KD_DESAAHM,DESAAHM,CREATED_BY) SELECT * FROM OPENJSON(@json) WITH (KD_DESA varchar(10),KD_KOTA varchar(10),KD_KECAMATAN varchar(10), KD_DESA varchar(10),NAMA_PROPINSI varchar(150),NAMA_KOTA varchar(150), NAMA_KECAMATAN varchar(150),NAMA_DESA varchar(150), KODE_POS varchar(10),STATUS int,KD_DESAAHM varchar(10),DESAAHM varchar(100) ,CREATED_BY varchar(50)) SELECT * FROM MASTER_DESA_TMP';

		}
		return $query;
	}*/

	function simpan_kecamatan($json=null){
		$query="";
		if($json){
			$query='TRUNCATE TABLE MASTER_KECAMATAN_TMP; DECLARE @json nvarchar(max)=\''.$json.'\';INSERT INTO MASTER_KECAMATAN_TMP (KD_KABUPATEN,KD_KECAMATAN,NAMA_KECAMATAN,CREATED_BY) SELECT * FROM OPENJSON(@json) WITH (KD_KABUPATEN varchar(10),KD_KECAMATAN varchar(10),NAMA_KECAMATAN varchar(150), ,CREATED_BY varchar(50)) SELECT * FROM MASTER_KECAMATAN_TMP';

		}
		return $query;
	}

	function simpan_saleskupon($json=null){
		$query="";
		if($json){
			$query='TRUNCATE TABLE SETUP_SALESKUPON_TMP; DECLARE @json nvarchar(max)=\''.$json.'\';INSERT INTO SETUP_SALESKUPON_TMP (KD_SALESKUPON,NAMA_SALESKUPON,START_DATE,END_DATE,END_CLAIM,NO_PERKIRAAN,NO_SUBPERKIRAAN,KD_TYPEMOTOR,TOP1,TOP2,NILAI,CREATED_BY) SELECT * FROM OPENJSON(@json) WITH (KD_SALESKUPON varchar(10),NAMA_SALESKUPON varchar(150),START_DATE date, END_DATE date,END_CLAIM date,NO_PERKIRAAN varchar(50),NO_SUBPERKIRAAN varchar(50), KD_TYPEMOTOR varchar(10), TOP1 varchar(50), TOP2 varchar(50), NILAI varchar(100), CREATED_BY varchar(50)) SELECT * FROM SETUP_SALESKUPON_TMP';

		}
		return $query;
	}

	function simpan_sjmasuk($json=null){
		$query="";
		if($json){
			$query="DECLARE @json nvarchar(max)='".$json."';INSERT INTO TRANS_PART_SJMASUK (KD_MAINDEALER,KD_DEALERAHM,NO_SJ,TGL_SJ,JATUH_TEMPO,NO_PO,PART_NUMBER,QTY,PRICE,DISKON,PPN,NETPRICE,KD_TRANS) SELECT * FROM OPENJSON(@json) WITH(kdmd varchar(5),kddlr varchar(5),nosj varchar(25),fakdate date,tgljt date,pono varchar(25),partno varchar(30),qty decimal(18,2),price decimal(18,2),disc decimal(18,2),ppn decimal(18,2), netprice decimal(18,2),kdtrans varchar(10))";
		}
		return $query;
	}
	function simpan_part($json=null){
		$query="";
		if($json){
			$query="DECLARE @JSON NVARCHAR(MAX)='".$json."';
			INSERT INTO MASTER_PART(PART_NUMBER,PART_DESKRIPSI,HET,HARGA_BELI,KD_SUPPLIER,KD_GROUPSALES,PART_REFERENCE,PART_STATUS,PART_SUPERSEED,MOQ_DK,MOQ_DM,MOQ_DB,PART_NUMBERTYPE,PART_MOVING,PART_SOURCE,PART_RANK,PART_CURRENT,PART_TYPE,PART_LIFETIME,PART_GROUP,ROW_STATUS,CREATED_BY) SELECT * FROM OPENJSON(@JSON) WITH (part_number varchar(30),part_deskripsi varchar(max),het decimal(18,2),harga_beli decimal(18,2),kd_supplier varchar(11),kd_groupsales varchar(5),part_reference varchar(30),part_status varchar(1),part_superseed varchar(25),moq_dk decimal(18,2),moq_dm decimal(18,2),moq_db decimal(18,2),part_numbertype varchar(1),part_moving varchar(1),part_source varchar(1),part_rank varchar(1),part_current varchar(1),part_type varchar(1),part_lifetime varchar(1),part_group varchar(2),row_status int,created_by varchar(50))";
		}
		return $query;
	}
	function check_desa($json=null){
		$folder = getConfig("UPJSON_C");
		$query="DECLARE @JSON NVARCHAR(MAX)
				SELECT @JSON = BulkColumn
				FROM OPENROWSET (BULK '".$folder."\desa.json', SINGLE_CLOB) as j

				    SELECT * FROM OPENJSON(@JSON) 
				    WITH(kdkota varchar(10),kdkec varchar(10),kdKel varchar(10),
				                kota varchar(150),kecamatan varchar(150),kelurahan varchar(150),kodePos varchar(5),
				    status varchar(1),kdkelahm varchar(20),kelurahanahm varchar(100))
				    WHERE ( LTRIM(RTRIM(kdKel))) NOT IN((SELECT LTRIM(RTRIM(KD_DESA)) FROM MASTER_DESA))
					OR (LTRIM(RTRIM(kdkec))NOT IN((SELECT LTRIM(RTRIM(KD_KECAMATAN)) FROM MASTER_DESA))
					OR LTRIM(RTRIM(kdkota))NOT IN((SELECT LTRIM(RTRIM(KD_KOTA)) FROM MASTER_DESA))
					OR LTRIM(RTRIM(kelurahan))NOT IN((SELECT LTRIM(RTRIM(NAMA_DESA)) FROM MASTER_DESA))
					OR LTRIM(RTRIM(kodepos)) NOT IN((SELECT LTRIM(RTRIM(KODE_POS)) FROM MASTER_DESA))
					OR LTRIM(RTRIM(kdkelahm))NOT IN((SELECT LTRIM(RTRIM(KD_DESAAHM)) FROM MASTER_DESA))
					OR LTRIM(RTRIM(kelurahanahm))NOT IN((SELECT LTRIM(RTRIM(DESAAHM)) FROM MASTER_DESA)))";
		return $query;
	}
	function hargabeli_part($where=null){
		$query="SELECT TOP 1 WITH ties P.NO_TRANS,P.TGL_TRANS, PART_NUMBER,JUMLAH,HARGA_BELI,DISKON,PPN,NETPRICE,P.KD_DEALER,P.KD_MAINDEALER
				FROM TRANS_PART_TERIMADETAIL PD
				LEFT JOIN TRANS_PART_TERIMA P ON P.NO_TRANS=PD.NO_TRANS
				WHERE PD.ROW_STATUS>-1 $where
				ORDER BY ROW_NUMBER() OVER(PARTITION BY PART_NUMBER ORDER BY TGL_TRANS DESC)";

		return $query;
	}
	function nomor_kk($kd_kus,$no_kk){
		$query = "UPDATE MASTER_CUSTOMER SET NO_KK='".$no_kk."',LASTMODIFIED_BY ='".$this->session->userdata('user_id')."|SPK', LASTMODIFIED_TIME=GETDATE() WHERE KD_CUSTOMER='".$kd_kus."'";
		return $query;
	}
	function lapPinjamanaBPKB($param,$ttrecord=null){
		//where field width prefix REQ must value = 2 
		$query="WITH STNK AS (
					SELECT D.ID,K.KD_DEALER,K.TGLMULAI_PENGURUSAN AS TGL_PINJAM, K.NO_TRANS,D.NAMA_PEMILIK,
					D.ALAMAT_PEMILIK,D.STNK_ID,	{fn CONCAT(D.KD_MESIN,D.NO_MESIN)}NO_MESIN,
					CASE WHEN D.REQ_ADMIN_SAMSAT=2 THEN D.ADMIN_SAMSAT ELSE 0 END ADMIN_SAMSAT,
					CASE WHEN D.REQ_BPKB=2 THEN D.BPKB ELSE 0 END BPKB, CASE WHEN D.REQ_STCK=2 THEN D.STCK ELSE 0 END STCK,
					CASE WHEN D.REQ_PLAT_ASLI=2 THEN D.PLAT_ASLI ELSE 0 END PLAT_ASLI,D.ROW_STATUS
					,D.REQ_ADMIN_SAMSAT,D.REQ_BPKB,D.REQ_PLAT_ASLI,D.REQ_STCK
					FROM TRANS_STNK_DETAIL D
					LEFT JOIN TRANS_STNK K ON K.ID=D.STNK_ID
					WHERE (REQ_ADMIN_SAMSAT=2 OR REQ_BPKB=2 OR REQ_PLAT_ASLI=2 OR REQ_STCK=2)
					AND D.ROW_STATUS >=0 AND D.REFF_SOURCE=2 AND K.ROW_STATUS >=0
				)";
				if($ttrecord){
				$query .= "SELECT COUNT(ID) AS ID
						FROM STNK WHERE KD_DEALER='".$param["kd_dealer"]."' 
						AND TGL_PINJAM BETWEEN '".$param["start_date"]."' AND '".$param["end_date"]."'";
			}else{
				$query .= "SELECT *,(ADMIN_SAMSAT+BPKB+PLAT_ASLI+STCK) AS BIAYA_BPKB
						FROM STNK WHERE KD_DEALER='".$param["kd_dealer"]."' 
						AND TGL_PINJAM BETWEEN '".$param["start_date"]."' AND '".$param["end_date"]."'
						ORDER BY ID DESC ";
				$query .=(isset($param["limit"]))?"OFFSET ".$param["offset"]." ROWS FETCH NEXT ".$param["limit"]." ROWS ONLY":"";
			}
		return $query;
	}
}
?>