<?php  
   include '../database/config.php';
  

// Mendapatkan data dari query string  
$data = isset($_GET['data']) ? json_decode($_GET['data'], true) : [];  
 
// Cek apakah data ada  
if (empty($data)) {  
    die("Tidak ada data untuk dicetak.");  
}  
 
// Membuat objek TCPDF    
require('tcpdf/tcpdf.php');    
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false); // Mengatur ukuran kertas A4    
$pdf->SetCreator(PDF_CREATOR);    
$pdf->SetAuthor('Delia');    
$pdf->SetTitle('Laporan Lost & Found');    
$pdf->SetHeaderData('', 0, 'Laporan Lost & Found', 'Generated by Your Application');    
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));    
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));    
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);    
$pdf->SetMargins(4, 10, 1); // Mengatur margin    
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);    
$pdf->AddPage();    
   
// Header tabel    
$pdf->SetFont('helvetica', 'B', 12);    
$pdf->Cell(10, 10, 'No', 1, 0, 'C');    
$pdf->Cell(30, 10, 'Nama', 1, 0, 'C');    
$pdf->Cell(25, 10, 'NPM', 1, 0, 'C');    
$pdf->Cell(30, 10, 'Kampus', 1, 0, 'C');    
$pdf->Cell(25, 10, 'Barang', 1, 0, 'C');    
$pdf->Cell(20, 10, 'Lokasi', 1, 0, 'C');    
$pdf->Cell(25, 10, 'Tanggal', 1, 0, 'C');    
$pdf->Cell(40, 10, 'Status', 1, 1, 'C'); // Menambahkan kolom Status    
   
// Data tabel    
$pdf->SetFont('helvetica', '', 12);    
$no = 1;    
foreach ($data as $row) {    
    $pdf->Cell(10, 10, $no++, 1, 0, 'C');    
    $pdf->Cell(30, 10, $row['nama'], 1, 0, 'L');    
    $pdf->Cell(25, 10, $row['npm'], 1, 0, 'C');    
    $pdf->Cell(30, 10, $row['kampus'], 1, 0, 'C');    
    $pdf->Cell(25, 10, $row['barang'], 1, 0, 'L');    
    $pdf->Cell(20, 10, $row['lokasi'], 1, 0, 'L');    
    $pdf->Cell(25, 10, $row['tanggal'], 1, 0, 'C');    
    $pdf->Cell(40, 10, $row['status'], 1, 1, 'C'); // Menambahkan status    
}     
   
// Menutup koneksi    
$conn->close();    
   
// Output PDF    
$pdf->Output('Laporan_Lost_Found.pdf', 'I'); // 'I' untuk menampilkan di browser    
?>

