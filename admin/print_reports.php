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
        // Logo
        $headerfont = $this->getHeaderFont();
        $headerdata = $this->getHeaderData();
        $this->SetTextColorArray([0, 0, 0]); 
        
        // Title
        $this->SetFont('helvetica', 'B', 20); // Atur ukuran font judul ke 20
        $this->Cell(0, 15, $headerdata['title'], 0, false, 'L', 0, '', 0, false, 'M', 'M');
        
        // Sub Title
        $this->Ln(10); // Beri jarak
        $this->SetFont('helvetica', 'I', 16); // Atur ukuran font subtitle ke 12
        $this->Cell(0, 15, $headerdata['string'], 0, false, 'L', 0, '', 0, false, 'M', 'M');
        
        // Garis bawah
        // $this->SetLineStyle(array('width' => 0.85 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $this->header_line_color));
        // $this->SetY((2.835 / $this->k) + max($imgy, $this->y));
        // if ($this->rtl) {
        //     $this->SetX($this->original_rMargin);
        // } else {
        //     $this->SetX($this->original_lMargin);
        // }
        // $this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'T', 0, 'C');
    }
}

// Penggunaan:
$pdf = new MYPDF('L', 'mm', 'A3', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);    
$pdf->SetAuthor('Delia');    
$pdf->SetTitle('Laporan Lost & Found');    
$pdf->SetHeaderData('', 0, 'Laporan Lost & Found', 'Credit by Kelompok 6', array(0,64,255), array(0,64,128));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));    
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));    
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margin yang lebih kecil untuk ruang tabel lebih besar
$pdf->SetMargins(10, 25, 10); 
$pdf->SetHeaderMargin(10);   
$pdf->SetAutoPageBreak(TRUE, 10);    
$pdf->AddPage();    

// Mendefinisikan kolom
$kolomHeader = ['No', 'Nama', 'NPM', 'Kampus', 'Barang', 'Lokasi', 'Tanggal', 'Foto', 'Status'];

// Hitung total lebar halaman yang tersedia
$pageWidth = $pdf->getPageWidth() - $pdf->getMargins()['left'] - $pdf->getMargins()['right'];

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

// Fungsi untuk mencetak header dengan proporsi otomatis
function printTableHeader($pdf, $kolomHeader, $pageWidth) {
    $pdf->SetFont('helvetica', 'B', HEADER_FONT_SIZE);
    $pdf->SetFillColor(220, 220, 220);
    
    // Hitung proporsi kolom
    $totalKolom = count($kolomHeader);
    $defaultWidth = $pageWidth / $totalKolom;
    
    // Berikan proporsi khusus untuk kolom tertentu
    $proportions = [
        0 => 0.3,    // No (setengah dari default)
        2 => 0.7,
        3 => 0.7,
        7 => 1.3,    // Foto (1.5x dari default)
        6 => 0.8,
        1 => 0.8,    // Nama (1.2x dari default)
    ];
    
    // Hitung total proporsi
    $totalProportion = $totalKolom - count($proportions) + array_sum($proportions);
    
    // Hitung lebar default untuk kolom yang tidak memiliki proporsi khusus
    $standardWidth = $pageWidth / $totalProportion;
    
    foreach ($kolomHeader as $i => $header) {
        $width = isset($proportions[$i]) ? $standardWidth * $proportions[$i] : $standardWidth;
        $pdf->Cell($width, ROW_HEIGHT, $header, 1, 0, 'C', true);
    }
    
    $pdf->Ln();
    $pdf->SetFont('helvetica', '', CONTENT_FONT_SIZE);
    
    return array_map(function($i) use ($proportions, $standardWidth) {
        return isset($proportions[$i]) ? $standardWidth * $proportions[$i] : $standardWidth;
    }, array_keys($kolomHeader));
}

// Cetak header dan dapatkan array lebar kolom
$columnWidths = printTableHeader($pdf, $kolomHeader, $pageWidth);

// Data tabel
$no = 1;
foreach ($data as $row) {
    // Cek kebutuhan halaman baru
    if($pdf->GetY() > $pdf->getPageHeight() - 20) {
        $pdf->AddPage();
        $columnWidths = printTableHeader($pdf, $kolomHeader, $pageWidth);
    }
    
    $startY = $pdf->GetY();
    $rowHeight = 55; // Tinggi baris tetap untuk konsistensi
    
    // Cetak data
    $pdf->SetFont('helvetica', '', CONTENT_FONT_SIZE);
    $pdf->Cell($columnWidths[0], $rowHeight, $no++, 1, 0, 'C');
    $pdf->Cell($columnWidths[1], $rowHeight, $row['nama'], 1, 0, 'C');
    $pdf->Cell($columnWidths[2], $rowHeight, $row['npm'], 1, 0, 'C');
    $pdf->Cell($columnWidths[3], $rowHeight, $row['kampus'], 1, 0, 'C');
    $pdf->Cell($columnWidths[4], $rowHeight, $row['barang'], 1, 0, 'C');
    $pdf->Cell($columnWidths[5], $rowHeight, $row['lokasi'], 1, 0, 'C');
    $pdf->Cell($columnWidths[6], $rowHeight, formatTanggalIndonesia($row['tanggal']), 1, 0, 'C');
    
    // Handle gambar
    $startX = $pdf->GetX();
    $imagePath = '../uploads/' . $row['foto'];
    $imageType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
    
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
        
        $imgX = $startX + ($cellWidth - $imgWidth) / 2;
        $imgY = $startY + ($rowHeight - $imgHeight) / 2;
        
        $pdf->Cell($cellWidth, $rowHeight, '', 1, 0, 'C');
        $pdf->Image($imagePath, $imgX, $imgY, $imgWidth, $imgHeight, $imageType);
    } else {
        $pdf->Cell($columnWidths[7], $rowHeight, 'Gambar tidak ditemukan', 1, 0, 'C');
    }
    
    $pdf->Cell($columnWidths[8], $rowHeight, $row['status'], 1, 0, 'C');
    $pdf->Ln();
}

$conn->close();    
$pdf->Output('Laporan_Lost_Found.pdf', 'I');
?>