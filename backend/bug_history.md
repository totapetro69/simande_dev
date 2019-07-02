[06-03-2019]
1. Merubah genarate stock part dari query per users digantikan pembuatan trigger untuk proses update stock otomatis
2. trigger after update dan insert di setiap table sbb:
	1. SETUP_SA_PART [done]
	2. TRANS_PART_TERIMADETAIL [done]
	3. TRANS_INV_MUTASI [DONE]
	4. TRANS_PART_PICKING_DETAIL [done]
	5. TRANS_RETUR_JUALBELI_DETAIL
3. Rule trigger adalah :
	1. Jika part number tidak terdapat di table TRANS_PART_STOCK berdasarkan kd_dealer,lokasi dealer gudang dan rakbin maka lakukan insert ke table tersebut sebaliknya lakukan update
	2. Untuk proses update
		1. jika row_status di update jadi -1 ( model delete) maka kurang stock sejumlah kolom jumlah yang di update
		2. jika update dilakukan untuk kolom jumlah maka total stock dikurangi dengan jumlah asal yang di edit di tambah jumlah baru hasil editan
		3. jika tidak ada perubahan di 2 item di atas tidak melakukan update
		4. point 1,2 di atas untuk trigger proses penerimaan / penambahan stock 
		5. untuk trigger transaksi pengeluran maka untuk point 1 mode nya di tambahkan dan untuk point 2 modenya di kurangkan