<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Satpam Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <!-- BX BX ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100">
    <!-- Flash Message -->
    @if(session('success'))
    <div id="flash-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
        <button class="ml-4" onclick="document.getElementById('flash-message').remove()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <script>
        setTimeout(() => {
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                flashMessage.style.transition = 'opacity 0.5s ease';
                flashMessage.style.opacity = '0';
                setTimeout(() => {
                    if (flashMessage) flashMessage.remove();
                }, 500);
            }
        }, 5000);
    </script>
    @endif
    <!-- Sidebar -->
    <div class="flex h-screen">
        <div class="w-[15rem] bg-[#393646] text-white">
            <img
                class="h-[6rem] m-auto mt-[1rem]"
                src="{{ asset('assets/img/laflogoputih.png') }}" />
            <ul class="mt-6 space-y-2">
                <li>
                    <a href="" class="block px-4 py-2 bg-[#5C5470]"><i class='bx bxs-dashboard'></i> Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.create') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx  bx-box'></i> Add Items</a>
                </li>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.view') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-list-ul'></i> List Item</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.createClaim') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-clipboard'></i> Laporan Claim Barang</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.viewHistory') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-history'></i> History Claim Barang</a>
                </li>
                <li>
                    <a href="{{ route('satpam.dashboard.profile') }}" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bx-user'></i> Profile Satpam</a>
                </li>
            </ul>
            <!-- Logout Button -->
            <div class="mt-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block px-4 py-2 text-red-500 hover:text-white"><i class='bx bxs-exit'></i> Logout</button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-3">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Dashboard</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Unaproved Report -->
                    <div class="bg-white p-4 rounded-lg shadow-md flex items-center border-l-4 border-t-4 border-yellow-600">
                        <i class='bx bx-doughnut-chart text-4xl text-yellow-600 mr-4'></i>
                        <div>
                            <h3 class="text-lg font-semibold">Unaproved Report</h3>
                            <p class="text-xl font-bold">{{ $unapprovedCount }}</p> <!-- Ganti angka 0 dengan variabel userCount -->
                        </div>
                    </div>
                    <!-- Total Report -->
                    <div class="bg-white p-4 rounded-lg shadow-md flex items-center border-l-4 border-t-4 border-green-600">
                        <i class='bx bxs-group text-3xl text-green-600 mr-4'></i>
                        <div>
                            <h3 class="text-lg font-semibold">Registred Items</h3>
                            <p class="text-xl font-bold">{{ $totalReports }}</p> <!-- Ganti angka 0 dengan variabel userCount -->
                        </div>
                    </div>
                    <!-- Total Users -->
                    <div class="bg-white p-4 rounded-lg shadow-md flex items-center border-l-4 border-t-4 border-[#124076]">
                        <i class='bx bxs-group text-3xl text-blue-800 mr-4'></i>
                        <div>
                            <h3 class="text-lg font-semibold">Total Users</h3>
                            <p class="text-xl font-bold">{{ $userCount }}</p> <!-- Ganti angka 0 dengan variabel userCount -->
                        </div>
                    </div>
                    <!-- Lost Items -->
                    <div class="bg-white p-4 rounded-lg shadow-md flex items-center border-l-4 border-t-4 border-red-600">
                        <i class='bx bxs-help-circle text-3xl text-red-600 mr-4'></i>
                        <div>
                            <h3 class="text-lg font-semibold">Lost Items Report</h3>
                            <p class="text-xl font-bold">{{ $lostItemsCount }}</p>
                        </div>
                    </div>
                    <!-- Found Items -->
                    <div class="bg-white p-4 rounded-lg shadow-md flex items-center border-l-4 border-t-4 border-green-600">
                        <i class='bx bxs-check-circle text-3xl text-green-600 mr-4'></i>
                        <div>
                            <h3 class="text-lg font-semibold">Found Items Report</h3>
                            <p class="text-xl font-bold">{{ $foundItemsCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Activity Report</h2>

                <!-- Ubah dari flex menjadi flex-col (kolom) -->
                <div class="flex flex-col gap-4">
                    <!-- Chart dengan lebar penuh -->
                    <div class="w-full">
                        <canvas id="reportsChart" height="300"></canvas>
                    </div>

                    <!-- Statistik di bawah chart -->
                    <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">Statistik</h3>
                        <div class="flex justify-center gap-8">
                            <div class="text-center">
                                <span class="block text-gray-600">Bulan tertinggi</span>
                                <span class="font-semibold text-xl">{{ $highestMonth ?? 'N/A' }}</span>
                            </div>
                            <div class="text-center">
                                <span class="block text-gray-600">Rata-rata laporan</span>
                                <span class="font-semibold text-xl">{{ $averageReports ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dengan ini -->
    <footer class="bg-[#6D5D6E] text-white text-center py-4 fixed bottom-0 w-full">
        Dibuat dengan 💙 oleh © 2025 Lost and Found items Team
    </footer>


    <!-- JS SECTION -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data dari controller
            const monthlyData = JSON.parse('{!! json_encode($monthlyReports ?? [], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) !!}');

            // Mendapatkan label bulan (Jan-Des)
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            // Pastikan ada data untuk 12 bulan
            const reportCounts = Array(12).fill(0);

            // Isi data yang ada
            if (monthlyData && monthlyData.length > 0) {
                monthlyData.forEach(item => {
                    // Kurangi 1 karena array dimulai dari 0 tetapi bulan dari 1
                    reportCounts[item.month - 1] = item.count;
                });
            }

            // Buat chart
            const ctx = document.getElementById('reportsChart').getContext('2d');
            const reportsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Total Laporan',
                        data: reportCounts,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0 // Hanya tampilkan bilangan bulat
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Laporan Barang Bulanan',
                            font: {
                                size: 16
                            }
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>