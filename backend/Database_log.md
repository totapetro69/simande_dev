#Database Change Log#
=== iswan update
### 03/09/2017 ###
####Store Procedure####
	* SP_MASTER_CUSTOMER_UPDATE_AP
	* SP_MASTER_CUSTOMER_UPDATE_SO
	**__[remove master customer from updatable field]__

	* SP_TRANS_UANGMASUK_APV
	
####Create table USERS_AREA####
	*__otorisasi user main dealer untuk akses data dealer__
####Trigger####
	*TRANS_UANGMASUK_DETAIL *
	** __change trigger UpdateReff__
####TABLE###
	* Alter table TRANS_UANGMASUK add VOUCHER_BY VARCHAR(50) NULL
======= pita update
## 03/09/2017 ##
*Store Procedure*
	* SP_MASTER_CUSTOMER_UPDATE_AP* [167 done]
	* SP_MASTER_CUSTOMER_UPDATE_SO* [167 done]
	** __[remove master customer from updatable field]__

	* SP_MASTER_TOLERANSI_INSERT* [167 done]
	* SP_MASTER_TOLERANSI_UPDATE* [167 done]
	** __[change data type TOLERANSI from varchar to int]__
	* SP_USERS_AREA_INSERT* [167 done]
	* SP_USERS_AREA_UPDATE* [167 done]
	* SP_USERS_AREA_DELETE* [167 done] 
	* SP_USERS_APPROVAL_INSERT* [167 done]
	* SP_USERS_APPROVAL_UPDATE* [167 done]
	* SP_USERS_APPROVAL_DELETE* [167 done]
	* SP_USERS_UPDATE* [167 done]
	* SP_USERS_INSERT* [167 done]
	* SP_TRANS_PKB_DETAIL_INSERT*[167 done]
	* SP_TRANS_PKB_DETAIL_UPDATE*[167 done]

####Views#####
	* TRANS_STNK_BATASTOLERANSI_VIEW* [167 done]
	** __[add new view]__

####Backend###
	* Menu>users_area*
	* Menu>users_approval*
	* Login>users*
	* Service>pkb_detail*


####Table###
	* USERS* __[167 done]__
	* TRANS_PKB_DETAIL* __[167 done]__

----
##04/09/2018##
####Store Procedur####
	1. create SP_TRANS_PKB_OLI __[167 Done]__
	2. Update Custom Query Upload data from file and web service
		- Update backend controller sparepart

##05/09/2018##
####Table View####
	1. Create view TRANS_PKB_DETAIL_V __[167 done]__
	2. Update Backend service->pkb_detail_get($view=null);
	3. Please copy data table SETUP_ACC_JURNAL_OTO to 167 (both) __[167 done]__
	
##07/09/2018##
####Store Procedure####
	^SP_TRANS_PKB_DETAIL_INSERT __[167 done]__
	^SP_TRANS_PKB_DETAIL_UPDATE __[167 done]__
	^SP_TRANS_PKB_OLI __[167 done]__

####Table###
	^ TRANS_PKB_DETAIL __[167 done]__


##12/09/2018##


	##12/09/2018##
####Store Procedure####
	^SP_TRANS_SO_UPDATE __[167 done]__
	^SP_TRANS_SO_KENDARAAN_UPDATE __[167 done]__
	__[remove LOK_PENJUALAN & LOK_PENGIRIMAN]__
	^SP_SETUP_SA_UNIT_INSERT __[167 done]__
	^SP_SETUP_SA_UNIT_UPDATE __[167 done]__
	^SP_SETUP_SA_UNIT_DELETE __[167 done]__
	__add sp for data SETUP_SA_UNIT__

####Backend###
	* Setup>kepalasales*
	* Spk>so*
	* Spk>so_kendaraan *
	* Setup>sa_unit*

###TABLE##
	1. CREATE TABLE SETUP_SA_UNIT on 167,114 __[DONE]__
	TODO :
	Buat bc dan sp crud nya...__[done]

##17-09-2018##
####Store Procedure####
	1. CREATE SP SP_TRANS_SPK_HISTORY - Pembatalan SPK  __[167 done]__
	2. CREATE SP SP_TRANS_SPK_BATAL - Process Pembatalan SPK __[167 done]__
	3. UPDATE SP SP_TRANS_APPROVAL_DS_UPDATE __[167 done]__
####View####

	1. Update View TRANS_APPROVAL_DSV __[167 done]__
	2. Update View MASTER_CUSTOMER_VIEW __[167 done]__

	1. Update View TRANS_APPROVAL_DSV __[167 done]__

