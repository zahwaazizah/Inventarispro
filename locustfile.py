from locust import HttpUser, task, between
from bs4 import BeautifulSoup


class BaseInventarisUser(HttpUser):
    abstract = True
    wait_time = between(1, 2)

    def get_csrf_token(self, url):
        response = self.client.get(url, name=f"GET {url} - Ambil CSRF")

        if response.status_code != 200:
            return None

        soup = BeautifulSoup(response.text, "html.parser")
        token = soup.find("input", {"name": "_token"})

        if token:
            return token.get("value")

        return None

    def login(self, email, password, role_name):
        token = self.get_csrf_token("/login")

        if token is None:
            return

        self.client.post(
            "/login",
            data={
                "_token": token,
                "email": email,
                "password": password
            },
            name=f"{role_name} - Login"
        )


class AdminUser(BaseInventarisUser):
    weight = 1

    def on_start(self):
        self.login(
            "farissaelfira88@gmail.com",
            "12345678",
            "Admin"
        )

    @task(4)
    def dashboard_admin(self):
        self.client.get("/dashboard", name="Admin - Dashboard")

    @task(4)
    def inventaris(self):
        self.client.get("/inventaris", name="Admin - Inventaris")

    @task(3)
    def kategori(self):
        self.client.get("/kategori", name="Admin - Kategori")

    @task(3)
    def lokasi(self):
        self.client.get("/lokasi", name="Admin - Lokasi")

    @task(2)
    def kelola_user(self):
        # Kelola user hanya Admin
        self.client.get("/users", name="Admin - Kelola User")

    @task(3)
    def riwayat(self):
        self.client.get("/riwayat", name="Admin - Riwayat Transaksi")

    @task(2)
    def laporan(self):
        self.client.get("/laporan", name="Admin - Laporan")


class PetugasUser(BaseInventarisUser):
    weight = 1

    def on_start(self):
        self.login(
            "azizahzahwa0@gmail.com",
            "12345678",
            "Petugas"
        )

    @task(4)
    def dashboard_petugas(self):
        self.client.get("/dashboard", name="Petugas - Dashboard")

    @task(4)
    def inventaris(self):
        self.client.get("/inventaris", name="Petugas - Lihat Inventaris")

    @task(3)
    def halaman_scan_qr(self):
        # Menguji halaman scan QR, bukan kamera secara langsung
        self.client.get("/qr/scan", name="Petugas - Halaman Scan QR")

    @task(3)
    def peminjaman_aktif(self):
        self.client.get("/transaksi", name="Petugas - Peminjaman Aktif")

    @task(3)
    def riwayat(self):
        self.client.get("/riwayat", name="Petugas - Riwayat Transaksi")