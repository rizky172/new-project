# Caraka Guna

## List Module

**Transaksi**

1. Quotation
2. Quotation Online
3. Sales Order
4. Surat Jalan
5. Invoice
6. Faktur Pajak

**Laporan**

1. Omzet
2. Tagihan Customer

**Master Data**

1. Admin
2. Customer
3. Barang Customer
4. Barang
5. Cabang
6. Kategori
    - Satuan
7. Hak Akses

## Timeline

### Checkpoint 1

**Transaksi**

1. Quotation
2. Quotation Online


**Master Data**

*Semua master data yang diperlukan untuk mendukung semua Module Transaksi*

### Checkpoint 2

**Transaksi**

1. Sales Order
2. Surat Jalan
3. Invoice
4. Faktur Pajak

**Laporan**

1. Omzet
2. Tagihan Customer

### Checkpoint 3

**Transaksi**

1. Quo to SO
2. SO to SJ
3. SJ to Inv

**NOTE:** Ini untuk membantu koneksi dari SJ ke Invoice, karena pengisian bisa dari INV dulu baru SJ. Jadi tidak sesuai dengan alur. Module ini akan membantu user dalam mapping ke masing-masing SJ.

## Penjelasan Per Module

### Quotation

- Quotation ID: Optional, Mengambil dari "Quotation via Online"
- Ref No
- Ref No(Ext): Keterangan, "Nomor quotation customer"
- Company Name: Optional
- Company PIC: Auto Gen dari Customer ID
- Our PIC: PIC dari Caraka Guna
- Created: Date
- Due Date: Date
- PIC Name: Optional, nama PIC di Caraka Guna
- Notes

Detail:

- Permintaan Barang: Text
- Barang: Selectbox
- Qty
- Satuan
- Harga
- Merk: Selectbox
- Keterangan
- Tersedia: Boolean

Catatan:

- Untuk "Quotation via Online" adalah penawaran yang diisikan secara web oleh customer langsung. Sehingga tidak akan ada selectbox untuk barang dan customer. Fields text harus ada.

**Quotation via Online**

1. Nama Customer: Diisi manual
2. Nama Barang: Diisi manual

**Quotation**

1. Quotation: Bisa pilih ID dari **Quotation via Online**.
2. Customer: Seperti biasa pakai selectbox
3. Barang: Pilih dari master data

### Sales Order

- Ref No
- Ref No(Ext): Keterangan, "Nomor PO customer"
- Customer
- Created
- Notes

Detail:

- Quotation Detail ID: Optional
- Barang
- Qty
- Harga
- Keterangan

### Surat Jalan

- Ref No
- Customer
- Created
- Notes

Detail:

- PO Detail ID
- Barang
- Qty
- Harga
- Keterangan

### Invoice

- Ref No
- Customer
- Created
- Due Date
- Notes

Detail:

- SJ Detail ID
- Barang
- Qty
- Harga
- Keterangan

### Faktur Pajak

*Menyusul*

### Customer

- Ref No: Text
- Company Name: Text
- Group: Text
- NPWP: Text
- Notes: Text
- Due Date: Date

