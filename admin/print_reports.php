<?php
include '../database/config.php';

// Mendapatkan data dari query string  
$data = isset($_GET['data']) ? json_decode($_GET['data'], true) : [];  

if (empty($data)) {  
    die("Tidak ada data untuk dicetak.");  
}  

if (!file_exists('../assets/vendor/tcpdf/tcpdf.php')) {
    die('File TCPDF tidak ditemukan.');
}
require_once('../assets/vendor/tcpdf/tcpdf.php');   

// Konstanta untuk style
define('HEADER_FONT_SIZE', 14);
define('CONTENT_FONT_SIZE', 14);
define('ROW_HEIGHT', 12);

class MYPDF extends TCPDF {
    public function Header() {
        $headerfont = $this->getHeaderFont();
        $headerdata = $this->getHeaderData();
        $this->SetTextColorArray([0, 0, 0]); 
        
        // Title
        $this->SetFont('helvetica', 'B', 20);
        $this->Cell(0, 15, $headerdata['title'], 0, false, 'L', 0, '', 0, false, 'M', 'M');
        
        // Sub Title
        $this->Ln(10);
        $this->SetFont('helvetica', 'I', 16);
        $this->Cell(0, 15, $headerdata['string'], 0, false, 'L', 0, '', 0, false, 'M', 'M');
    }
}

// Fungsi untuk format tanggal
function formatTanggalIndonesia($tanggal) {
    $bulanNama = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $parts = explode('-', $tanggal);
    $tahun = $parts[2];
    $bulan = (int)$parts[1];
    $hari = $parts[0];
    
    return "$hari {$bulanNama[$bulan]} $tahun";
}

// Inisialisasi PDF
$pdf = new MYPDF('L', 'mm', 'A3', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);    
$pdf->SetAuthor('Delia');    
$pdf->SetTitle('Laporan Lost & Found');    
$pdf->SetHeaderData('', 0, 'Laporan Lost & Found', 'Credit by Kelompok 6', array(0,64,255), array(0,64,128));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));    
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));    
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 25, 10); 
$pdf->SetHeaderMargin(10);   
$pdf->SetAutoPageBreak(TRUE, 10);    

// Mendefinisikan kolom
$kolomHeader = ['No', 'Nama', 'NPM', 'Kampus', 'Barang', 'Lokasi', 'Tanggal', 'Foto', 'Status'];

// Fungsi untuk mencetak header dengan proporsi otomatis
function printTableHeader($pdf, $kolomHeader, $pageWidth) {
    $pdf->SetFont('helvetica', 'B', HEADER_FONT_SIZE);
    $pdf->SetFillColor(220, 220, 220);
    
    // Hitung proporsi kolom
    $proportions = [
        0 => 0.3,    // No
        2 => 0.5,    // NPM
        3 => 0.7,    // Kampus
        7 => 1.3,    // Foto
        6 => 0.8,    // Tanggal
        1 => 0.8,    // Nama
        4 => 1.0,    // Barang
        5 => 1.0,    // Lokasi
        8 => 0.9,    // Status
    ];
    
    $totalKolom = count($kolomHeader);
    $totalProportion = array_sum($proportions);
    $standardWidth = $pageWidth / $totalProportion;
    
    $columnWidths = [];
    foreach ($kolomHeader as $i => $header) {
        $width = $standardWidth * ($proportions[$i] ?? 1);
        $columnWidths[] = $width;
        $pdf->Cell($width, ROW_HEIGHT, $header, 1, 0, 'C', true);
    }
    
    $pdf->Ln();
    $pdf->SetFont('helvetica', '', CONTENT_FONT_SIZE);
    
    return $columnWidths;
}

