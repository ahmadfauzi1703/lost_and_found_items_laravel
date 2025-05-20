<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Approval</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <!-- BX BX ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <div class="w-[15rem] flex-shrink-0 bg-[#124076] text-white">
            <img
                class="h-[5rem] m-auto mt-[1rem]"
                src="{{ asset('assets/img/lostnfoundlogowhite.png') }}" />
            <ul class="mt-6 space-y-2">
                <li>
                    <a href="{{ route('admin_dashboard') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-dashboard'></i> Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('admin_dashboard_approval') }}" class="block px-4 py-2 bg-[#1E5CB8]"><i class='bx bxs-check-circle'></i> Approval</a>
                </li>
                <li>
                    <a href="{{ route('admin_dashboard_lost') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-box'></i> Items Lost</a>
                </li>
                <li>
                    <a href="{{ route('admin_dashboard_found') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-box'></i> Items Found</a>
                </li>
                <li>
                    <a href="{{ route('admin_dashboard_user') }}" class="block px-4 py-2 hover:bg-[#4973b3]"><i class='bx bxs-user-circle'></i> Users</a>
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
        <!-- Main Content -->
        <div class="flex-1 p-3 overflow-hidden  ">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Report Approval</h2>

                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                <div class="flex flex-col">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-[#124076]">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Item Name</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Category</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Type</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Date</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Description</th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-white uppercase">Status</th>
                                            <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-white uppercase">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @if(count($pendingItems) > 0)
                                        @foreach($pendingItems as $item)
                                        <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $item->item_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->category }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $item->date_of_event }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-800 max-w-xs truncate">{{ $item->description }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                                <span class="px-2 py-1 rounded text-white bg-yellow-500 text-xs font-medium">
                                                    Pending
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                <div class="flex justify-end space-x-2">
                                                    <form action="{{ route('admin.approve.item', $item->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-green-600 hover:text-green-800 focus:outline-hidden focus:text-green-800">
                                                            Approve
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('admin.reject.item', $item->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:text-red-800 focus:outline-hidden focus:text-red-800">
                                                            Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="bg-white">
                                            <td colspan="7" class="px-6 py-4 text-sm text-gray-800 text-center">No pending items found</td>
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
    <footer class="bg-gray-800 text-white text-center py-4 fixed bottom-0 w-full -z-50">
        Dibuat dengan 💙 oleh © 2025 Lost and Found items Team
    </footer>
</body>

</html>