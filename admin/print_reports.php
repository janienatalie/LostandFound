<?php  
include '../database/config.php';

// Mendapatkan data dari query string  
$data = isset($_GET['data']) ? json_decode($_GET['data'], true) : [];  
 
// Cek apakah data ada  
if (empty($data)) {  
    die("Tidak ada data untuk dicetak.");  
}  
 
// Membuat objek TCPDF    
if (!file_exists('../assets/vendor/tcpdf/tcpdf.php')) {
    die('File TCPDF tidak ditemukan.');
}
require_once('../assets/vendor/tcpdf/tcpdf.php');   

$pdf = new TCPDF('P', 'mm', 'B3', true, 'UTF-8', false); // Mengatur ukuran kertas A4    
$pdf->SetCreator(PDF_CREATOR);    
$pdf->SetAuthor('Delia');    
$pdf->SetTitle('Laporan Lost & Found');    
$pdf->SetHeaderData('', 0, 'Laporan Lost & Found', 'Credit by Kelompok 6', 0, 5);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));    
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));    
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);    
$pdf->SetMargins(10, 25, 9); // Mengatur margin 
$pdf->SetHeaderMargin(8);   
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);    
$pdf->AddPage();    
   
// Mendefinisikan kolom dan lebar default awal
$kolomHeader = ['No', 'Nama', 'NPM', 'Kampus', 'Barang', 'Lokasi', 'Tanggal', 'Foto', 'Status'];
$lebarKolom = [10, 35, 30, 30, 45, 45, 25, 60, 42];  // Lebar awal

// Menghitung lebar kolom berdasarkan isi data
foreach ($data as $row) {
    $lebarKolom[1] = max($lebarKolom[1], $pdf->GetStringWidth($row['nama']) + 5);
    $lebarKolom[2] = max($lebarKolom[2], $pdf->GetStringWidth($row['npm']) + 5);
    $lebarKolom[4] = max($lebarKolom[4], $pdf->GetStringWidth($row['barang']) + 5);
    $lebarKolom[5] = max($lebarKolom[5], $pdf->GetStringWidth($row['lokasi']) + 5);
    $lebarKolom[6] = max($lebarKolom[6], $pdf->GetStringWidth($row['tanggal']) + 5);
    $lebarKolom[8] = max($lebarKolom[8], $pdf->GetStringWidth($row['status']) + 5);
}

// Header tabel
$pdf->SetFont('helvetica', 'B', 12);
foreach ($kolomHeader as $index => $header) {
    $pdf->Cell($lebarKolom[$index], 10, $header, 1, 0, 'C');
}
$pdf->Ln();

// Data tabel
$no = 1;
foreach ($data as $row) {
    $pdf->Cell($lebarKolom[0], 25, $no++, 1, 0, 'C');
    $pdf->Cell($lebarKolom[1], 25, $row['nama'], 1, 0, 'C');
    $pdf->Cell($lebarKolom[2], 25, $row['npm'], 1, 0, 'C');
    $pdf->Cell($lebarKolom[3], 25, $row['kampus'], 1, 0, 'C');
    $pdf->Cell($lebarKolom[4], 25, $row['barang'], 1, 0, 'C');
    $pdf->Cell($lebarKolom[5], 25, $row['lokasi'], 1, 0, 'C');
    $pdf->Cell($lebarKolom[6], 25, $row['tanggal'], 1, 0, 'C');
    
    // Mengatur gambar jika ada
        // Mengatur gambar jika ada dan file gambar memiliki ekstensi yang valid
        $imagePath = '../uploads/' . $row['foto'];
        $imageType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
    
        if (file_exists($imagePath) && in_array($imageType, ['jpg', 'jpeg'])) {
            $startX = $pdf->GetX();  // Posisi awal X
            $startY = $pdf->GetY();  // Posisi awal Y
            $cellWidth = $lebarKolom[7];  // Lebar sel foto
            $cellHeight = 25;  // Tinggi sel foto
            
            // Tampilkan sel kosong untuk foto
            $pdf->Cell($cellWidth, $cellHeight, '', 1, 0, 'C');
            
            // Tentukan ukuran gambar (lebar maksimal 20, tinggi maksimal 20)
            $imgWidth = min($cellWidth - 4, 25);
            $imgHeight = min($cellHeight - 4, 25);
            
            // Hitung posisi tengah gambar dalam sel
            $imgX = $startX + ($cellWidth - $imgWidth) / 2;
            $imgY = $startY + ($cellHeight - $imgHeight) / 2;
            
            // Tentukan format gambar yang sesuai
            $imgFormat = ($imageType === 'png') ? 'PNG' : 'JPG';
    
            // Tambahkan gambar ke dalam posisi yang sesuai
            $pdf->Image($imagePath, $imgX, $imgY, $imgWidth, $imgHeight, $imgFormat);
        } else {
            $pdf->Cell($lebarKolom[7], 25, 'Gambar tidak ditemukan', 1, 0, 'C');
        }
        $pdf->Cell($lebarKolom[8], 25, $row['status'], 1, 0, 'C');
        $pdf->Ln();

}
   
// Menutup koneksi    
$conn->close();    
   
// Output PDF    
$pdf->Output('Laporan_Lost_Found.pdf', 'I'); // 'I' untuk menampilkan di browser    
?>