// Fungsi untuk mencetak MultiCell dengan rata tengah
// Fungsi untuk mencetak MultiCell dengan rata tengah
function writeAlignedCell($pdf, $w, $h, $text, $border=1, $align='C', $fill=false) {
    // Simpan posisi awal
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    
    // Hitung tinggi teks yang dibutuhkan
    $pdf->startTransaction();
    $pdf->SetX($x);
    // Gunakan tinggi baris yang lebih kecil untuk kalkulasi
    $pdf->MultiCell($w, 5, $text, 0, $align, $fill);
    $textHeight = $pdf->GetY() - $y;
    $pdf->rollbackTransaction(true);
    
    // Hitung offset untuk penempatan vertikal di tengah
    $yOffset = max(0, ($h - $textHeight) / 2);
    
    // Gambar border dengan tinggi penuh
    $pdf->SetXY($x, $y);
    $pdf->Cell($w, $h, '', $border, 0, $align, $fill);
    
    // Tulis teks di tengah cell
    $pdf->SetXY($x, $y + $yOffset);
    $pdf->MultiCell($w, 5, $text, 0, $align, $fill);
    
    // Kembalikan ke posisi untuk cell berikutnya
    $pdf->SetXY($x + $w, $y);
}

function printTableRow($pdf, $row, $columnWidths, $no, $rowHeight = 55) {
    $startY = $pdf->GetY();
    $startX = $pdf->GetX();
    $currentX = $startX;
    
    // Array data yang akan dicetak
    $data = [
        $no,
        $row['nama'],
        $row['npm'],
        $row['kampus'],
        $row['barang'],
        $row['lokasi'],
        formatTanggalIndonesia($row['tanggal'])
    ];
    
    // Cetak semua kolom teks
    foreach ($data as $i => $text) {
        $pdf->SetXY($currentX, $startY);
        if ($i == 4 || $i == 5 || strlen($text) > 30) { // barang dan lokasi
            writeAlignedCell($pdf, $columnWidths[$i], $rowHeight, $text);
        } else {
            $pdf->Cell($columnWidths[$i], $rowHeight, $text, 1, 0, 'C');
        }
        $currentX += $columnWidths[$i];
    }
    
    // Handle gambar
    $imagePath = '../uploads/' . $row['foto'];
    $imageType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
    
    // Cetak cell untuk foto
    $pdf->Cell($columnWidths[7], $rowHeight, '', 1, 0, 'C');
    
    if (file_exists($imagePath) && in_array($imageType, ['jpg', 'jpeg', 'png'])) {
        list($width, $height) = getimagesize($imagePath);
        $ratio = $width / $height;
        
        $cellWidth = $columnWidths[7];
        if ($width > $height) {
            $imgWidth = min($width, $cellWidth - 10);
            $imgHeight = $imgWidth / $ratio;
            if ($imgHeight > ($rowHeight - 10)) {
                $imgHeight = $rowHeight - 10;
                $imgWidth = $imgHeight * $ratio;
            }
        } else {
            $imgHeight = min($height, $rowHeight - 10);
            $imgWidth = $imgHeight * $ratio;
            if ($imgWidth > ($cellWidth - 10)) {
                $imgWidth = $cellWidth - 10;
                $imgHeight = $imgWidth / $ratio;
            }
        }
        
        $imgX = $currentX + ($cellWidth - $imgWidth) / 2;
        $imgY = $startY + ($rowHeight - $imgHeight) / 2;
        
        $pdf->Image($imagePath, $imgX, $imgY, $imgWidth, $imgHeight, $imageType);
    }
    
    // Cetak status
    $currentX += $columnWidths[7];
    $pdf->SetXY($currentX, $startY);
    $pdf->Cell($columnWidths[8], $rowHeight, $row['status'], 1, 1, 'C');
    
    return $rowHeight;
}
// Mulai halaman pertama
$pdf->AddPage();
$pageWidth = $pdf->getPageWidth() - $pdf->getMargins()['left'] - $pdf->getMargins()['right'];
$columnWidths = printTableHeader($pdf, $kolomHeader, $pageWidth);

// Cetak data
$no = 1;
foreach ($data as $row) {
    // Cek apakah perlu halaman baru
    if ($pdf->GetY() + 55 > $pdf->getPageHeight() - 20) {
        $pdf->AddPage();
        $columnWidths = printTableHeader($pdf, $kolomHeader, $pageWidth);
    }
    
    // Cetak baris
    printTableRow($pdf, $row, $columnWidths, $no++);
}

$conn->close();    
$pdf->Output('Laporan_Lost_Found.pdf', 'I');
?>