##18-09-2018##
####Table####
	^TRANS_FILE __[167 done]__
####Store Procedure####
	^SP_TRANS_FILE_INSERT __[167 done]__
	^SP_TRANS_FILE_UPDATE __[167 done]__
####Backend####
	^laporan>file_post
	^laporan>file_put
--delete field kd_kota dan kelurahan

##19-09-2018##
####Store Procedure####
	^SP_TRANS_FILE_INSERT __[167 done]__
	--delete tgl_download,tgl_batal_download dan status_download
	^SP_TRANS_FILE_DOWNLOAD_UPDATE __[167 done]__
	--create sp baru

####Backend####
	^laporan>file_post
	^laporan>file_download_put

##21-09-2018##
####TABLE####
	^TRANS_FILE __[167 done]__
	--add field
####Store Procedure####
	^SP_TRANS_FILE_INSERT __[167 done]__
	--add field

####Backend####
	^laporan>file_post

##24-09-2018##
####Store Procedure####
	1. CREATE SP_MASTER_RANGEPAJAK --Nomor Pajak yang siap digunakan __[167 done]__ 

####TRIGGER####
	^Update DetailFaktur  __[167 done]__
	--create trigger on TRANS_FAKTURPAJAK_DETAIL

##25-09-2018##

####TRIGGER###
	1. Update Trigger on TRANS_GUESTBOOK -> ResetAppointment __[167 done]__

####Store Procedure####
	1. Update SP_TRANS_SPK_HISTORY	__[167 done]__
	2. Update SP_TRANS_SPK_BATAL	__[167 done]__
	3. Update SP_MASTER_RANGE_PAJAK	__[167 done]__

##26-09-2018##
####View####
	1. Update View TRANS_APPROVAL_DSV	__[167 done]__

##27-09-2018##

####Store Procedure####
	1. Update SP_TRANS_FAKTURPAJAK_INSERT	__[167 done]__
	2. Update SP_TRANS_FAKTURPAJAK_UPDATE	__[167 done]__
	3. Update SP_TRANS_PARTSO_UPDATE __[167 done]__
	4. Update SP_TRANS_PARTSO_INSERT __[167 done]__

####Table####
	1. TRANS_PARTSO __[167 done]__
	--update length field

##28-09-2018##
####Store Procedure####
	1. CREATE SP_WORKSOP_ABSEN __[114 and 167_simande done]__


##04-10-2018##
####Table####
	1. Create table MASTER_PENDIDIKAN_LEVEL __[114 and 167_simande done]__

##05-10-2018##
###Coding###
	1. Penambahan setingan constant variable PATH_DATA di folder config->constant.php (front end), ini berfungsi untuk mengarahkan penyimpanan generate file json
	2. Perubahan prosed download saleskupon dari web servce disimpan ke json file di folder PATH_DATA dan di baca lagi untuk di compare dengan data yang ada di database per 20 item 

####Table###
	1. Penambahan Table Master_Pendidikan_level ( untuk dropdown di spk quiz);
	2. Perubahan trigger di Setup_Saleskupon_tmp __[114 and 167_simande done]__
	3. create table SETUP_SO_PROGRAMHADIAH  __[114 and 167_simande done]__
	TODO: create SP SETUP_SO_PROGRAMHADIAH (CRUD) __[114 and 167_simande done]__

####Trigger###
	1. Update UpdateSPKStatus - menambahkan delete spk_saleskupon dan spk_salesprogram __[114 and 167_simande done]__

##06-10-2018##
####Store Procedur####
	1. Update SP_TRANS_SO_KENDARAAN_UPDATE __[114 and 167_simande done]__
	2. Update SP_TRANS_SPK_DETAILCUSTOMER_UPDATE __[114 and 167_simande done]__
	3. Update SP_TRANS_SPK_DETAILCUSTOMER_INSERT __[114 and 167_simande done]__
	4. Update SP_TRANS_SPK_PAYBILL __[114 and 167_simande done]__
	5. Update SP_TRANS_UANGMASUK_DETAIL_INSERT __[114 and 167_simande done]__
	6. Update SP_TRANS_UANGMASUK_DETAIL_UPDATE __[114 and 167_simande done]__
	
