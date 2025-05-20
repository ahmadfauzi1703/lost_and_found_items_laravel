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
</head>

<body class="bg-gray-100">
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
                    <a href="#" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bxs-check-circle'></i> Approval</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bxs-box'></i> Items Lost</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bxs-box'></i> Items Found</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-[#5C5470]"><i class='bx bxs-user-circle'></i> Users</a>
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
                            <h3 class="text-lg font-semibold">Total Report</h3>
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
                    <!-- Claimed Items -->
                    <div class="bg-white p-4 rounded-lg shadow-md flex items-center border-l-4 border-t-4 border-green-600">
                        <i class='bx  bx-box text-3xl text-green-600 mr-4'></i>
                        <div>
                            <h3 class="text-lg font-semibold">Claimed Items</h3>
                            <p class="text-xl font-bold">0</p> <!-- Ganti angka 0 dengan variabel userCount -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Recent Activity</h2>

                <div class="flex flex-col">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class=" bg-[#393646]">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Item Name</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Category</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Date of Events</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Description</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Location</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Email Report</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Phone</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Item Picture</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($items as $item)
                                        <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $item->item_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->category }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->date_of_event }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate">{{ $item->description }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->location }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->phone_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                @if($item->photo_path)
                                                <img src="{{ asset('storage/' . $item->photo_path) }}" alt="Item Image" class="w-16 h-16 object-cover rounded">
                                                @else
                                                <img src="{{ asset('Assets/img/no-image.png') }}" alt="No Image" class="w-16 h-16 object-cover rounded">
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach

                                        @if(count($items) === 0)
                                        <tr class="bg-white">
                                            <td colspan="8" class="px-6 py-4 text-sm text-gray-800 text-center">No items found</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-[#6D5D6E] text-white text-center py-4 fixed bottom-0 w-full -z-50">
        Dibuat dengan 💙 oleh © 2025 Lost and Found items Team
    </footer>
</body>

</html>