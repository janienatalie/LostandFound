<style>

  footer.footer * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
}
  
  footer.footer {
  display: flex;
  justify-content: center; /* Menempatkan konten di tengah horizontal */
  align-items: center; /* Menempatkan konten di tengah vertikal */
  width: 100%; /* Lebar footer sesuai lebar halaman */
  height: 70px; /* Tinggi footer */
  bottom: 0; /* Footer tetap berada di bawah */
  background-color: #763996; /* Warna latar footer */
  z-index: 1000;
  margin-top: 50px;
}

footer.footer .copyrights-lost {
  display: flex;
  justify-content: flex-end; /* Menempatkan teks copyright di tengah secara horizontal */
  margin-right: 40px;
  font-family: "Open Sans-Regular", Helvetica;
  font-weight: 400;
  color: #ffffff;
  font-size: 16px;
  width: 100%; /* Memastikan lebar teks mengisi seluruh footer */
  flex-wrap: wrap;
  gap: 5px;
}

.span {
  color: #ffffff;
}

.text-wrapper-2 {
  font-family: "Open Sans-Bold", Helvetica;
  font-weight: 700;
}

    /* Responsive styling */
    @media screen and (max-width: 768px) {
        footer.footer {
            height: auto; /* Tinggi menyesuaikan konten */
            padding: 15px 10px; /* Padding lebih kecil */
        }

        footer.footer .copyrights-lost {
            justify-content: center; /* Text center pada layar kecil */
            margin-right: 0;
            text-align: center;
            font-size: 14px; /* Font lebih kecil */
        }
    }

    @media screen and (max-width: 480px) {
        footer.footer {
            padding: 10px 5px;
        }

        footer.footer .copyrights-lost {
            flex-direction: column; /* Stack vertikal pada layar sangat kecil */
            font-size: 12px;
            gap: 2px;
        }

        footer.footer.copyrights-lost span {
            text-align: center;
            width: 100%;
        }
    }
</style>

<footer class="footer">
      <p class="copyrights-lost">
        <span class="span">Copyrights © 2024 </span>
        <span class="text-wrapper-2">Lost and Found.</span>
        <span class="span">Kelompok 6.</span>
      </p>
    </footer>