####Table####
	1. ALTER table TRANS_SPK_DETAILCUSTOMER ADD EMAIL_BPKB VARCHAR(150), TGL_LAHIR_BPKB DATE __[114 and 167_simande done]__
	2. ALTER TABLE TRANS_SPK ADD PAYBILL_REFF2 VARCHAR(30) __[114 and 167_simande done]__
	3. ALTER TABLE TRANS_SPK_DETAILKENDARAAN ADD KURANG_BAYAR DECIMAL(18,2), RENCANA_BAYAR DATE __[114 and 167_simande done]__

##08-10-2018##
####View####
	1. Update TRANS_SPK_KENDARAANVIEW __[114 and 167_simande done]__
	2. Update TRANS_FILE_UDPRG_VIEW __[167 done]__
####Store Procedure####
	1. CREATE SP_TRANS_PIUTANG_APPROVAL __[114 done]__
	2. UPDATE SP_TRANS_PIUTANG_INSERT __[114 done]__
	3. UPDATE SP_TRANS_PIUTANG_UPDATE __[114 done]__
	4. UPDATE SP_TRANS_PIUTANG_DELETE __[114 done]__

	bc has been changed
##09-10-2018##
	1. Update Trigger TRANS_PART_SJMASUK->Update_data __[114 and 167_simande done]__
	2. Update SP_TRANS_KASIR_AUDIT_DELETE __[114 and 167_simande done]__
	3. Update SP_TRANS_KASIR_AUDIT_UPDATE __[114 and 167_simande done]__

##10-10-2018##
	1. Update Trigger TRANS_UANGMASUK ->UpdateTransUangMasuk __[114 and 167_simande done]__
	2. CREATE SP_TRANS_JURNAL_DETAIL_ALL_DELETE __[167 simande done]__
	3. UPDATE view TRANS_STNK_PENGURUS_V __[114 and 167_simande done]__
	4. CREATE PROCEDURE SP_TRANS_SPK_FEE __[114 and 167_simande done]__

##11-10-2018##
	1. Update Trigger TRANS_PARTSO ->UpdateNomorUrutSOPART __[114 and 167_simande done]__

##18-10-2018##
	1. CREATE PROCEDURE [dbo].[SP_TRANS_KASIR_AUDIT_STATUS]
	2. UPDATE PROCEDURE [dbo].[SP_TRANS_KASIR_AUDIT_UPDATE]
	3. UPDATE PROCEDURE [dbo].[SP_TRANS_KASIR_AUDIT_INSERT]

##19-10-2018##
####Store Procedur####
	1.SP_TRANS_PARTSO_CUSTOMER_INSERT __[114 and 167_simande done]__
	2.SP_TRANS_PARTSO_CUSTOMER_UPDATE __[114 and 167_simande done]__
	3.SP_TRANS_PARTSO_CUSTOMER_DELETE __[114 and 167_simande done]__

####VIEWS####
	1. TRANS_BIROJASA_OUTSTANDING_VIEW __[114 and 167_simande done]__

##22-10-2018##
####Store Procedure####
	1.SP_MASTER_MODUL_APV_INSERT __[114 and 167 done]__
	2.SP_MASTER_MODUL_APV_UPDATE __[114 and 167 done]__
	3.SP_MASTER_MODUL_APV_DELETE __[114 and 167 done]__

	4.UPDATE SP_TRANS_PO2MD_DETAIL_UPDATE	__[114 and 167_simande done]__
	5.UPDATE SP_TRANS_PO2MD_UPDATE	__[114 and 167_simande done]__

##23-10-2018##
####Store Procedure###

	1. UPDATE SP_SP_TRANS_RANKPARTS __[167 Done]__
	2. UPDATE SP_TRANS_PARTSO_UPDATE __[167 done]__

	1. UPDATE SP_SP_TRANS_RANKPARTS __[167 Done]__ __[114 and 167 simande  done]__


####Table####
	1. ALTER TABLE TRANS_STNK_BUKTI ADD NO_MESIN VARCHAR(30) __[167 done]__  __[114 and 167 simande  done]__

####View####
	1. CREATE VIEW TRANS_DATA_UNIT - data customer H2 __[167 done]__ __[114 and 167 simande  done]__
	2. CREATE VIEW TRANS_PARTSO_CUSTOMER __[114 and 167 simande  done]__
	
##22-10-2018##
####View####
	1.TRANS_PENERIMAAN_UNIT_VIEW __[114 and 167 done]__

####Store Procedure####
	1.SP_TRANS_INV_MUTASI_STATUS_UPDATE  __[114 and 167 done]__ 
	2.[SP_TRANS_SJ_TERIMASJMOTOR_UPDATE] __[114 and 167 done]__


