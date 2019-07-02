<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Main model
 * Author 	: Iswan Putera {zetrosoft}
 */

	class Custom_model extends CI_Model{
		function __construct(){
			parent::__construct();
			$this->load->helper('zetro_helper');
		}
		function set_logines($userid=null){
			$this->logines=$userid;
		}
		function get_logines(){
			return $this->logines;
		}
		/**
		 * [querypdmp description]
		 * @return [type] [description]
		 */
		function querypdmp(){
			$rand=mt_rand(10,1000);
			$query=" IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[master_part_".$rand."]') AND type in (N'U')) DROP TABLE [dbo].[master_part_".$rand."];
					CREATE TABLE master_part_".$rand." (part_number varchar(30),part_deskripsi varchar(max),het decimal(18,2),harga_beli decimal(18,2),kd_supplier varchar(11),kd_groupsales varchar(5),part_reference varchar(30),part_status varchar(1),part_superseed varchar(25),moq_dk decimal(18,2),moq_dm decimal(18,2),moq_db decimal(18,2),part_numbertype varchar(1),part_moving varchar(1),part_source varchar(1),part_rank varchar(1),part_current varchar(1),part_type varchar(1),part_lifetime varchar(1),part_group varchar(2),row_status int,created_by varchar(50));";
					/*TRUNCATE TABLE MASTER_PART*/
			$this->db->query($query);
			$query="CREATE TRIGGER  [dbo].[master_part_tmp_$rand] ON  [dbo].[master_part_$rand] AFTER INSERT,UPDATE AS 
					BEGIN
						IF EXISTS(
							SELECT MS.PART_NUMBER FROM MASTER_PART MS 
							INNER JOIN inserted i ON i.part_number=MS.PART_NUMBER 
						)
						BEGIN
						UPDATE MP 
							SET MP.PART_DESKRIPSI=i.part_deskripsi,MP.HET=i.het,MP.HARGA_BELI=i.harga_beli,
								MP.KD_SUPPLIER=i.kd_supplier,MP.KD_GROUPSALES=i.kd_groupsales,MP.PART_REFERENCE=i.part_reference,
								MP.PART_STATUS=i.part_status,
								MP.PART_SUPERSEED=i.part_superseed,MP.MOQ_DK=i.moq_dk,MP.MOQ_DM=i.moq_dm,MP.MOQ_DB=i.moq_db,MP.PART_NUMBERTYPE=i.part_numbertype,
								MP.PART_MOVING=i.part_moving,MP.PART_SOURCE=i.part_source,MP.PART_RANK=i.part_rank,
								MP.PART_CURRENT=i.part_current,MP.PART_TYPE=i.part_type,MP.PART_LIFETIME=i.part_lifetime,MP.PART_GROUP=i.part_group,
								MP.LASTMODIFIED_BY=i.created_by,
								MP.LASTMODIFIED_TIME=GETDATE(),
								MP.ROW_STATUS=0
							FROM dbo.MASTER_PART MP
							INNER JOIN inserted i ON i.part_number=MP.PART_NUMBER;
							DELETE FROM master_part_$rand WHERE part_number IN(SELECT MS.PART_NUMBER FROM MASTER_PART MS);
						END;
						/*IF NOT EXISTS(
							SELECT MS.PART_NUMBER FROM MASTER_PART MS 
							INNER JOIN master_part_$rand i ON i.part_number=MS.PART_NUMBER 
						)
						BEGIN*/
							INSERT INTO MASTER_PART(PART_NUMBER,PART_DESKRIPSI,HET,HARGA_BELI,KD_SUPPLIER,KD_GROUPSALES,PART_REFERENCE,PART_STATUS,
							PART_SUPERSEED,MOQ_DK,MOQ_DM,MOQ_DB,PART_NUMBERTYPE,PART_MOVING,PART_SOURCE,PART_RANK,PART_CURRENT,PART_TYPE,PART_LIFETIME,
							PART_GROUP,ROW_STATUS,CREATED_BY) 
							SELECT part_number,part_deskripsi,het,harga_beli,kd_supplier,kd_groupsales,part_reference,
							part_status,part_superseed,moq_dk,moq_dm,moq_db,part_numbertype,part_moving,part_source,part_rank,
							part_current,part_type,part_lifetime,part_group,row_status,created_by  
							FROM master_part_$rand
						--END;
					END";
			$this->db->query($query);
			$folderJson = getConfig("UPJSON_L")."\part.json";
			
			$query="WITH tmppart as  
					(SELECT part.*  FROM OPENROWSET(BULK '".$folderJson."', SINGLE_CLOB) JSON
					CROSS APPLY OPENJSON(BulkColumn) 
					WITH (part_number varchar(30),part_deskripsi varchar(max),het decimal(18,2),harga_beli decimal(18,2),kd_supplier varchar(11),kd_groupsales varchar(5),part_reference varchar(30),part_status varchar(1),part_superseed varchar(25),moq_dk decimal(18,2),moq_dm decimal(18,2),moq_db decimal(18,2),part_numbertype varchar(1),part_moving varchar(1),part_source varchar(1),part_rank varchar(1),part_current varchar(1),part_type varchar(1),part_lifetime varchar(1),part_group varchar(2),row_status int,created_by varchar(50)) as part)

					INSERT INTO master_part_".$rand." select * from tmppart;
					IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[master_part_".$rand."]') AND type in (N'U')) DROP TABLE [dbo].[master_part_".$rand."];";
			return $this->db->query($query)->row();
		}

		/**
		 * [querypvtm description]
		 * @return [type] [description]
		 */
		function querypvtm(){
			$rand=mt_rand(10,1000);
			$query=" IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[master_pvtm_".$rand."]') AND type in (N'U')) DROP TABLE [dbo].[master_pvtm_".$rand."];
					CREATE TABLE master_pvtm_".$rand." (no_part_tipemotor varchar(50),type_marketing varchar(50),row_status int,created_by varchar(50));";
					// TRUNCATE TABLE MASTER_PVTM";
			$this->db->query($query);
			$query="CREATE TRIGGER [dbo].[master_pvtm_tmp_$rand] ON  [dbo].[master_pvtm_$rand] AFTER INSERT AS 
					BEGIN
						IF EXISTS(SELECT MS.* FROM MASTER_PVTM MS INNER JOIN inserted i ON i.no_part_tipemotor=MS.NO_PART_TIPEMOTOR AND i.type_marketing=MS.TYPE_MARKETING)
						BEGIN
						UPDATE MP 
							SET MP.LASTMODIFIED_BY=i.created_by,
								MP.LASTMODIFIED_TIME=GETDATE(),
								MP.ROW_STATUS=0
							FROM dbo.MASTER_PVTM MP
							INNER JOIN inserted i ON i.no_part_tipemotor=MP.NO_PART_TIPEMOTOR AND i.type_marketing=MP.TYPE_MARKETING;
							DELETE FROM master_pvtm_$rand WHERE {fn CONCAT(no_part_tipemotor,'-'+type_marketing)} IN ((SELECT {fn CONCAT(NO_PART_TIPEMOTOR,'-'+TYPE_MARKETING)} FROM MASTER_PVTM ));
						END;

						INSERT INTO MASTER_PVTM(NO_PART_TIPEMOTOR,TYPE_MARKETING,ROW_STATUS,CREATED_BY) SELECT no_part_tipemotor,type_marketing,row_status,created_by  FROM master_pvtm_".$rand."
					END";
			$this->db->query($query);
			$folderJson = getConfig("UPJSON_L")."\pvtm.json";
			//$folderJson = "\\\\192.168.0.114\\tmp\pvtm.json";
			$query="WITH tmppvtm as  
					(SELECT pvtm.*  FROM OPENROWSET(BULK '".$folderJson."', SINGLE_CLOB) JSON
					CROSS APPLY OPENJSON(BulkColumn) 
					WITH (no_part_tipemotor varchar(50),type_marketing varchar(50),row_status int,created_by varchar(50)) as pvtm)

					INSERT INTO master_pvtm_".$rand." select * from tmppvtm;
					IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[master_pvtm_".$rand."]') AND type in (N'U')) DROP TABLE [dbo].[master_pvtm_".$rand."];";

			
			return $this->db->query($query)->row();
		}
		/**
		 * [queryugm description]
		 * @return [type] [description]
		 */
		function queryugm(){
			$rand=mt_rand(10,1000);
			$query=" IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[master_p_groupmotor_".$rand."]') AND type in (N'U')) DROP TABLE [dbo].[master_p_groupmotor_".$rand."];
					CREATE TABLE master_p_groupmotor_".$rand." (kd_groupmotor varchar(10),nama_groupmotor varchar(150),kd_typemotor varchar(10),category_motor varchar(15),sembilan_segmen varchar(15),series varchar(25),row_status int,created_by varchar(50));";
					// TRUNCATE TABLE MASTER_P_GROUPMOTOR";
			$this->db->query($query);
			$query="CREATE TRIGGER [dbo].[master_p_groupmotor_tmp_$rand] ON  [dbo].[master_p_groupmotor_$rand] AFTER INSERT AS 
					BEGIN
						IF EXISTS(SELECT MS.* FROM MASTER_P_GROUPMOTOR MS INNER JOIN inserted i ON i.kd_groupmotor=MS.KD_GROUPMOTOR)
						BEGIN
						UPDATE MP 
							SET MP.NAMA_GROUPMOTOR=i.nama_groupmotor,
								MP.KD_TYPEMOTOR=i.kd_typemotor,
								MP.CATEGORY_MOTOR=i.category_motor,
								MP.SEMBILAN_SEGMEN=i.sembilan_segmen,
								MP.SERIES=i.series,
								MP.LASTMODIFIED_BY=i.created_by,
								MP.LASTMODIFIED_TIME=GETDATE(),
								MP.ROW_STATUS=0
							FROM dbo.MASTER_P_GROUPMOTOR MP
							INNER JOIN inserted i ON i.kd_groupmotor=MP.KD_GROUPMOTOR;

							DELETE FROM master_p_groupmotor_$rand WHERE kd_groupmotor IN((SELECT KD_GROUPMOTOR FROM MASTER_P_GROUPMOTOR));
						END;
						INSERT INTO MASTER_P_GROUPMOTOR(KD_GROUPMOTOR,NAMA_GROUPMOTOR,KD_TYPEMOTOR,CATEGORY_MOTOR,SEMBILAN_SEGMEN,SERIES,ROW_STATUS,CREATED_BY) 
							SELECT kd_groupmotor,nama_groupmotor,kd_typemotor,category_motor,sembilan_segmen,series,row_status,created_by  
							FROM master_p_groupmotor_".$rand."

					END";
			$this->db->query($query);
			// $folderJson = "c:\\tmp";
			$folderJson = getConfig("UPJSON_L")."\ugm.json";
			$query="WITH tmpugm as  
					(SELECT ugm.*  FROM OPENROWSET(BULK '".$folderJson."', SINGLE_CLOB) JSON
					CROSS APPLY OPENJSON(BulkColumn) 
					WITH (kd_groupmotor varchar(10),nama_groupmotor varchar(150),kd_typemotor varchar(10),category_motor varchar(15),sembilan_segmen varchar(15),series varchar(25),row_status int,created_by varchar(50)) as ugm)

					INSERT INTO master_p_groupmotor_".$rand." select * from tmpugm;
					IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[master_p_groupmotor_".$rand."]') AND type in (N'U')) DROP TABLE [dbo].[master_p_groupmotor_".$rand."];";
			return $this->db->query($query)->row();
		}
		/**
		 * [querypdsim description]
		 * @return [type] [description]
		 */
		function querypdsim(){
			$rand=mt_rand(10,1000);
			$query=" IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[master_sim_parts_".$rand."]') AND type in (N'U')) DROP TABLE [dbo].[master_sim_parts_".$rand."];
					CREATE TABLE master_sim_parts_".$rand." (kategori_ahass varchar(5),part_number varchar(50),jumlah_standaritem_min varchar(10),row_status int,created_by varchar(50));";
					// TRUNCATE TABLE MASTER_SIM_PARTS";
			$this->db->query($query);
			$query="CREATE TRIGGER [dbo].[master_sim_parts_tmp_$rand] ON  [dbo].[master_sim_parts_$rand] AFTER INSERT AS 
					BEGIN
						IF EXISTS(SELECT MS.* FROM MASTER_SIM_PARTS MS INNER JOIN inserted i ON i.part_number=MS.PART_NUMBER AND i.kategori_ahass=MS.KATEGORI_AHASS)
						BEGIN
						UPDATE MP 
							SET MP.JUMLAH_STANDARITEM_MIN=i.jumlah_standaritem_min,
								MP.LASTMODIFIED_BY=i.created_by,
								MP.LASTMODIFIED_TIME=GETDATE(),
								MP.ROW_STATUS=0
							FROM dbo.MASTER_SIM_PARTS MP
							INNER JOIN inserted i ON i.part_number=MP.PART_NUMBER AND i.kategori_ahass=MP.KATEGORI_AHASS;

							DELETE FROM master_sim_parts_$rand WHERE {fn CONCAT(part_number,'-'+kategori_ahass)} IN(
							(SELECT {fn CONCAT(PART_NUMBER,'-'+KATEGORI_AHASS)} FROM MASTER_SIM_PARTS));
						END;
							INSERT INTO MASTER_SIM_PARTS(KATEGORI_AHASS,PART_NUMBER,JUMLAH_STANDARITEM_MIN,ROW_STATUS,CREATED_BY) 
							SELECT kategori_ahass,part_number,jumlah_standaritem_min,row_status,created_by  FROM master_sim_parts_".$rand."
						
					END";
			$this->db->query($query);
			// $folderJson = "c:\\tmp\pdsim.json";
			$folderJson = getConfig("UPJSON_L")."\pdsim.json";
			$query="WITH tmppdsim as  
					(SELECT pdsim.*  FROM OPENROWSET(BULK '".$folderJson."', SINGLE_CLOB) JSON
					CROSS APPLY OPENJSON(BulkColumn) 
					WITH (kategori_ahass varchar(5),part_number varchar(50),jumlah_standaritem_min varchar(10),row_status int,created_by varchar(50)) as pdsim)

					INSERT INTO master_sim_parts_".$rand." select * from tmppdsim;
					IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[master_sim_parts_".$rand."]') AND type in (N'U')) DROP TABLE [dbo].[master_sim_parts_".$rand."];";
			return $this->db->query($query)->row();
		}

		/**
		 * [querysdmj description]
		 * @return [type] [description]
		 */
		function querysdmj(){
			$rand=mt_rand(10,1000);
			$query=" IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[master_jasa_".$rand."]') AND type in (N'U')) DROP TABLE [dbo].[master_jasa_".$rand."];
					CREATE TABLE master_jasa_".$rand." (kategori varchar(100),kd_jasa varchar(10),kd_motor varchar(10),keterangan varchar(max),frt varchar(50),harga varchar(50),row_status int,created_by varchar(50));";
					// TRUNCATE TABLE MASTER_JASA";

			$this->db->query($query);
			$query="CREATE TRIGGER [dbo].[master_jasa_tmp_$rand] ON  [dbo].[master_jasa_$rand] AFTER INSERT AS 
					BEGIN
						IF EXISTS(SELECT MS.* FROM MASTER_JASA MS INNER JOIN inserted i ON i.kd_jasa=MS.KD_JASA AND i.kategori=MS.KATEGORI)
						BEGIN
						UPDATE MP 
							SET 
								MP.KD_MOTOR=i.kd_motor,
								MP.KETERANGAN=i.keterangan,
								MP.FRT=i.frt,
								MP.HARGA=i.harga,
								MP.LASTMODIFIED_BY=i.created_by,
								MP.LASTMODIFIED_TIME=GETDATE(),
								MP.ROW_STATUS=0
							FROM dbo.MASTER_JASA MP
							INNER JOIN inserted i ON i.kd_jasa=MP.KD_JASA AND i.kategori=MP.KATEGORI;
							DELETE FROM master_jasa_$rand WHERE {fn CONCAT(kd_jasa,'-'+kategori)} IN((SELECT {fn CONCAT(KD_JASA,'-'+KATEGORI)} FROM MASTER_JASA));
						END;
							INSERT INTO MASTER_JASA(KATEGORI, KD_JASA, KD_MOTOR, KETERANGAN, FRT, HARGA,ROW_STATUS,CREATED_BY) 
							SELECT kategori,kd_jasa,kd_motor,keterangan,frt,harga,row_status,created_by  FROM master_jasa_".$rand."
						
					END";
			$this->db->query($query);
			// $folderJson = "c:\\tmp\sdmj.json";
			$folderJson = getConfig("UPJSON_L")."\sdmj.json";
			$query="WITH tmpsdmj as  
					(SELECT sdmj.*  FROM OPENROWSET(BULK '".$folderJson."', SINGLE_CLOB) JSON
					CROSS APPLY OPENJSON(BulkColumn) 
					WITH (kategori varchar(100),kd_jasa varchar(10),kd_motor varchar(10),keterangan varchar(max),frt varchar(50),harga varchar(50),row_status int,created_by varchar(50)) as sdmj)

					INSERT INTO master_jasa_".$rand." select * from tmpsdmj;
					IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[master_jasa_".$rand."]') AND type in (N'U')) DROP TABLE [dbo].[master_jasa_".$rand."];";
			return $this->db->query($query)->row();
		}

		/**
		 * [queryptm description]
		 * @return [type] [description]
		 */
		function queryptm(){
			$rand=mt_rand(10,1000);
			$query=" IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[master_ptm_".$rand."]') AND type in (N'U')) DROP TABLE [dbo].[master_ptm_".$rand."];
					CREATE TABLE master_ptm_".$rand." (tipe_produksi varchar(10),type_marketing varchar(10),deskripsi varchar(max),last_effective date,row_status int,created_by varchar(50));";
					// TRUNCATE TABLE MASTER_PTM";
			$this->db->query($query);
			$query="CREATE TRIGGER [dbo].[master_ptm_tmp_$rand] ON  [dbo].[master_ptm_$rand] AFTER INSERT AS 
					BEGIN
						IF EXISTS(SELECT MS.* FROM MASTER_PTM MS 
						INNER JOIN inserted i ON RTRIM(i.tipe_produksi)=RTRIM(MS.TIPE_PRODUKSI) AND RTRIM(i.type_marketing)=RTRIM(MS.TYPE_MARKETING))
						BEGIN
						UPDATE MP 
							SET 
								MP.TIPE_PRODUKSI=RTRIM(i.tipe_produksi),
								MP.TYPE_MARKETING=RTRIM(i.type_marketing),
								MP.DESKRIPSI=i.deskripsi,
								MP.LAST_EFFECTIVE=i.last_effective,
								MP.LASTMODIFIED_BY=i.created_by,
								MP.LASTMODIFIED_TIME=GETDATE(),
								MP.ROW_STATUS=0
							FROM dbo.MASTER_PTM MP
							INNER JOIN inserted i ON i.tipe_produksi=MP.TIPE_PRODUKSI AND i.type_marketing=MP.TYPE_MARKETING;
							DELETE FROM master_ptm_$rand WHERE {fn CONCAT(tipe_produksi,'-'+type_marketing)} IN ((SELECT {fn CONCAT(TIPE_PRODUKSI,'-'+TYPE_MARKETING)} FROM MASTER_PTM));
						END;
						/*ELSE
						BEGIN*/
							INSERT INTO MASTER_PTM(TIPE_PRODUKSI, TYPE_MARKETING, DESKRIPSI, LAST_EFFECTIVE,ROW_STATUS,CREATED_BY,CREATED_TIME) 
							SELECT RTRIM(tipe_produksi),RTRIM(type_marketing),RTRIM(deskripsi),last_effective,row_status,created_by,GETDATE() FROM master_ptm_$rand
						/*END*/
					END";
			$this->db->query($query);
			$folderJson = getConfig("UPJSON_L")."\ptm.json";
			$query="WITH tmpptm as  
					(SELECT ptm.*  FROM OPENROWSET(BULK '".$folderJson."', SINGLE_CLOB) JSON
					CROSS APPLY OPENJSON(BulkColumn) 
					WITH (tipe_produksi varchar(10),type_marketing varchar(10),deskripsi varchar(max),last_effective date,row_status int,created_by varchar(50)) as ptm)

					INSERT INTO master_ptm_".$rand." select * from tmpptm";
			$query .=" IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[master_ptm_".$rand."]') AND type in (N'U')) DROP TABLE [dbo].[master_ptm_".$rand."];";
			return $this->db->query($query)->row();
		}
		/**
		 * [part_stock description]
		 * @param  [type] $param   [description]
		 * @param  [type] $paramSA [khusus untuk saldo awal]
		 * @return [type]          [description]
		 */
		function set_where_custom($param=null){
			$this->where_custom=$param;
		}
		function get_where_custom(){
			return $this->where_custom;
		}
		function part_stock($param=null,$paramSA=Null,$onlyStock=null){
			$where="";$whereSa="";
			if($param){
				foreach ($param as $key => $value) {
					$where .= " AND x.".$key."='".$value."'";
				}
			}
			if($paramSA){
				foreach ($paramSA as $key => $value) {
					$whereSa .= " AND ".$key."='".$value."'";
				}
			}
			$stock_show=($onlyStock)? "AND JUMLAH_SAK >0 ":"";
			$where_lagi="";
			if(isset($this->where_custom)){
				if(is_array($this->get_where_custom())){
					foreach ($this->get_where_custom() as $key => $value) {
						if($key!='customs'){
							$where_lagi .=" AND T.".$key."='".$value."'";
						}else{
							$where_lagi .= $value;
						}
					}
				}else{
					$where_lagi .=$this->get_where_custom();
				}
			}
			$query="WITH TRANSAKSI AS (
						SELECT '0' JENIS_TRANS,KD_MAINDEALER,KD_DEALER,YEAR(CREATED_TIME)TAHUN,MONTH(CREATED_TIME)BULAN,PART_NUMBER,JUMLAH JUMLAH_SAK,NULL HARGA_SAK,ROW_STATUS FROM SETUP_SA_PART
						WHERE ROW_STATUS >=0 $whereSa
						UNION ALL
							SELECT * FROM (
								SELECT '1' JENIS_TRANS,T.KD_MAINDEALER,T.KD_DEALER,YEAR(T.TGL_TRANS)TAHUN,MONTH(T.TGL_TRANS)BULAN,TD.PART_NUMBER,
								SUM(TD.JUMLAH) AS JUMLAH_SAK,TD.HARGA_BELI,TD.ROW_STATUS
								FROM TRANS_PART_TERIMA T
								LEFT JOIN TRANS_PART_TERIMADETAIL TD ON TD.NO_TRANS=T.NO_TRANS
								WHERE TD.ROW_STATUS>-1 
								GROUP BY T.KD_MAINDEALER,T.KD_DEALER,YEAR(T.TGL_TRANS),MONTH(T.TGL_TRANS),TD.PART_NUMBER,TD.HARGA_BELI,TD.ROW_STATUS
							) AS x WHERE ROW_STATUS >-1 $where
						UNION ALL
							SELECT * FROM (
								SELECT '2' TYPE_TRANS,TU.KD_MAINDEALER,TU.KD_DEALER,YEAR(TU.TGL_TRANS) TAHUN,MONTH(TU.TGL_TRANS) BULAN,
								(SELECT dbo.fnSplitColumn(KETERANGAN,2,':'))PART_NUMBER,(JUMLAH*-1)JUMLAH_SAK,HARGA HARGA_SAK,TU.ROW_STATUS
								FROM TRANS_UANGMASUK TU
								LEFT JOIN TRANS_UANGMASUK_DETAIL TUD ON TUD.NO_TRANS=TU.NO_TRANS
								WHERE JENIS_TRANS='Penjualan Sparepart' 
								AND TU.ROW_STATUS>-1 AND TUD.ROW_STATUS>-1
							) AS x WHERE x.ROW_STATUS >-1 $where
					)
					SELECT T.KD_MAINDEALER,T.KD_DEALER,D.NAMA_DEALER,T.PART_NUMBER,P.PART_DESKRIPSI,P.PART_SUPERSEED,
					ISNULL(SUM(JUMLAH_SAK),0)JUMLAH_SAK,
					T.ROW_STATUS 
					FROM TRANSAKSI T
					LEFT JOIN MASTER_PART P ON P.PART_NUMBER=T.PART_NUMBER
					LEFT JOIN MASTER_DEALER D ON D.KD_DEALER=T.KD_DEALER
					WHERE T.ROW_STATUS >=0 $stock_show $where_lagi
					GROUP BY T.KD_MAINDEALER,T.KD_DEALER,D.NAMA_DEALER,T.PART_NUMBER,T.ROW_STATUS,P.PART_DESKRIPSI,P.PART_SUPERSEED
					ORDER BY T.PART_NUMBER";
			//return $query;
			//echo $query;
			return $this->part_last_stock($where);
		}
		function part_last_stock($param=null){
			$query="SELECT * FROM (
					SELECT KD_DEALER,P.PART_NUMBER,P.PART_DESKRIPSI,SUM(STOCK) JUMLAH_SAK
					FROM TRANS_PART_MOVEMENT TP
					LEFT JOIN MASTER_PART P ON P.PART_NUMBER=TP.PART_NUMBER
					WHERE JENIS_MOVE NOT IN('Mutasi') 
					GROUP BY KD_DEALER,P.PART_NUMBER,PART_DESKRIPSI
				) AS x WHERE JUMLAH_SAK>0 ".$param;
			return $query;
		}
		function part_stocked($kd_dealer,$tipe_data='ALL',$tanggal=null){
			$rand=strtoupper($kd_dealer."_".substr(str_replace('-','',$this->get_logines()),0,10));
			$tgl = ($tanggal)?$tanggal:date('Ymd');
			$query="IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[STOCK_VIEWS_$rand]') AND type in (N'U')) DROP TABLE [dbo].[STOCK_VIEWS_$rand];
					CREATE TABLE STOCK_VIEWS_$rand (ID INT,KD_DEALER VARCHAR(5),PART_NUMBER VARCHAR(30),KD_LOKASI VARCHAR(10),KD_GUDANG VARCHAR(10),KD_RAKBIN VARCHAR(10),JUMLAH DECIMAL(18,2), JUMLAH_SAK DECIMAL(10,2),HARGA_BELI DECIMAL(18,6), HARGA_JUAL DECIMAL(18,6),KETERANGAN VARCHAR(MAX),ROW_STATUS INT,PART_DESKRIPSI VARCHAR(250),KD_GROUPSALES VARCHAR(10));

					INSERT INTO STOCK_VIEWS_$rand EXEC SP_WAREHOUSE_STOCK '".$kd_dealer."','".$tipe_data."','".$tgl."'";

			return $query;
		}
		function deltmp_table($kd_dealer=null){
			$rand=strtoupper($kd_dealer."_".substr($this->get_logines(),0,10));
			$query="IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[STOCK_VIEWS_$rand]') AND type in (N'U')) 
			DROP TABLE [dbo].[STOCK_VIEWS_$rand];";
			return $query;
		}
		function dellead_table(){
			$rand=substr($this->get_logines(),0,10);
			$query="IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[PART_LEADTIME_$rand]') AND type in (N'U')) 
			DROP TABLE [dbo].[PART_LEADTIME_$rand];";
			return $query;
		}
		function dellots_table(){
			$rand=substr($this->get_logines(),0,10);
			$query="IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[PART_OTS_$rand]') AND type in (N'U')) 
			DROP TABLE [dbo].[PART_OTS_$rand];";
			return $query;
		}
		/**
		 * [barang_stock description]
		 * Depreciated on 19-03-2018 - not match with requirement
		 * @param  [type] $param   [description]
		 * @param  [type] $paramSA [description]
		 * @return [type]          [description]
		 */
		function barang_stock($param=null,$paramSA=Null){
			$where=""; $whereSa="";
			if($param){
				foreach ($param as $key => $value) {
					$where .= " AND x.".$key."='".$value."'";
				}
			}
			if($paramSA){
				foreach ($paramSA as $key => $value) {
					$whereSa .= " AND ".$key."='".$value."'";
				}
			}
			$query="WITH B_TERIMA AS (
						SELECT * FROM (
							SELECT '1' TYPE_TRANS,(SELECT dbo.fnSplitColumn(KETERANGAN,2,':'))PART_NUMBER,JUMLAH,HARGA,TU.ROW_STATUS,TU.KD_DEALER,TU.KD_MAINDEALER,
							YEAR(TU.TGL_TRANS) TAHUN,MONTH(TU.TGL_TRANS) BULAN
							FROM TRANS_UANGMASUK TU
							LEFT JOIN TRANS_UANGMASUK_DETAIL TUD on TUD.NO_TRANS=TU.NO_TRANS
							WHERE JENIS_TRANS IN('Penerimaan Barang')
							AND TUD.ROW_STATUS >-1 AND TU.ROW_STATUS>-1 
						) AS x WHERE x.ROW_STATUS>-1 $where
					),
					B_JUAL AS (
						SELECT * FROM (
							SELECT '2' TYPE_TRANS,(SELECT dbo.fnSplitColumn(KETERANGAN,2,':'))PART_NUMBER,(JUMLAH*-1)JUMLAH,HARGA,TU.ROW_STATUS,TU.KD_DEALER,TU.KD_MAINDEALER,
							YEAR(TU.TGL_TRANS) TAHUN,MONTH(TU.TGL_TRANS) BULAN
							FROM TRANS_UANGMASUK tu
							LEFT JOIN TRANS_UANGMASUK_DETAIL TUD on TUD.NO_TRANS=TU.NO_TRANS
							WHERE JENIS_TRANS IN('Pengeluaran Barang','Penjualan Apparel','Penjualan Aksesoris')
							AND TUD.ROW_STATUS >-1 AND TU.ROW_STATUS>-1
						) AS x WHERE x.ROW_STATUS>-1 $where
					)
					,B_SALDO_AWAL AS (
						SELECT '0' TYPE_TRANS,PART_NUMBER,JUMLAH_SAK,HARGA_SAK,ROW_STATUS,KD_DEALER,KD_MAINDEALER,TAHUN,BULAN
						FROM TRANS_PART_SALDO
						WHERE ROW_STATUS >-1 AND KATEGORI='Barang' $whereSa
					)
					,B_JOIN AS
					(
						SELECT * FROM B_TERIMA UNION ALL SELECT * FROM B_JUAL UNION ALL SELECT * FROM B_SALDO_AWAL
					)
					,STOCK AS (
					SELECT B_JOIN.*,MB.NAMA_BARANG FROM B_JOIN
					LEFT JOIN MASTER_BARANG MB ON MB.KD_BARANG=B_JOIN.PART_NUMBER
					)
					
					SELECT KD_MAINDEALER,KD_DEALER, PART_NUMBER,NAMA_BARANG AS PART_DESKRIPSI,SUM(JUMLAH) AS JUMLAH_SAK
					/*,HARGA AS HARGA_SAK,
					CONCAT(PART_NUMBER,(SELECT dbo.fnPadLeft(CAST(REPLACE(HARGA,'.00000','') AS CHAR ),10))) BATCH*/
					FROM STOCK
					WHERE (LEN(PART_NUMBER)>0)
					GROUP BY PART_NUMBER,NAMA_BARANG,KD_MAINDEALER,KD_DEALER";
			return $query;
		}
		/**
		 * [stock_barang description]
		 * @param  [array] $param [parameter yng di perlukan]
		 * @return [string]        [quer]
		 */
		function stock_barang($param=null,$transaksi=null){
			$query="";
			$kd_dealer=null;$jenis_trans=null; 
			$f_date=null;$s_date=null;$kd_lokasi=null;
			if($param){
				$kd_dealer = $param["KD_DEALER"];
				$jenis_trans = isset($param["JENIS_TRANS"])?$param["JENIS_TRANS"]:"0";
				$f_date = isset($param["DARI_TANGGAL"])?$param["DARI_TANGGAL"]:'20190101';
				$s_date = isset($param["SAMPAI_TANGGAL"])?$param["SAMPAI_TANGGAL"]:date('Ymd');;
				$kd_lokasi = isset($param["KD_LOKASI"])?$param["KD_LOKASI"]:null;
				$detail = isset($param["DETAIL"])?$param["DETAIL"]:"0";
			}
			$sufix =($transaksi)?"TRANS":"STOCK";
			$jenis_trans =($sufix=='TRANS')?($jenis_trans)."','".$detail:$jenis_trans;
			if($f_date){
				$query="EXEC SP_WH_BARANG_$sufix '".trim($kd_dealer)."','".$jenis_trans."','".$f_date."','".$s_date."'";
			}elseif($kd_lokasi){
				$query="EXEC SP_WH_BARANG_$sufix '".trim($kd_dealer)."','".$jenis_trans."','".$f_date."','".$s_date."','".$kd_lokasi."'";
			}
			//echo $query;exit();
			return $query;
		}
		/**
		 * [barang_hargabeli description]
		 * @param  [type] $param [description]
		 * @param  [type] $list  [description]
		 * @return [type]        [description]
		 */
		function barang_hargabeli($param=null,$list=null){
			$where=""; $whereSa=""; $querys="";
			if($param){
				foreach ($param as $key => $value) {
					$where .= " AND ".$key."='".$value."'";
				}
			}
			$top_1=($list)?"" :"TOP 1 ";
			$order_by =($list)?"ORDER BY ID" :" ORDER BY DID DESC";
			$querys="WITH B_TERIMA AS (
					SELECT TU.ID,TUD.ID AS DID, '1' TYPE_TRANS,(SELECT dbo.fnSplitColumn(KETERANGAN,2,':'))PART_NUMBER,JUMLAH,HARGA HARGA_BELI,HARGA HARGA_JUAL,TU.ROW_STATUS,TU.KD_DEALER,TU.KD_MAINDEALER,
					YEAR(TU.TGL_TRANS) TAHUN,MONTH(TU.TGL_TRANS) BULAN
					FROM TRANS_UANGMASUK TU
					LEFT JOIN TRANS_UANGMASUK_DETAIL TUD on TUD.NO_TRANS=TU.NO_TRANS
					WHERE JENIS_TRANS IN('Penerimaan Barang')
					AND TUD.ROW_STATUS >=0 AND TU.ROW_STATUS>=0
					)
					SELECT $top_1 * FROM B_TERIMA 
					WHERE (LEN(PART_NUMBER)>0) $where $order_by";
			return $querys;
		}
		function barang_in_dounit($param){
			$query="";
			$kd_dealer=null;$jenis_trans=null; 
			$no_trans=null;$kd_lokasi=null;
			if($param){
				$kd_dealer = $param["KD_DEALER"];
				$kd_lokasi = isset($param["KD_LOKASI"])?$param["KD_LOKASI"]:null;
				$jenis_trans = isset($param["DETAIL"])?$param["DETAIL"]:null;
				$no_trans = isset($param["NO_TRANS"])?$param["NO_TRANS"]:null;
			}
			$query=($jenis_trans)?
				"EXEC SP_WH_BARANG_DOUNIT '".trim($kd_dealer)."','".$kd_lokasi."','".$jenis_trans."','".$no_trans."'":
				"EXEC SP_WH_BARANG_DOUNIT '".trim($kd_dealer)."','".$kd_lokasi."'";
			return $query;
		}
		/**
		 * [laporankasharian description]
		 * @param  [type] $param [description]
		 * @param  [type] $list  [description]
		 * @return [type]        [description]
		 */
		function laporankasharian($param=null,$wherex=null){
			$where=""; $whereSa=""; $querys="";
			if($param){
				foreach ($param as $key => $value) {
					$where .= " AND ".$key."='".$value."'";
				}
			}
			$query="WITH SALDO_AWAL AS(
					SELECT TRANS,KD_MAINDEALER,KD_DEALER,TGL_TRANS,NO_TRANS,JENIS_TRANS,URAIAN_TRANSAKSI,JUMLAH,HARGA,KREDIT,DEBET,KD_AKUN FROM (
					SELECT 1 TRANS, KD_MAINDEALER,KD_DEALER,
					CONVERT(CHAR,OPEN_DATE,112) AS TGL_TRANS,KD_TRANS,
					CONCAT(LEFT(KD_TRANS,2),KD_DEALER,YEAR(OPEN_DATE),(SELECT dbo.fnPadLeft(MONTH(OPEN_DATE),2)),'-',(SELECT dbo.fnPadLeft(ID,6)))AS NO_TRANS,
					'Penerimaan Kas'JENIS_TRANS,'Saldo Awal' AS URAIAN_TRANSAKSI,1 JUMLAH,SALDO_AWAL HARGA,SALDO_AWAL AS KREDIT, 0 AS DEBET,'100.11100' AS KD_AKUN 
					FROM TRANS_KASIR) AS x
					WHERE KD_TRANS='KSR' $where
					)
					,TRANS_MASUK AS(
					SELECT 2 TRANS, KD_MAINDEALER,KD_DEALER,CONVERT(CHAR,TU.TGL_TRANS,112)TGL_TRANS,TU.NO_TRANS,JENIS_TRANS,URAIAN_TRANSAKSI,JUMLAH,HARGA,(JUMLAH*HARGA)KREDIT,0 DEBET,KD_ACCOUNT
					FROM TRANS_UANGMASUK_DETAIL TUD
					LEFT JOIN TRANS_UANGMASUK TU ON TU.NO_TRANS=TUD.NO_TRANS
					WHERE TU.TYPE_TRANS='Penerimaan' 
					AND TUD.ROW_STATUS>=0 AND TU.ROW_STATUS>=0 $where
					
					)
					,TRANS_KELUAR AS(
					SELECT 3 TRANS, KD_MAINDEALER,KD_DEALER,CONVERT(CHAR,TU.TGL_TRANS,112)TGL_TRANS,TU.NO_TRANS,JENIS_TRANS,URAIAN_TRANSAKSI,JUMLAH,HARGA,0 KREDIT,(JUMLAH*HARGA) DEBET,KD_ACCOUNT
					FROM TRANS_UANGMASUK_DETAIL TUD
					LEFT JOIN TRANS_UANGMASUK TU ON TU.NO_TRANS=TUD.NO_TRANS
					WHERE TU.TYPE_TRANS='Pengeluaran' 
					AND TUD.ROW_STATUS>=0 AND TU.ROW_STATUS>=0 $where
					/*AND KD_DEALER='K7D'AND CONVERT(CHAR,TGL_TRANS,112)='20180312'*/
					)
					,TRANS_GABUNGAN AS (
					SELECT * FROM SALDO_AWAL UNION ALL SELECT * FROM TRANS_MASUK UNION ALL SELECT * FROM TRANS_KELUAR
					)
					SELECT G.*,A.NAMA_AKUN,A.TIPE,A.DEFAULT_AC FROM TRANS_GABUNGAN G
					LEFT JOIN MASTER_ACC_KODEAKUN_V A ON A.KD_AKUN=G.KD_AKUN
					WHERE ROW_STATUS>-1 /*AND TGL_TRANS BETWEEN '20180410' AND '20180412'*/ $wherex
					ORDER BY  CASE WHEN G.TRANS=1 THEN G.TRANS ELSE CAST(RIGHT(G.NO_TRANS,5) AS INT) END";

			return $query;
		}
		/**
		 * [lkh description]
		 * @param  [type]  $kd_dealer [description]
		 * @param  [type]  $startDate [description]
		 * @param  [type]  $endDate   [description]
		 * @param  integer $saldoAwal [description]
		 * @return [type]             [description]
		 */
		function lkh($kd_dealer,$startDate,$endDate,$saldoAwal=0){
			$query="EXEC SP_TRANS_UANGMASUK_VIEW '".$kd_dealer."','".$startDate."','".$endDate."','".$saldoAwal."'";
			return $query;
		}
		/**
		 * [rankparts description]
		 * @param  [type] $kd_dealer     [description]
		 * @param  string $rpt           [description]
		 * @param  [type] $date          [description]
		 * @param  string $kd_maindealer [description]
		 * @return [type]                [description]
		 */
		function rankparts($kd_dealer,$rpt='RP',$date=null,$kd_maindealer='T10'){
			$date=($date)?$date:date('Ymd',strtotime('-1 Days'));
			$query="EXEC SP_TRANS_RANKPARTS '".$kd_dealer."','".$kd_maindealer."','".$date."','".$rpt."'";
			return ($query);
		}
		/**
		 * [wp_revenue description]
		 * @param  [type] $kd_dealer [description]
		 * @param  [type] $tahun     [description]
		 * @param  [type] $bulan     [description]
		 * @return [type]            [description]
		 */
		function wp_revenue($kd_dealer,$tahun,$bulan){
			$tahun =($tahun)?$tahun:date("Y");
			$bulan =($bulan)?$bulan:date("m");
			$query="EXEC SP_WORKSHOP_REVENUE '".$kd_dealer."','".$tahun."','".$bulan."'";
			return ($query);
		}
		/**
		 * [wp_unitentry description]
		 * @param  [type] $kd_dealer [description]
		 * @param  [type] $tahun     [description]
		 * @param  [type] $bulan     [description]
		 * @return [type]            [description]
		 */
		function wp_unitentry($kd_dealer,$tahun,$bulan){
			$tahun =($tahun)?$tahun:date("Y");
			$bulan =($bulan)?$bulan:date("m");
			$query="EXEC SP_WORKSHOP_SERVICE '".$kd_dealer."','".$tahun."','".$bulan."'";
			return ($query);
		}
		/**
		 * [wp_recaptahun description]
		 * @param  [type] $kd_dealer [description]
		 * @param  [type] $tahun     [description]
		 * @param  [type] $bulan     [description]
		 * @return [type]            [description]
		 */
		function wp_recaptahun($kd_dealer,$tahun,$bulan){
			$tahun =($tahun)?$tahun:date("Y");
			$bulan =($bulan)?$bulan:date("m");
			$query="EXEC SP_WORKSHOP_REKAP '".$kd_dealer."','".$tahun."','".$bulan."'";
			return ($query);
		}
		/**
		 * [wp_sdlbbfiles description]
		 * @param  [type] $kd_dealer [description]
		 * @param  [type] $tahun     [description]
		 * @param  [type] $bulan     [description]
		 * @param  string $jam_kerja [description]
		 * @return [type]            [description]
		 */
		function wp_sdlbbfiles($kd_dealer,$tahun,$bulan,$jam_kerja='26'){
			$tahun =($tahun)?$tahun:date("Y");
			$bulan =($bulan)?$bulan:date("m");
			$query="EXEC SP_WORKSHOP_SDLBB '".$kd_dealer."','".$tahun."','".$bulan."','".$jam_kerja."'";
			return ($query);
		}
		/**
		 * [wp_rekapmekanik description]
		 * @param  [type] $kd_dealer [description]
		 * @param  [type] $tahun     [description]
		 * @param  [type] $bulan     [description]
		 * @param  string $jam_kerja [description]
		 * @return [type]            [description]
		 */
		function wp_rekapmekanik($kd_dealer,$tahun,$bulan,$jam_kerja='26'){
			$tahun =($tahun)?$tahun:date("Y");
			$bulan =($bulan)?$bulan:date("m");
			$query="EXEC SP_WORKSHOP_MEKANIK '".$kd_dealer."','".$tahun."','".$bulan."','".$jam_kerja."'";
			return ($query);
		}
		/**
		 * [wp_biayaopr description]
		 * @param  [type] $kd_dealer [description]
		 * @param  [type] $tahun     [description]
		 * @param  [type] $bulan     [description]
		 * @return [type]            [description]
		 */
		function wp_biayaopr($kd_dealer,$tahun,$bulan){
			$tahun =($tahun)?$tahun:date("Y");
			$bulan =($bulan)?$bulan:date("m");
			$query="EXEC SP_WORKSHOP_BIAYA '".$kd_dealer."','".$tahun."','".$bulan."'";
			return ($query);
		}
		function wp_customer($kd_dealer,$tahun,$bulan){
			$tahun =($tahun)?$tahun:date("Y");
			$bulan =($bulan)?$bulan:date("m");
			$query="EXEC SP_WORKSHOP_CUSTOMER '".$kd_dealer."','".$tahun."','".$bulan."'";
			return ($query);
		}
		function check_desa($json=null){
			$folder=getConfig("UPJSON_L");
			$query="DECLARE @JSON NVARCHAR(MAX)
					SELECT @JSON = BulkColumn
					FROM OPENROWSET (BULK 'C:\Program Files\Microsoft SQL Server\MSSQL14.MSSQLSERVER\MSSQL\Backup\desa.json', SINGLE_CLOB) as j

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
		/**
		 * [simpan_desa description]
		 * @return [type] [description]
		 */
		function simpan_desa(){
			$folder=getConfig("UPJSON_C");
			$query="DECLARE @JSON NVARCHAR(MAX)
					SELECT @JSON = BulkColumn
					FROM OPENROWSET (BULK 'C:\Program Files\Microsoft SQL Server\MSSQL14.MSSQLSERVER\MSSQL\Backup\desa.json', SINGLE_CLOB) as j
					MERGE INTO MASTER_DESA AS D
					USING (
						SELECT * FROM OPENJSON(@JSON)
							WITH(kdkota varchar(10),kdkec varchar(10),kdKel varchar(10),propinsi varchar(20),
								    kota varchar(150),kecamatan varchar(150),kelurahan varchar(150),kodePos varchar(5),
									status varchar(1),kdkelahm varchar(20),kelurahanahm varchar(100))
									) AS JD
							ON ((LTRIM(RTRIM(D.KD_DESA))=LTRIM(RTRIM(JD.kdKel))) 
								AND LTRIM(RTRIM(D.NAMA_DESA))=(LTRIM(RTRIM(kelurahan))))
						WHEN MATCHED THEN
							UPDATE SET 
								D.KD_DESA=JD.kdKel,
								D.KD_KOTA=JD.kdkota,
								D.KD_KECAMATAN=JD.kdkec,
								D.NAMA_PROPINSI=JD.propinsi,
								D.NAMA_KOTA=JD.kota,
								D.NAMA_KECAMATAN=JD.kecamatan,
								D.NAMA_DESA=JD.kelurahan,
								D.KODE_POS=JD.kodepos,
								D.KD_DESAAHM=JD.kdkelahm,
								D.DESAAHM=JD.kelurahanahm,
								D.STATUS=JD.status,
								D.ROW_STATUS=0,
								D.LASTMODIFIED_BY='|ws.list15',
								D.LASTMODIFIED_TIME=GETDATE()
						WHEN NOT MATCHED THEN
							INSERT (KD_DESA,KD_KOTA,KD_KECAMATAN,NAMA_PROPINSI,NAMA_KOTA,NAMA_KECAMATAN,NAMA_DESA,KODE_POS,KD_DESAAHM,DESAAHM,STATUS,ROW_STATUS,CREATED_BY,CREATED_TIME)
							VALUES(JD.kdKel,JD.kdkota,JD.kdkec,JD.propinsi,JD.kota,JD.kecamatan,JD.kelurahan,JD.kodepos,JD.kdkelahm,JD.kelurahanahm,JD.status,0,'u|ws.list15',GETDATE());";
			return $this->db->query($query)->row();
		}
		function simpan_kupon(){
			
		}
		/**
		 * [paybill_bpkb description]
		 * @param  integer $stnk_id [description]
		 * @return [type]           [description]
		 */
		function paybill_bpkb($stnk_id=0,$pinjam='1'){
			$query ="SELECT ROW_NUMBER() OVER(ORDER BY JENIS_BIAYA) AS ID,STNK_ID,JENIS_BIAYA,
					COUNT(CASE WHEN BIAYA>0 THEN NO_RANGKA END) UNIT,SUM(BIAYA) BIAYA
					FROM (
						SELECT U.STNK_ID,U.NO_RANGKA,U.NAMA_PEMILIK,U.JENIS_BIAYA,U.BIAYA
						FROM (
							SELECT STNK_ID,NO_RANGKA,NAMA_PEMILIK,
								CASE WHEN REQ_STCK = $pinjam THEN STCK ELSE 0 END STCK,
								CASE WHEN REQ_PLAT_ASLI = $pinjam THEN PLAT_ASLI ELSE 0 END PLAT_ASLI,
								CASE WHEN REQ_ADMIN_SAMSAT = $pinjam THEN ADMIN_SAMSAT ELSE 0 END ADMIN_SAMSAT,
								CASE WHEN REQ_BPKB = $pinjam THEN BPKB ELSE 0 END BPKB,ROW_STATUS 
							FROM TRANS_STNK_DETAIL 
							WHERE ROW_STATUS >=0
						) AS S
						UNPIVOT(BIAYA FOR JENIS_BIAYA IN(STCK,PLAT_ASLI,ADMIN_SAMSAT,BPKB)
						) AS U WHERE STNK_ID=".$stnk_id." AND ROW_STATUS >=0
					) AS X
					GROUP BY JENIS_BIAYA,STNK_ID";
			return $query;
		}
		/**
		 * [mutasi_history description]
		 * @param  [type] $no_rangka [description]
		 * @param  [type] $kd_dealer [description]
		 * @return [type]            [description]
		 */
		function mutasi_history($no_rangka,$kd_dealer){
			$query="EXEC SP_TRANS_INV_MUTASI_HISTORY '".$no_rangka."','".$kd_dealer."'";
			return $query;
		}

		
		function mt_metodefu($kd_dealer,$tgl_awal,$tgl_akhir,$jenis_kpb,$metode){
			if($metode == 'metode1'){
				$query="EXEC SP_REMINDER_BOOKING_METODE1 '".$kd_dealer."','".$tgl_awal."','".$tgl_akhir."','".$jenis_kpb."'";
			}
			else{
				$query="EXEC SP_REMINDER_BOOKING_METODE2 '".$kd_dealer."','".$tgl_awal."','".$tgl_akhir."','".$jenis_kpb."'";
			}
			return $query;
		}

		function mt_metode($kd_dealer,$tgl_awal,$tgl_akhir,$gb_source){
			
			$query="EXEC SP_TRANS_GUESTBOOK_WEEKLYCOUNT '".$kd_dealer."','".$tgl_awal."','".$tgl_akhir."','".$gb_source."'";
			
			return $query;
		}


		function wk_crm($kd_dealer,$tgl_awal,$tgl_akhir){
			
			$query="EXEC SP_TRANS_CRM_WEEKLYCOUNT '".$kd_dealer."','".$tgl_awal."','".$tgl_akhir."'";
			
			return $query;
		}

		function part_leadtime($kd_dealer,$tahun,$bulan,$so=null){
			$rand=substr($this->get_logines(),0,10);
			$tahun =($tahun)?$tahun:date("Y");
			$bulan =($bulan)?$bulan:date("m");
			$query="IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[PART_LEADTIME_$rand]') AND type in (N'U')) 
					DROP TABLE [dbo].[PART_LEADTIME_$rand];
					CREATE TABLE PART_LEADTIME_$rand (URUTAN INT,NO_PO VARCHAR(30),PART_NUMBER VARCHAR(30),TGL_PO DATETIME,
					TGL_TERIMA DATETIME, LEADTIME INT,ROW_STATUS INT);";
			$query .=($so==true)?
					"INSERT INTO PART_LEADTIME_$rand EXEC SP_LEADTIME_PARTSO '".$kd_dealer."','".$tahun."','".$bulan."'":
					"INSERT INTO PART_LEADTIME_$rand EXEC SP_LEADTIME_PART '".$kd_dealer."','".$tahun."','".$bulan."'";
			return ($query);
		}
		function part_outstanding($kd_dealer,$tahun,$bulan,$so=null){
			$rand=substr($this->get_logines(),0,10);
			$tahun =($tahun)?$tahun:date("Y");
			$bulan =($bulan)?$bulan:date("m");
			$query="IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[PART_OTS_$rand]') AND type in (N'U')) 
					DROP TABLE [dbo].[PART_OTS_$rand];
					CREATE TABLE PART_OTS_$rand (TIPE_RPT VARCHAR(2),KD_DEALER VARCHAR(5),PART_NUMBER VARCHAR(30),PART_DESKRIPSI VARCHAR(225),
					JUMLAH_ORDER DECIMAL(18,2),JUMLAH_SUPPLY DECIMAL(18,2),SISA DECIMAL(18,2),ROW_STATUS INT);
					INSERT INTO PART_OTS_$rand EXEC SP_PART_OUTSTANDING '".$kd_dealer."','".$tahun."','".$bulan."'";
			return ($query);
		}

		/**
		 * [jurnal_unit description]
		 * @param  [type] $no_spk [description]
		 * @return [type]         [description]
		 */
		function jurnal_unit($no_spk){
		
			$query="EXEC SP_JURNAL_PENJUALAN_UNIT '".$no_spk."'";
			return ($query);
		}

		function jurnal_bpkb($kd_dealer,$no_trans,$kd_trans){
		
			$query="EXEC SP_JURNAL_STNKBPKB '".$kd_dealer."','".$no_trans."','".$kd_trans."'";
			return ($query);
		}
		function report_lkh($kd_dealer,$output,$tgl_trans){
			$query="EXEC SP_TRANS_UANGMASUK_LKH '".$kd_dealer."','".$output."','".$tgl_trans."'";
			return ($query);
		}

		function approvale_ds($id,$tp,$apvby,$apv_level='1'){
			$query=($tp=='SPK')?
				   "SELECT * FROM TRANS_SPK WHERE ID=$id; 
				   UPDATE TRANS_SPK SET STATUS_SPK=".$apv_level.",LASTMODIFIED_BY='".$apvby."',LASTMODIFIED_TIME=GETDATE() WHERE ID=$id; 
				   UPDATE TRANS_SPK_LEASING SET HASIL='Approve' WHERE SPK_ID=$id":
				   "SELECT * FROM TRANS_INV_MUTASI WHERE NO_TRANS='".$id."'; 
				   UPDATE TRANS_INV_MUTASI SET APPROVAL_STATUS='".$apv_level."',APPROVAL_BY='".$apvby."',APPROVAL_DATE=GETDATE() WHERE NO_TRANS='".$id."'";
			
			return $query;
		
		}
		function salesprogram($kd_dealer,$kd_leasing=null,$kd_typemotor=null){
			$query = "EXEC SP_SETUP_SALESPROGRAM '".$kd_dealer."','".$kd_leasing."','".$kd_typemotor."'";
			// echo $query;exit();
			return $query;
		}
		function trans_pkb_oli($kd_dealer,$mode=0,$part_number=null,$kd_typemotor=null){
			$query="";
			switch ($mode) {
				case 2:
				case 3:
				case 4:
					$query = "EXEC SP_TRANS_PKB_OLI '".$kd_dealer."','".$mode."','".$part_number."','".$kd_typemotor."'";
					break;
				case 1:
					$query = "EXEC SP_TRANS_PKB_OLI '".$kd_dealer."','".$mode."','".$part_number."'";
					break;
				default:
					$query = "EXEC SP_TRANS_PKB_OLI '".$kd_dealer."'";
					break;
			}
			return $query;
		}
		function batal_spk($no_spk,$kd_dealer){
			$query =" EXEC SP_TRANS_SPK_HISTORY '".$no_spk."','".$kd_dealer."'";
			//var_dump($query);
			return $query;
		}


		function range_seri($kd_dealer, $range_seri){
			$query =" EXEC SP_MASTER_RANGEPAJAK '".$kd_dealer."','".$range_seri."'";
			//var_dump($query);
			return $query;
		}
		function att_mekanik($kd_dealer,$start_date,$end_date=null){
			$end_date=($end_date)?$end_date:date('Ymd');
			$query = "EXEC SP_WORKSHOP_MEKANIK '".$kd_dealer."','".$start_date."','".$end_date."'";
			return $query;
		}
		function fee_penjualan($kd_dealer,$groups){
			$query = "EXEC SP_TRANS_SPK_FEE '".$kd_dealer."','".$groups."'";
			return $query;
		}
		function customer_search($keywords,$used_for=null,$limit=null){
			$query ="SELECT * FROM (
					SELECT DISTINCT KD_CUSTOMER,NAMA_CUSTOMER,ALAMAT_SURAT,KD_PROPINSI,KD_KABUPATEN,KD_KECAMATAN,KD_DESA,KODE_POS,NO_MESIN
					FROM TRANS_PARTSO_CUSTOMER_V WHERE CHARINDEX('".$keywords."',NAMA_CUSTOMER) >0
					) AS x WHERE LEN(ALAMAT_SURAT)>8
					ORDER BY NAMA_CUSTOMER ";
			$query .=($limit)?" OFFSET 0 ROWS FETCH NEXT $limit ROWS ONLY":"" ;
			return $query;
		}
		function mek_att($kd_dealer,$start_date,$end_date){
			$query ="SELECT t.NIK, k.NAMA, k.PERSONAL_JABATAN, k.HONDA_ID, count(case when t.STATUS_KARYAWAN = 'M' then 1 end) 'KEHADIRAN', count(case when t.STATUS_KARYAWAN = 'A' then 1
						when t.STATUS_KARYAWAN = 'I' then 1 
						when t.STATUS_KARYAWAN = 'S' then 1 end) 'KETIDAKHADIRAN'
						from TRANS_ABSENSI_MEKANIK t, MASTER_KARYAWAN k
						where t.nik = k.nik and t.tanggal between '".$start_date."' and '".$end_date."' and t.kd_dealer IN('".$kd_dealer."')
						group by t.NIK, k.NAMA, k.PERSONAL_JABATAN, k.HONDA_ID
						order by k.NAMA asc";
			return $query;
		}

		function terima_bank($kd_dealer){
			$rand=strtoupper($kd_dealer."_".substr($this->get_logines(),0,10));
			$query ="CREATE OR ALTER VIEW [dbo].[TRANS_TMB_$rand]
					AS
					SELECT ID, IDS, NO_TRANS, KD_MAINDEALER, KD_DEALER, KD_BANK, KD_AKUN, TGL_TRANS, KD_TRANS, KETERANGAN, TIPE_TRANS, SALDO_AWAL, DEBET, KREDIT, SALDO_AKHIR, ROW_STATUS
					FROM  OPENQUERY(local, 'exec SIMANDE.dbo.SP_TRANS_TERIMA_BANK_V ''".$kd_dealer."''') AS derivedtbl_1";
					//$query="EXEC SP_TRANS_TERIMA_BANK_V '".$kd_dealer."'";
			return $query;
		}
		function trans_bank($kd_dealer,$frD=null,$tod=null,$kd_bank=null){
			//$order_by = $this->Main_model->get_orderby();
			$query="WITH TRANS_BANK AS (
						SELECT NO_TRANS,KD_MAINDEALER,KD_DEALER,KD_BANK,KD_AKUN,TGL_TRANS,KD_TRANS,KETERANGAN,TIPE_TRANS,DEBET,KREDIT,JUMLAH,ROW_STATUS
						FROM TRANS_TERIMABANK WHERE ROW_STATUS >=0
						AND KD_DEALER='".$kd_dealer."'
						/*AND KD_BANK=$kd_bank*/
						AND CONVERT(CHAR,TGL_TRANS,112) BETWEEN '".$frD."' AND '".$tod."'
					)
					,SA_AKUN AS (
						SELECT KD_AKUN NO_TRANS,'T10' KD_MAINDEALER,KD_DEALER,(SELECT KD_BANK FROM MASTER_BANK M WHERE M.KD_AKUN=SA.KD_AKUN AND M.KD_DEALER=SA.KD_DEALER AND ROW_STATUS>=0) KD_BANK,
						KD_AKUN,ISNULL(TGL_SALDO,GETDATE()) TGL_TRANS,'00' AS KD_TRANS,'SALDO AWAL PERKIRAAN' AS KETERANGAN,'D' AS TIPE_TRANS,
						SALDO_AWAL DEBET,0 KREDIT,SALDO_AWAL AS JUMLAH,ROW_STATUS
						FROM TRANS_ACC_SALDOAWAL SA 
						WHERE KD_DEALER='".$kd_dealer."' AND ROW_STATUS >=0 
						AND KD_AKUN IN(SELECT KD_AKUN FROM TRANS_BANK)
					)
					,GABUNGAN AS (
						SELECT '1' ID, * FROM SA_AKUN 
						UNION ALL
						SELECT '2' ID, * FROM TRANS_BANK
					)
					,GABUNGAN_2 AS (
						SELECT ID,NO_TRANS,KD_MAINDEALER,KD_DEALER,KD_BANK,KD_AKUN,TGL_TRANS,KD_TRANS,KETERANGAN,
						TIPE_TRANS,DEBET,KREDIT,
						SUM (DEBET-KREDIT) OVER(PARTITION BY KD_BANK ORDER BY ID,NO_TRANS,TGL_TRANS ROWS BETWEEN 
						UNBOUNDED PRECEDING AND CURRENT ROW) AS SALDO_AKHIR
						FROM GABUNGAN
					)
					SELECT ID,NO_TRANS,KD_MAINDEALER,KD_DEALER,KD_BANK,KD_AKUN,TGL_TRANS,KD_TRANS,KETERANGAN,TIPE_TRANS,
					ISNULL(CASE WHEN ID=1 THEN DEBET ELSE (LAG(SALDO_AKHIR) OVER(PARTITION BY KD_BANK 
					ORDER BY ID,NO_TRANS,TGL_TRANS)) END,0) SALDO_AWAL,CASE WHEN ID=1 THEN 0 ELSE DEBET END DEBET,
					KREDIT,SALDO_AKHIR FROM GABUNGAN_2 
					ORDER BY KD_BANK,ID,TGL_TRANS
					OFFSET 0 ROWS FETCH NEXT 15 ROWS ONLY";
					return $query;
		}
		function change_dlr($field=null){
			return;
		}

		function getdatassu($kd_dealer=null,$ssu_type='CDDB'){
			$query="EXEC SP_TRANS_SSU_GETDATA_".$ssu_type." '".$kd_dealer."'";
			return $query;
		}
		function getBiayaBPKB($kd_dealer=null,$row_status=null){
			$query="";
			if($kd_dealer){
				$query ="EXEC SP_MASTER_BIAYA_STNKBPKB '".$kd_dealer."','".$row_status."'";
			}
			return $query;
		}


		function get_mekanikperformance($kd_dealer,$tahun=null,$bulan=null){
			$tahun =($tahun)?$tahun:date("Y");
			$bulan =($bulan)?$bulan:date("m");
			$query="EXEC SP_MEKANIK_PERFORMANCE '".$kd_dealer."','".$tahun."','".$bulan."'";
			return $query;
		}
		function skemaleasing($kd_dealer,$kd_typemotor,$no_trans){
			$query="EXEC SP_SETUP_LEASING_SKEMA_VIEW '".$kd_dealer."','".$kd_typemotor."','".$no_trans."'";
			return $query;
		}

		function insentif_picpart($parameter){
			$query ="EXEC SP_LAPORAN_INSENTIF_PICPART_VIEW".$parameter["PARAMETER"];
			return $query;
		}

		function rekap_ins_k_sales($parameter){
			$query ="EXEC SP_LAPORAN_REKAP_INS_KS".$parameter["PARAMETER"];
			return $query;
		}
		function rekap_penalty($KD_DEALER, $START_DATE,$END_DATE){
			$query ="SELECT AA.KD_SALES, AA.KD_JABATAN,AA.PERSONAL_JABATAN,AA.NAMA_SALES, MT.TARGET, COUNT(*) AS JUAL,MK.ATASAN_LANGSUNG, MK1.NAMA AS NAMA_ATASAN,SUM(CONVERT(INT,AA.INSENTIF)) AS INS_DASAR, SUM(CONVERT(INT,AA.PENALTY_UNIT)) AS PENALTY_UNIT,SUM(CONVERT(INT,AA.PENALTY_AR)) AS PENALTY_AR FROM (SELECT TRANS_SPK.*, MK.KD_JABATAN, MK.PERSONAL_JABATAN, MS.NAMA_SALES, MS.NIK, MS.GROUP_SALES, 
			SK.ID AS LEASINGID, SK.UANG_MUKA, SK.HASIL, SK.KETERANGAN AS KET, 
			SD.HARGA_OTR, SD.DISKON, SD.KD_TYPEMOTOR, SD.KD_WARNA, SD.HARGA_OTR AS HARGA_OTR2, 
			SS.SK_AHM + SS.SK_MD + SS.SK_SD + SS.SK_FINANCE + SS.DP_OTR AS SUB_DLR_K,
			SS.SC_AHM + SS.SC_MD + SS.SC_SD AS SUB_DLR_C,
			TSM.TGL_SJMASUK, 
			MTM.NAMA_TYPEMOTOR, MTM.NAMA_PASAR, 
			MG.CATEGORY_MOTOR, 
			MI.CASH, MI.KREDIT,  MI.KHUSUS,  
			CASE 
                 WHEN (SS.SK_AHM + SS.SK_MD + SS.SK_SD + SS.SK_FINANCE + SS.DP_OTR + SD.DISKON) > 150000 OR
                 (SS.SC_AHM + SS.SC_MD + SS.SC_SD + SD.DISKON) > 150000 THEN MI.KHUSUS 
                 WHEN TRANS_SPK.TYPE_PENJUALAN = 'CREDIT' THEN MI.KREDIT                          
                 WHEN  TRANS_SPK.TYPE_PENJUALAN = 'CASH' THEN MI.CASH 
            END AS INSENTIF, 
            CASE WHEN (DATEDIFF(DAY, TSM.TGL_SJMASUK, '".$END_DATE."')) >= 91 AND (DATEDIFF(DAY, TSM.TGL_SJMASUK, '".$END_DATE."')) <= 120 THEN 20000
                WHEN (DATEDIFF(DAY, TSM.TGL_SJMASUK, '".$END_DATE."')) > 120 THEN   40000
            END AS PENALTY_UNIT,
            CASE WHEN (DATEDIFF(DAY, TRANS_SPK.TGL_SPK,  SK.JATUH_TEMPO)) > 10 THEN 10000 ELSE 0 END AS PENALTY_AR
			FROM TRANS_SPK 	
			LEFT OUTER JOIN MASTER_CUSTOMER AS MC ON MC.KD_CUSTOMER =TRANS_SPK.KD_CUSTOMER AND MC.ROW_STATUS >= 0 
			LEFT OUTER JOIN MASTER_SALESMAN AS MS ON MS.KD_SALES =TRANS_SPK.KD_SALES AND MS.KD_DEALER =TRANS_SPK.KD_DEALER AND MS.ROW_STATUS >= 0 
			LEFT OUTER JOIN TRANS_SPK_LEASING AS SK ON SK.SPK_ID =TRANS_SPK.ID AND SK.ROW_STATUS >= 0 
			LEFT OUTER JOIN TRANS_SPK_SALESPROGRAM AS SS ON SS.NO_SPK =TRANS_SPK.NO_SPK AND SS.ROW_STATUS >= 0 
			LEFT OUTER JOIN MASTER_KARYAWAN AS MK ON MK.NIK = MS.NIK AND MK.ROW_STATUS >= 0 
			LEFT OUTER JOIN TRANS_SPK_DETAILKENDARAAN AS SD ON SD.SPK_ID =TRANS_SPK.ID AND SD.ROW_STATUS >= 0 
			LEFT OUTER JOIN TRANS_SJMASUK TSM ON TSM.NO_RANGKA = SD.NO_RANGKA AND TSM.ROW_STATUS >=0 
			LEFT OUTER JOIN MASTER_P_TYPEMOTOR AS MTM ON MTM.KD_TYPEMOTOR = SD.KD_TYPEMOTOR AND MTM.KD_WARNA = SD.KD_WARNA AND MTM.ROW_STATUS >= 0 
			LEFT OUTER JOIN MASTER_P_GROUPMOTOR AS MG ON MG.KD_TYPEMOTOR = SD.KD_TYPEMOTOR AND MG.ROW_STATUS >= 0 
			LEFT OUTER JOIN MASTER_INSENTIF AS MI ON MI.KD_CATEGORY = MG.CATEGORY_MOTOR AND MI.KATEGORI = 'Kepala Sales' AND MI.ROW_STATUS >= 0
			WHERE TRANS_SPK.STATUS_SPK = 4)  AA 
			LEFT OUTER JOIN MASTER_TARGETSF MT ON MT.KD_SALES = AA.KD_SALES AND MT.START_DATE >= '".$START_DATE."' AND MT.ROW_STATUS >=0
			LEFT OUTER JOIN MASTER_KARYAWAN MK ON MK.NIK = AA.NIK AND MK.ROW_STATUS >=0
			LEFT OUTER JOIN MASTER_KARYAWAN MK1 ON MK1.NIK = MK.ATASAN_LANGSUNG AND MK.ROW_STATUS >=0
			WHERE AA.TGL_SPK >= '".$START_DATE."' AND AA.TGL_SPK <= '".$END_DATE."' AND (MK1.PERSONAL_JABATAN ='Kepala Sales' OR MK1.PERSONAL_JABATAN ='Koordinator Sales') AND AA.KD_DEALER = '".$KD_DEALER."'
			GROUP BY AA.KD_SALES,AA.KD_JABATAN,AA.PERSONAL_JABATAN, AA.NAMA_SALES, MT.TARGET, MK.ATASAN_LANGSUNG, MK1.NAMA";
			
			return $query;
		}
	

	function rekap_ins_k_counter($KD_DEALER, $START_DATE,$END_DATE){
			$query ="SELECT AA.KD_SALES, AA.KD_JABATAN,AA.PERSONAL_JABATAN,AA.NAMA_SALES, MT.TARGET, COUNT(*) AS JUAL,MK.ATASAN_LANGSUNG, MK1.NAMA AS NAMA_ATASAN,SUM(CONVERT(INT,AA.INSENTIF)) AS INS_DASAR, SUM(CONVERT(INT,AA.PENALTY_UNIT)) AS PENALTY_UNIT,SUM(CONVERT(INT,AA.PENALTY_AR)) AS PENALTY_AR FROM (SELECT TRANS_SPK.*, MK.KD_JABATAN, MK.PERSONAL_JABATAN, MS.NAMA_SALES, MS.NIK, MS.GROUP_SALES, 
			SK.ID AS LEASINGID, SK.UANG_MUKA, SK.HASIL, SK.KETERANGAN AS KET, 
			SD.HARGA_OTR, SD.DISKON, SD.KD_TYPEMOTOR, SD.KD_WARNA, SD.HARGA_OTR AS HARGA_OTR2, 
			SS.SK_AHM + SS.SK_MD + SS.SK_SD + SS.SK_FINANCE + SS.DP_OTR AS SUB_DLR_K,
			SS.SC_AHM + SS.SC_MD + SS.SC_SD AS SUB_DLR_C,
			TSM.TGL_SJMASUK, 
			MTM.NAMA_TYPEMOTOR, MTM.NAMA_PASAR, 
			MG.CATEGORY_MOTOR, 
			MI.CASH, MI.KREDIT,  MI.KHUSUS,  
			CASE 
                 WHEN (SS.SK_AHM + SS.SK_MD + SS.SK_SD + SS.SK_FINANCE + SS.DP_OTR + SD.DISKON) > 150000 OR
                 (SS.SC_AHM + SS.SC_MD + SS.SC_SD + SD.DISKON) > 150000 THEN MI.KHUSUS 
                 WHEN TRANS_SPK.TYPE_PENJUALAN = 'CREDIT' THEN MI.KREDIT                          
                 WHEN  TRANS_SPK.TYPE_PENJUALAN = 'CASH' THEN MI.CASH 
            END AS INSENTIF, 
            CASE WHEN (DATEDIFF(DAY, TSM.TGL_SJMASUK, '".$END_DATE."')) >= 91 AND (DATEDIFF(DAY, TSM.TGL_SJMASUK, '".$END_DATE."')) <= 120 THEN 20000
                WHEN (DATEDIFF(DAY, TSM.TGL_SJMASUK, '".$END_DATE."')) > 120 THEN   40000
            END AS PENALTY_UNIT,
            CASE WHEN (DATEDIFF(DAY, TRANS_SPK.TGL_SPK,  SK.JATUH_TEMPO)) > 10 THEN 10000 ELSE 0 END AS PENALTY_AR
			FROM TRANS_SPK 	
			LEFT OUTER JOIN MASTER_CUSTOMER AS MC ON MC.KD_CUSTOMER =TRANS_SPK.KD_CUSTOMER AND MC.ROW_STATUS >= 0 
			LEFT OUTER JOIN MASTER_SALESMAN AS MS ON MS.KD_SALES =TRANS_SPK.KD_SALES AND MS.KD_DEALER =TRANS_SPK.KD_DEALER AND MS.ROW_STATUS >= 0 
			LEFT OUTER JOIN TRANS_SPK_LEASING AS SK ON SK.SPK_ID =TRANS_SPK.ID AND SK.ROW_STATUS >= 0 
			LEFT OUTER JOIN TRANS_SPK_SALESPROGRAM AS SS ON SS.NO_SPK =TRANS_SPK.NO_SPK AND SS.ROW_STATUS >= 0 
			LEFT OUTER JOIN MASTER_KARYAWAN AS MK ON MK.NIK = MS.NIK AND MK.ROW_STATUS >= 0 
			LEFT OUTER JOIN TRANS_SPK_DETAILKENDARAAN AS SD ON SD.SPK_ID =TRANS_SPK.ID AND SD.ROW_STATUS >= 0 
			LEFT OUTER JOIN TRANS_SJMASUK TSM ON TSM.NO_RANGKA = SD.NO_RANGKA AND TSM.ROW_STATUS >=0 
			LEFT OUTER JOIN MASTER_P_TYPEMOTOR AS MTM ON MTM.KD_TYPEMOTOR = SD.KD_TYPEMOTOR AND MTM.KD_WARNA = SD.KD_WARNA AND MTM.ROW_STATUS >= 0 
			LEFT OUTER JOIN MASTER_P_GROUPMOTOR AS MG ON MG.KD_TYPEMOTOR = SD.KD_TYPEMOTOR AND MG.ROW_STATUS >= 0 
			LEFT OUTER JOIN MASTER_INSENTIF AS MI ON MI.KD_CATEGORY = MG.CATEGORY_MOTOR AND MI.KATEGORI = 'Kepala Sales' AND MI.ROW_STATUS >= 0
			WHERE TRANS_SPK.STATUS_SPK = 4)  AA 
			LEFT OUTER JOIN MASTER_TARGETSF MT ON MT.KD_SALES = AA.KD_SALES AND MT.START_DATE >= '".$START_DATE."' AND MT.ROW_STATUS >=0
			LEFT OUTER JOIN MASTER_KARYAWAN MK ON MK.NIK = AA.NIK AND MK.ROW_STATUS >=0
			LEFT OUTER JOIN MASTER_KARYAWAN MK1 ON MK1.NIK = MK.ATASAN_LANGSUNG AND MK.ROW_STATUS >=0
			WHERE AA.TGL_SPK >= '".$START_DATE."' AND AA.TGL_SPK <= '".$END_DATE."' AND MK1.PERSONAL_JABATAN ='Kepala Counter' AND AA.KD_DEALER = '".$KD_DEALER."'
			GROUP BY AA.KD_SALES,AA.KD_JABATAN,AA.PERSONAL_JABATAN, AA.NAMA_SALES, MT.TARGET, MK.ATASAN_LANGSUNG, MK1.NAMA";
			
			return $query;
		}

		function rekap_ins_ksp($KD_DEALER, $START_DATE,$END_DATE){
			$query ="SELECT AA.KD_DEALER, AA.KD_MAINDEALER, COUNT(*) AS JUAL, SUM(CONVERT(INT,AA.PENALTY_UNIT)) AS PENALTY_UNIT,SUM(CONVERT(INT,AA.PENALTY_AR)) AS PENALTY_AR FROM 

			(SELECT TRANS_SPK.*, MK.KD_JABATAN, MK.PERSONAL_JABATAN, MS.NAMA_SALES, MS.NIK, MS.GROUP_SALES, 
			SK.ID AS LEASINGID, SK.UANG_MUKA, SK.HASIL, SK.KETERANGAN AS KET, 
			SD.HARGA_OTR, SD.DISKON, SD.KD_TYPEMOTOR, SD.KD_WARNA, TSM.TGL_SJMASUK, MTM.NAMA_TYPEMOTOR, MTM.NAMA_PASAR, MG.CATEGORY_MOTOR,

            CASE WHEN (DATEDIFF(DAY, TSM.TGL_SJMASUK, '".$END_DATE."')) >= 91 AND (DATEDIFF(DAY, TSM.TGL_SJMASUK, '".$END_DATE."')) <= 120 THEN 20000
                WHEN (DATEDIFF(DAY, TSM.TGL_SJMASUK, '".$END_DATE."')) > 120 THEN   40000
            END AS PENALTY_UNIT,
            CASE WHEN (DATEDIFF(DAY, TRANS_SPK.TGL_SPK,  SK.JATUH_TEMPO)) > 10 THEN 10000 ELSE 0 END AS PENALTY_AR
			FROM TRANS_SPK 	
			LEFT OUTER JOIN MASTER_CUSTOMER AS MC ON MC.KD_CUSTOMER =TRANS_SPK.KD_CUSTOMER AND MC.ROW_STATUS >= 0 
			LEFT OUTER JOIN MASTER_SALESMAN AS MS ON MS.KD_SALES =TRANS_SPK.KD_SALES AND MS.KD_DEALER =TRANS_SPK.KD_DEALER AND MS.ROW_STATUS >= 0 
			LEFT OUTER JOIN TRANS_SPK_LEASING AS SK ON SK.SPK_ID =TRANS_SPK.ID AND SK.ROW_STATUS >= 0 
			
			LEFT OUTER JOIN MASTER_KARYAWAN AS MK ON MK.NIK = MS.NIK AND MK.ROW_STATUS >= 0 
			LEFT OUTER JOIN TRANS_SPK_DETAILKENDARAAN AS SD ON SD.SPK_ID =TRANS_SPK.ID AND SD.ROW_STATUS >= 0 
			LEFT OUTER JOIN TRANS_SJMASUK TSM ON TSM.NO_RANGKA = SD.NO_RANGKA AND TSM.ROW_STATUS >=0 
			LEFT OUTER JOIN MASTER_P_TYPEMOTOR AS MTM ON MTM.KD_TYPEMOTOR = SD.KD_TYPEMOTOR AND MTM.KD_WARNA = SD.KD_WARNA AND MTM.ROW_STATUS >= 0 
			LEFT OUTER JOIN MASTER_P_GROUPMOTOR AS MG ON MG.KD_TYPEMOTOR = SD.KD_TYPEMOTOR AND MG.ROW_STATUS >= 0 			
			WHERE TRANS_SPK.STATUS_SPK = 4)  AA 

			WHERE AA.TGL_SPK >= '".$START_DATE."' AND AA.TGL_SPK <= '".$END_DATE."' AND AA.KD_DEALER = '".$KD_DEALER."'
			GROUP BY AA.KD_DEALER, AA.KD_MAINDEALER";
			
			return $query;
		}

		function penalty_ksp($KD_DEALER, $START_DATE,$END_DATE){
			$query ="SELECT YEAR(AA.TGL_SPK) as TAHUN,MONTH(AA.TGL_SPK) AS BULAN,  SUM(CONVERT(INT,AA.PENALTY_UNIT)) AS PENALTY_UNIT,JENIS_PENALTY_UNIT,COUNT(JENIS_PENALTY_UNIT) BANYAK_PENALTY_UNIT,SUM(CONVERT(INT,AA.PENALTY_AR)) AS PENALTY_AR, SUM(CONVERT(INT,AA.BANYAK_PENALTY_AR)) AS BANYAK_PENALTY_AR FROM (SELECT TRANS_SPK.*, MK.KD_JABATAN, MK.PERSONAL_JABATAN, MS.NAMA_SALES, MS.NIK, MS.GROUP_SALES, 
			SK.ID AS LEASINGID, SK.UANG_MUKA, SK.HASIL, SK.KETERANGAN AS KET, 
			SD.HARGA_OTR, SD.DISKON, SD.KD_TYPEMOTOR, SD.KD_WARNA, SD.HARGA_OTR AS HARGA_OTR2, 
			SS.SK_AHM + SS.SK_MD + SS.SK_SD + SS.SK_FINANCE + SS.DP_OTR AS SUB_DLR_K,
			SS.SC_AHM + SS.SC_MD + SS.SC_SD AS SUB_DLR_C,
			TSM.TGL_SJMASUK, 
			MTM.NAMA_TYPEMOTOR, MTM.NAMA_PASAR, 
			MG.CATEGORY_MOTOR, 
			
            CASE WHEN (DATEDIFF(DAY, TSM.TGL_SJMASUK, '".$END_DATE."')) >= 91 AND (DATEDIFF(DAY, TSM.TGL_SJMASUK, '".$END_DATE."')) <= 120 THEN 20000

                WHEN (DATEDIFF(DAY, TSM.TGL_SJMASUK, '".$END_DATE."')) > 120 THEN   40000
            END AS PENALTY_UNIT,

            CASE WHEN (DATEDIFF(DAY, TSM.TGL_SJMASUK, '".$END_DATE."')) >= 91 AND (DATEDIFF(DAY, TSM.TGL_SJMASUK, '".$END_DATE."')) <= 120 THEN '91 - 120'
            	
                WHEN (DATEDIFF(DAY, TSM.TGL_SJMASUK, '".$END_DATE."')) > 120 THEN   ' > 120 Hari'
            END AS JENIS_PENALTY_UNIT,
            CASE WHEN (DATEDIFF(DAY, TRANS_SPK.TGL_SPK,  SK.JATUH_TEMPO)) > 10 THEN 10000 ELSE 0 END AS PENALTY_AR,
            CASE WHEN (DATEDIFF(DAY, TRANS_SPK.TGL_SPK,  SK.JATUH_TEMPO)) > 10 THEN 1 ELSE 0 END AS BANYAK_PENALTY_AR
			FROM TRANS_SPK 	
			LEFT OUTER JOIN MASTER_CUSTOMER AS MC ON MC.KD_CUSTOMER =TRANS_SPK.KD_CUSTOMER AND MC.ROW_STATUS >= 0 
			LEFT OUTER JOIN MASTER_SALESMAN AS MS ON MS.KD_SALES =TRANS_SPK.KD_SALES AND MS.KD_DEALER =TRANS_SPK.KD_DEALER AND MS.ROW_STATUS >= 0 
			LEFT OUTER JOIN TRANS_SPK_LEASING AS SK ON SK.SPK_ID =TRANS_SPK.ID AND SK.ROW_STATUS >= 0 
			LEFT OUTER JOIN TRANS_SPK_SALESPROGRAM AS SS ON SS.NO_SPK =TRANS_SPK.NO_SPK AND SS.ROW_STATUS >= 0 
			LEFT OUTER JOIN MASTER_KARYAWAN AS MK ON MK.NIK = MS.NIK AND MK.ROW_STATUS >= 0 
			LEFT OUTER JOIN TRANS_SPK_DETAILKENDARAAN AS SD ON SD.SPK_ID =TRANS_SPK.ID AND SD.ROW_STATUS >= 0 
			LEFT OUTER JOIN TRANS_SJMASUK TSM ON TSM.NO_RANGKA = SD.NO_RANGKA AND TSM.ROW_STATUS >=0 
			LEFT OUTER JOIN MASTER_P_TYPEMOTOR AS MTM ON MTM.KD_TYPEMOTOR = SD.KD_TYPEMOTOR AND MTM.KD_WARNA = SD.KD_WARNA AND MTM.ROW_STATUS >= 0 
			LEFT OUTER JOIN MASTER_P_GROUPMOTOR AS MG ON MG.KD_TYPEMOTOR = SD.KD_TYPEMOTOR AND MG.ROW_STATUS >= 0 
			LEFT OUTER JOIN MASTER_INSENTIF AS MI ON MI.KD_CATEGORY = MG.CATEGORY_MOTOR AND MI.KATEGORI = 'Kepala Sales' AND MI.ROW_STATUS >= 0
			WHERE TRANS_SPK.STATUS_SPK = 4)  AA 
			
			LEFT OUTER JOIN MASTER_KARYAWAN MK ON MK.NIK = AA.NIK AND MK.ROW_STATUS >=0
			
			WHERE AA.TGL_SPK >= '".$START_DATE."' AND AA.TGL_SPK <= '".$END_DATE."' AND AA.KD_DEALER = '".$KD_DEALER."'
			GROUP BY YEAR(AA.TGL_SPK),MONTH(AA.TGL_SPK),JENIS_PENALTY_UNIT";
			
			return $query;
		}


		function update_NRFS($no_mesin,$kd_status_tujuan,$modify_by){
			if($kd_status_tujuan == 'NRFS'){
				$query = "SELECT * FROM TRANS_INV_MUTASI WHERE PART_NUMBER = '$no_mesin' AND ROW_STATUS >= 0; 
					  UPDATE TRANS_TERIMASJMOTOR SET STOCK_STATUS = '0', LASTMODIFIED_BY='".$modify_by."',LASTMODIFIED_TIME = GETDATE()  WHERE NO_MESIN ='".$no_mesin."'";
				
			}else if($kd_status_tujuan == 'RFS'){
				$query = "SELECT * FROM TRANS_INV_MUTASI WHERE PART_NUMBER = '$no_mesin' AND ROW_STATUS >= 0; 
					  UPDATE TRANS_TERIMASJMOTOR SET STOCK_STATUS = '1', LASTMODIFIED_BY='".$modify_by."',LASTMODIFIED_TIME = GETDATE()  WHERE NO_MESIN ='".$no_mesin."'";
			}
			
			return $query;
		}

		function update_mutasi_NRFS($no_trans,$modify_by){
			
				$query = "SELECT * FROM TRANS_INV_MUTASI WHERE NO_TRANS = '$no_trans' AND ROW_STATUS >= 0; 
					  UPDATE TRANS_INV_MUTASI SET STATUS_MUTASI = '1', LASTMODIFIED_BY='".$modify_by."',LASTMODIFIED_TIME = GETDATE()  WHERE NO_TRANS ='".$no_trans."'";
			
			
			return $query;
		}


		/**
		 * [stock fulfillment description]
		 * @param  [type] $param [description]
		 * @param  [type] $list  [description]
		 * @return [type]        [description]
		 */
		function stock_fulfillment($kd_item,$kd_dealer,$status_indent,$no_mesin=null){
			$top_1=($no_mesin)?"":"";
			// $top_1=($no_mesin)?"TOP 1 ":"";
			$order_by =($no_mesin)?"ORDER BY TGL_TERIMA" :" ORDER BY TGL_TERIMA DESC";
			// $where =($no_mesin)?"NO_MESIN = '".$no_mesin."'" :"STOCK_AKHIR > 0";
			$where =($no_mesin)?"STOCK_AKHIR > 0 AND NRFS = 0 OR NO_MESIN = '".$no_mesin."'" :"STOCK_AKHIR > 0 AND NRFS = 0";
			// $where =($no_mesin)?"NO_MESIN = '".$no_mesin."'" :"STOCK_AKHIR > 0 AND NRFS = 0";
			$whereindent =($no_mesin)?"":"AND FOR_INDENT = $status_indent";

			$querys="WITH ALLSTOCK AS (
						SELECT * FROM TRANS_STOCKMOTOR 
						WHERE 
						ROW_STATUS >= 0 AND
						KD_ITEM = '".$kd_item."' AND 
						KD_DEALER = '".$kd_dealer."' AND ".$where."
					)
					, INDENT AS (
						SELECT SP.KD_DEALER, SPD.* FROM TRANS_SPK_DETAILKENDARAAN SPD
						LEFT JOIN TRANS_SPK SP ON SP.ID = SPD.SPK_ID AND SP.ROW_STATUS >= 0
						LEFT JOIN MASTER_P_TYPEMOTOR PT ON PT.KD_TYPEMOTOR = SPD.KD_TYPEMOTOR AND PT.KD_WARNA = SPD.KD_WARNA AND PT.ROW_STATUS >= 0
						LEFT JOIN TRANS_GUESTBOOK G ON G.SPK_NO=SP.NO_SPK OR G.GUEST_NO=SP.GUEST_NO AND G.ROW_STATUS >= 0
						WHERE 
						SPD.ROW_STATUS >= 0
						AND G.STATUS = 'Deal Indent' 
						AND SP.STATUS_SPK >= 1
						AND LEN(ISNULL(SPD.NO_MESIN, '')) = 0
						AND SP.KD_DEALER = '".$kd_dealer."'
						AND PT.KD_ITEM = '".$kd_item."' 
					)
					SELECT $top_1 CASE WHEN (SELECT COUNT(KD_ITEM) FROM ALLSTOCK) > (SELECT COUNT(ID) FROM INDENT) THEN 1 ELSE 0 END STOCK_NOTINDENT, * FROM ALLSTOCK WHERE ROW_STATUS >= 0 $order_by";
			return $querys;
		}




	}


	


	
?>