##29-10-2018##
####Table####
	1. ALTER TABLE USERS ADD APV_DOC INT 	__[114 and 167 done]__
	__pita update__
	1.TRANS_CUST_BOOKING	__[114 and 167 done]__
	2.TRANS_CUST_REMINDER	__[114 and 167 done]__
	3.MASTER_STNK_BPKB		__[114 and 167 done]__
	__add field__ 

####Store Procedure####
	1. UPDATE SP_USERS_INSERT 	__[114 and 167 done]__
	2. UPDATE SP_USERS_UPDATE 	__[114 and 167 done]__
	__pita update__
	1.SP_TRANS_CUST_BOOKING_INSERT	__[114 and 167 done]__
	2.SP_TRANS_CUST_BOOKING_UPDATE	__[114 and 167 done]__
	3.SP_TRANS_CUST_REMINDER_INSERT	__[114 and 167 done]__
	4.SP_TRANS_CUST_REMINDER_UPDATE	__[114 and 167 done]__
	5.SP_MASTER_STNK_BPKB_INSERT	__[114 and 167 done]__
	6.SP_MASTER_STNK_BPKB_UPDATE	__[114 and 167 done]__

##30-10-2018##
####Store Procedure####
	1. UPDATE SP_TRANS_CSA_UPDATE __[114 and 167 done]__ merubah where 
####Table####
	1.MASTER_STNK_BPKB		__[114 and 167 done]__
	--change length field--

####Store Procedure####
	1.SP_MASTER_STNK_BPKB_INSERT	__[114 and 167 done]__
	2.SP_MASTER_STNK_BPKB_UPDATE	__[114 and 167 done]__

####Views####
	1.TRANS_STNK_PENGURUSAN_VIEW	__[114 and 167 done]__
	2.MASTER_KABUPATEN_SAMSAT_VIEW	__[114 and 167 done]__

##01-11-2018##
####Store Procedure####
	1. UPDATE SP_TRANS_STNK_DETAIL_RW_DELETE __[114 and 167 done]__UPDATE
	--create--
	2. SP_TRANS_STNK_DELETE __[114 and 167 done]__
	



##05-11-2018##
####table####
	1.TRANS_TITIPAN_UANG	__[114 and 167 done]__

####Store Procedure####
	1.SP_TRANS_TITIPAN_UANG_DELETE	__[114 and 167 done]__
	2.SP_TRANS_TITIPAN_UANG_INSERT	__[114 and 167 done]__
	3.SP_TRANS_TITIPAN_UANG_UPDATE	__[114 and 167 done]__

####views####
	1.TRANS_STOCKOPNAME_VIEW 	__[114 and 167 done]__

##06-11-2018##
####table####
	1.TRANS_STNK	__[114 and 167 done]__
	--add field--

####Store Procedure####
	1.SP_TRANS_STNK_UPDATE_STATUSCETAK	__[114 and 167 done]__
	2.UPDATE SP_TRANS_PIUTANG_APPROVAL __[114 and 167 done]__

<<<<<<< HEAD
##08-11-2018##
####table####
	1.TRANS_TITIPAN_UANG	__[114 and 167 done]__
	--add field--

####Store Procedure####
	1.SP_TRANS_TITIPAN_UANG_INSERT	__[114 and 167 done]__
	2.SP_TRANS_TITIPAN_UANG_UPDATE __[114 and 167 done]__
=======
##06-11-2018##
####Store Procedur####
	1. UPDATE SP_TRANS_PIUTANG_APPROVAL __[167 done]__
	2. UPDATE SP_TRANS_STNK_DETAIL_UPDATE __[167 done]__ no_stnk dan no_pengajuan disabled on query
	3. UPDATE SP_TRANS_STNK_DETAIL_FILE_UPDATE __[167]__ change sp for update pembayaran ss
	4. UPDATE TRIGGER UpdateTransUangMasuk di TRANS_UANGMASUK __[167 done]__ menambahkan membalikan status file di trans_stnk_detail jika transaksi di trans_uangmasuk di hapus

##07-11-2018##
####Store Procedure####
	1. UPDATE SP_TRANS_UANGMASUK_INSERT
	2. CREATE TRIGGER dbo.UpdateNoTrans ON  dbo.TRANS_TITIPAN_UANG 
	3. UPDATE TRIGGER UpdateNomorTranskaisUangMasuk ON dbo.TRANS_UANGMASUK
	4. UPDATE TRIGGER UpdateTransUangMasuk ON dbo.TRANS_UANGMASUK
>>>>>>> 2c356648013f115e09906c5917bccfb6e4f35a56
