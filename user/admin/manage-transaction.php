<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Transactions - Gymaster Admin</title>
    <!-- Add Google Fonts - Poppins with multiple weights -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../styles/admin-styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            dark: '#081738',
                            light: '#5C6C90'
                        },
                        secondary: '#647590',
                        tertiary: '#A5B3C9',
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom styles for filters section */
        .flatpickr-calendar {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            font-family: 'Poppins', sans-serif;
        }
        
        .flatpickr-day.selected {
            background: #081738;
            border-color: #081738;
        }
        
        .flatpickr-day.selected:hover {
            background: #081738;
            border-color: #081738;
        }
        
        .flatpickr-day:hover {
            background: #f0f4f8;
        }
        
        /* Custom select styling */
        select {
            background-image: none;
            appearance: none;
        }
        
        /* Reset filters button */
        #resetFiltersBtn {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            color: #495057;
        }
        
        #resetFiltersBtn:hover {
            background-color: #e9ecef;
        }
        
        /* Apply filters button */
        #applyFiltersBtn {
            background-color: #081738;
        }
        
        #applyFiltersBtn:hover {
            background-color: #0f2a5c;
        }
        
        /* Input focus styles */
        input:focus, select:focus {
            outline: none;
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
        }
    </style>
    <!-- Add CSS for the member search suggestions -->
    <style>
        /* Autocomplete suggestions styling */
        .autocomplete-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 10;
            max-height: 300px;
            overflow-y: auto;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-top: 4px;
        }
        .autocomplete-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
        }
        .autocomplete-item:not(:last-child) {
            border-bottom: 1px solid #e2e8f0;
        }
        .autocomplete-item:hover, .autocomplete-item.selected {
            background-color: #f3f4f6;
        }
        .autocomplete-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #5C6C90;
            color: white;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            font-size: 0.75rem;
        }
        .autocomplete-info {
            flex: 1;
        }
        .autocomplete-name {
            font-weight: 500;
            color: #1a202c;
            line-height: 1.25;
        }
        .autocomplete-email {
            font-size: 0.75rem;
            color: #718096;
        }
        .member-search-wrapper {
            position: relative;
        }
        /* Ensure autocomplete z-index is high enough in modal */
        #addTransactionModal .autocomplete-suggestions,
        #addTransactionModal #memberSearchResults {
            z-index: 100;
            position: absolute;
            width: 100%;
        }
        /* Make sure both search results containers share the same styling */
        #memberSearchResults.autocomplete-suggestions,
        #addTransactionForm #memberSearchResults {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-top: 4px;
            max-height: 300px;
            overflow-y: auto;
        }
        /* Fix for date inputs to ensure they display properly */
        input[type="date"] {
            appearance: none;
            -webkit-appearance: none;
            position: relative;
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body class="font-poppins bg-gray-50">
    <!-- Mobile menu button -->
    <button data-drawer-target="sidebar-gymaster" data-drawer-toggle="sidebar-gymaster" aria-controls="sidebar-gymaster" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
        </svg>
    </button>
    
    <!-- Sidebar Navigation -->
    <aside id="sidebar-gymaster" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-hidden text-white relative flex flex-col sidebar-content">
            <!-- Animated background -->
            <div class="sidebar-background"></div>
            <!-- Logo Section - Centered and Enlarged -->
            <div class="flex items-center justify-center mb-3 pb-4 border-b border-white/10 relative">
                <img src="../../src/images/gymaster-logo.png" alt="Gymaster Logo" class="h-20 w-auto filter brightness-0 invert">
            </div>
            <nav class="flex-grow relative">
                <ul class="space-y-1 font-medium">
                    <!-- Dashboard -->
                    <li>
                        <a href="admin-dashboard.php" class="sidebar-menu-item">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <!-- Management Dropdown -->
                    <li class="mt-2">
                        <button type="button" class="sidebar-menu-item w-full justify-between" aria-controls="dropdown-management" data-collapse-toggle="dropdown-management">
                            <div class="flex items-center">
                                <i class="fas fa-th-large"></i>
                                <span>Management</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="management-chevron"></i>
                        </button>
                        <div id="dropdown-management" class="hidden overflow-hidden transition-all duration-300 ease-in-out">
                            <ul class="pt-1 pb-1">
                                <li>
                                    <a href="manage-users.php" class="sidebar-dropdown-item">User</a>
                                </li>
                                <li>
                                    <a href="manage-members.php" class="sidebar-dropdown-item">Member</a>
                                </li>
                                <li>
                                    <a href="manage-programs-coaches.php" class="sidebar-dropdown-item">Program & Coach</a>
                                </li>
                                <li>
                                    <a href="manage-comorbidities.php" class="sidebar-dropdown-item">Comorbidities</a>
                                </li>
                                <li>
                                    <a href="manage-subscription.php" class="sidebar-dropdown-item">Subscription</a>
                                </li>
                                <li>
                                    <a href="manage-payment.php" class="sidebar-dropdown-item">Payment</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- Transaction -->
                    <li class="mt-2">
                        <a href="manage-transaction.php" class="sidebar-menu-item active">
                            <i class="fas fa-exchange-alt"></i>
                            <span>Transaction</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- Logout placed at the very bottom -->
            <div class="mt-auto border-t border-white/10 relative">
                <a href="#" class="sidebar-menu-item text-white/90 hover:text-white mt-3" id="logoutBtn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main content -->
    <div class="p-0 sm:ml-64 main-content">
        <!-- Top Header -->
        <header class="admin-header shadow-sm mb-3">
            <div class="max-w-full px-6">
                <div class="flex justify-between items-center h-16">
                    <!-- Page Title -->
                    <h1 class="text-xl font-semibold text-primary-dark">Manage Transactions</h1>
                    
                    <!-- Right Section - User Profile and Notifications -->
                    <div class="flex items-center space-x-3">
                        <!-- Notification Bell -->
                        <div class="header-icon-button">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="notification-badge">3</span>
                        </div>
                        
                        <!-- Divider -->
                        <div class="h-8 w-px bg-gray-200 mx-2"></div>
                        
                        <!-- User Profile -->
                        <a href="edit-profile.php" class="flex items-center space-x-3 pr-2 cursor-pointer">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-medium text-gray-700">John Doe</p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center text-white">
                                <i class="fas fa-user text-lg"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Error Alert Banner -->
        <div id="errorAlert" class="hidden bg-red-600 text-white py-3 px-4 mb-4 mx-4 rounded-md flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span id="errorAlertMessage">Error applying filters. Please try again.</span>
            </div>
            <button onclick="document.getElementById('errorAlert').classList.add('hidden')" class="text-white focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="container mx-auto px-4 py-4">
            <!-- Transaction Summary Cards - Moved Above Filters -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition-shadow duration-300">
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Total Transactions</h3>
                    <p class="text-3xl font-bold text-gray-800" id="totalTransactions">0</p>
                    <div class="flex items-center mt-2">
                        <span class="text-green-600 text-sm mr-1" id="transactionGrowth">+0%</span>
                        <span class="text-gray-500 text-sm">vs previous period</span>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition-shadow duration-300">
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Total Revenue</h3>
                    <p class="text-3xl font-bold text-gray-800" id="totalRevenue">$0.00</p>
                    <div class="flex items-center mt-2">
                        <span class="text-green-600 text-sm mr-1" id="revenueGrowth">+0%</span>
                        <span class="text-gray-500 text-sm">vs previous period</span>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition-shadow duration-300">
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Recent Transactions</h3>
                    <p class="text-3xl font-bold text-gray-800" id="recentTransactions">0</p>
                    <div class="flex items-center mt-2">
                        <span class="text-green-600 text-sm mr-1">+0%</span>
                        <span class="text-gray-500 text-sm">vs previous period</span>
                    </div>
                </div>
                
                <!-- New Card: Expiring Subscriptions -->
                <div class="bg-white rounded-lg shadow-sm p-5 hover:shadow-md transition-shadow duration-300">
                    <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Expiring Soon</h3>
                    <p class="text-3xl font-bold text-orange-500" id="expiringSubscriptions">0</p>
                    <div class="flex items-center mt-2">
                        <span class="text-orange-600 text-sm mr-1">
                            <i class="fas fa-clock"></i>
                        </span>
                        <span class="text-gray-500 text-sm">In next 7 days</span>
                    </div>
                </div>
            </div>
            
            <!-- Transaction Filters Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-primary-dark mb-4">Transaction Filters</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Start Date -->
                    <div>
                        <label for="startDate" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <div class="relative rounded-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <input type="text" id="startDate" class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" placeholder="05/01/2025" readonly>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="document.getElementById('startDate').focus()">
                                    <i class="fas fa-calendar-day"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- End Date -->
                    <div>
                        <label for="endDate" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <div class="relative rounded-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <input type="text" id="endDate" class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" placeholder="05/08/2025" readonly>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="document.getElementById('endDate').focus()">
                                    <i class="fas fa-calendar-day"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription Dropdown -->
                    <div>
                        <label for="subFilter" class="block text-sm font-medium text-gray-700 mb-1">Subscription</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-primary-light">
                                <i class="fas fa-tag"></i>
                            </div>
                            <select id="subFilter" class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent transition-all duration-200 appearance-none bg-white">
                                <option value="all">All Subscriptions</option>
                                <option value="1">Monthly</option>
                                <option value="2">Quarterly</option>
                                <option value="3">Annually</option>
                                <option value="4">Trial</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Programs Filter (Changed from Payment Method) -->
                    <div>
                        <label for="programFilter" class="block text-sm font-medium text-gray-700 mb-1">Programs</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-primary-light">
                                <i class="fas fa-dumbbell"></i>
                            </div>
                            <select id="programFilter" class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent transition-all duration-200 appearance-none bg-white">
                                <option value="all">All Programs</option>
                                <option value="1">Weight Loss</option>
                                <option value="2">Muscle Building</option>
                                <option value="3">Cardio Fitness</option>
                                <option value="4">Yoga & Flexibility</option>
                                <option value="5">Strength Training</option>
                                <option value="6">Personal Training</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Filters -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                     <!-- Member Search - Adjusted to match date fields -->
                     <div class="lg:col-span-2">
                        <label for="memberSearch" class="block text-sm font-medium text-gray-700 mb-1">Member</label>
                        <div class="relative rounded-md shadow-sm member-search-wrapper">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-primary-light">
                                <i class="fas fa-user"></i>
                            </div>
                            <input type="text" id="memberSearch" class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent transition-all duration-200" placeholder="Search by name or email">
                            <div id="memberSearchResults" class="autocomplete-suggestions hidden"></div>
                        </div>
                    </div>

                    <!-- Action Buttons - Moved to right side -->
                    <div class="lg:col-span-2 flex justify-end items-end">
                        <div class="grid grid-cols-2 gap-2 w-full">
                            <button id="resetFiltersBtn" class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-redo-alt"></i> Reset Filters+
                            </button>
                            <button id="applyFiltersBtn" class="px-4 py-2.5 bg-primary-dark text-white rounded-lg hover:bg-opacity-90 transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-filter"></i> Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Transaction Results Section -->
            <div id="transactionResults">
                <!-- Transaction Header with Transaction History and Add Transaction buttons -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-primary-dark">Transactions</h2>
                        <p class="text-gray-500 text-sm mt-1 md:mt-0">Showing all transactions</p>
                    </div>
                    <div class="flex flex-col md:flex-row gap-2 mt-2 md:mt-0">
                        <button id="addTransactionBtn" class="bg-primary-dark hover:bg-black text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition-colors flex items-center">
                            <i class="fas fa-plus mr-2"></i> Add Transaction
                        </button>
                    </div>
                </div>
                
                <!-- Subscription Status Section -->
                <div class="bg-white rounded-lg shadow-sm p-5 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Subscription Status <span class="text-xs font-normal normal-case text-gray-400">(Most recent first)</span></h3>
                        <div class="flex gap-2">
                            <button id="refreshSubsBtn" class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors flex items-center gap-2">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                    </div>
                    
                    <div class="w-full">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid Date</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="subscriptionStatusBody">
                                <!-- Dynamic data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Transaction Modal -->
    <div id="addTransactionModal" class="fixed inset-0 bg-black bg-opacity-60 z-[60] flex items-center justify-center hidden backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 transform scale-95 overflow-hidden transition-all duration-200">
            <!-- Modal Title Banner -->
            <div class="px-6 py-4 flex items-center justify-between bg-gradient-to-r from-blue-900 to-blue-800 relative overflow-hidden">
                <div class="flex items-center z-10">
                    <div class="mr-4 h-10 w-10 rounded-full bg-white/25 flex items-center justify-center text-white shadow-sm">
                        <i class="fas fa-money-bill-wave text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-medium text-white leading-tight">Add New Transaction</h2>
                        <p class="text-xs text-white/90">Enter the payment details below</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal(document.getElementById('addTransactionModal'))" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 focus:outline-none transition-all duration-300 hover:rotate-90 z-20 cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
                
                <!-- Decorative background elements -->
                <div class="absolute -bottom-12 -right-12 w-32 h-32 bg-white/10 rounded-full"></div>
                <div class="absolute -top-6 -left-6 w-24 h-24 bg-white/5 rounded-full"></div>
            </div>

            <!-- Modal Body -->
            <div class="p-6 max-h-[65vh] overflow-y-auto custom-scrollbar">
                <form id="addTransactionForm" class="space-y-4">
                    
                    <!-- Member Information Section -->
                    <div class="mb-1">
                        <h4 class="text-base font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-user text-primary-light mr-2"></i>
                            <span>Member Information</span>
                        </h4>
                        <div class="w-full h-px bg-gradient-to-r from-primary-light/40 to-transparent mb-3 mt-1"></div>
                    </div>

                    <!-- Member Select -->
                    <div>
                        <label for="memberSearch" class="block text-sm font-medium text-gray-700 mb-1">Member Search</label>
                        <div class="relative rounded-md shadow-sm member-search-wrapper">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-primary-light">
                                <i class="fas fa-search"></i>
                            </div>
                            <input type="text" id="memberSearch" class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent transition-all duration-200" placeholder="Search member by name or email">
                            <div id="memberSearchResults" class="autocomplete-suggestions hidden">
                                <!-- Search results will appear here -->
                            </div>
                        </div>
                    </div>

                    <!-- Selected Member Information -->
                    <div id="selectedMemberInfo" class="bg-gray-50 p-3 rounded-md mt-2 hidden">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-primary-light flex items-center justify-center text-white text-xs mr-3" id="memberInitials">JD</div>
                            <div>
                                <p id="memberName" class="font-medium text-gray-900">John Doe</p>
                                <p id="memberEmail" class="text-xs text-gray-500">john.doe@example.com</p>
                            </div>
                            <button type="button" id="changeMemberBtn" class="ml-auto text-sm text-blue-600 hover:text-blue-800">
                                Change
                            </button>
                        </div>
                        <input type="hidden" id="selectedMemberId" value="">
                    </div>

                    <!-- Subscription Information Section -->
                    <div class="mt-6 mb-1">
                        <h4 class="text-base font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-tag text-primary-light mr-2"></i>
                            <span>Subscription Details</span>
                        </h4>
                        <div class="w-full h-px bg-gradient-to-r from-primary-light/40 to-transparent mb-3 mt-1"></div>
                    </div>

                    <!-- Subscription Select -->
                    <div>
                        <label for="subscriptionSelect" class="block text-sm font-medium text-gray-700 mb-1">Subscription Plan</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-primary-light">
                                <i class="fas fa-tag"></i>
                            </div>
                            <select id="subscriptionSelect" class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent transition-all duration-200 appearance-none bg-white">
                                <option value="">Select Subscription</option>
                                <option value="1" data-duration="1 Month" data-price="49.99">Monthly Membership ($49.99)</option>
                                <option value="2" data-duration="3 Months" data-price="129.99">Quarterly Membership ($129.99)</option>
                                <option value="3" data-duration="12 Months" data-price="499.99">Annual Membership ($499.99)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Transaction Information Section -->
                    <div class="mt-6 mb-1">
                        <h4 class="text-base font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-money-bill-wave text-primary-light mr-2"></i>
                            <span>Payment Information</span>
                        </h4>
                        <div class="w-full h-px bg-gradient-to-r from-primary-light/40 to-transparent mb-3 mt-1"></div>
                    </div>

                    <!-- Payment Method Select -->
                    <div>
                        <label for="paymentSelect" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-primary-light">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <select id="paymentSelect" class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent transition-all duration-200 appearance-none bg-white">
                                <option value="">Select Payment Method</option>
                                <option value="1">Credit Card</option>
                                <option value="2">Debit Card</option>
                                <option value="3">Cash</option>
                                <option value="4">Bank Transfer</option>
                                <option value="5">Mobile Payment</option>
                                <option value="6">Online Payment</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Add Start Date and End Date Fields -->
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <!-- Start Date -->
                        <div>
                            <label for="startDateInput" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-primary-light">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <input type="text" id="startDateInput" class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent transition-all duration-200" placeholder="mm/dd/yyyy">
                                <input type="hidden" id="startDateInputHidden">
                            </div>
                        </div>
                        
                        <!-- End Date -->
                        <div>
                            <label for="endDateInput" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-primary-light">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <input type="date" id="endDateInput" placeholder="Auto-calculated" class="pl-10 w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent transition-all duration-200 bg-gray-50" readonly>
                                <small class="text-xs text-gray-500 mt-1 block">Auto-calculated based on duration</small>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription Details Summary -->
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 mt-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-0.5">
                                <i class="fas fa-info-circle text-blue-500"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Subscription Summary</h3>
                                <div class="mt-2 text-sm space-y-2">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-blue-600 mb-1">Plan</label>
                                            <p id="subName" class="text-sm text-gray-900">-</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-blue-600 mb-1">Duration</label>
                                            <p id="subDuration" class="text-sm text-gray-900">-</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-blue-600 mb-1">Start Date</label>
                                            <p id="subStartDate" class="text-sm text-gray-900">-</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-blue-600 mb-1">End Date</label>
                                            <p id="subEndDate" class="text-sm text-gray-900">-</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-blue-600 mb-1">Price</label>
                                            <p id="subPrice" class="text-sm font-medium text-gray-900">-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="border-t border-gray-200 px-6 py-4 bg-gray-50 flex justify-end gap-3">
                <button type="button" class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none transition-colors duration-300 shadow-sm font-medium cursor-pointer relative z-10" onclick="closeModal(document.getElementById('addTransactionModal'))">
                    Cancel
                </button>
                <button id="submitTransactionBtn" type="button" class="px-5 py-2.5 bg-primary-dark text-white rounded-lg hover:bg-opacity-90 focus:outline-none transition-all duration-300 shadow-md font-medium flex items-center justify-center cursor-pointer relative z-10">
                    <i class="fas fa-save mr-2"></i> Add Transaction
                </button>
            </div>
        </div>
    </div>

    <!-- Transaction Details Modal -->
    <div id="transactionDetailsModal" class="fixed inset-0 bg-black bg-opacity-60 z-[60] flex items-center justify-center hidden backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 transform scale-95 overflow-hidden transition-all duration-200">
            <!-- Modal Title Banner -->
            <div class="px-6 py-4 flex items-center justify-between bg-gradient-to-r from-blue-900 to-blue-800 relative overflow-hidden">
                <div class="flex items-center z-10">
                    <div class="mr-4 h-10 w-10 rounded-full bg-white/25 flex items-center justify-center text-white shadow-sm">
                        <i class="fas fa-file-invoice text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-medium text-white leading-tight">Transaction Details</h2>
                        <p class="text-xs text-white/90">View transaction information</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal(document.getElementById('transactionDetailsModal'))" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 focus:outline-none transition-all duration-300 hover:rotate-90 z-20 cursor-pointer">
                    <i class="fas fa-times"></i>
                </button>
                
                <!-- Decorative background elements -->
                <div class="absolute -bottom-12 -right-12 w-32 h-32 bg-white/10 rounded-full"></div>
                <div class="absolute -top-6 -left-6 w-24 h-24 bg-white/5 rounded-full"></div>
            </div>

            <!-- Modal Body -->
            <div class="p-6 max-h-[65vh] overflow-y-auto custom-scrollbar">
                <div class="flex items-center mb-4">
                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-4">
                        <i class="fas fa-info-circle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Transaction Details</h3>
                        <p class="text-sm text-gray-600">View the details of the transaction.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    <!-- Transaction ID and Date -->
                    <div class="flex justify-between items-center">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Transaction ID</label>
                            <p id="detailsTransactionId" class="text-sm text-gray-900">-</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Date</label>
                            <p id="detailsTransactionDate" class="text-sm text-gray-900">-</p>
                        </div>
                    </div>

                    <!-- Member Details -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Member Details</h4>
                        <div class="flex items-center mb-2">
                            <div class="h-10 w-10 rounded-full bg-primary-light flex items-center justify-center text-white text-xs" id="detailsMemberInitials">-</div>
                            <div class="ml-3">
                                <p id="detailsMemberName" class="text-sm font-medium text-gray-900">-</p>
                                <p id="detailsMemberId" class="text-xs text-gray-500">-</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                                <p id="detailsMemberEmail" class="text-sm text-gray-900">-</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Phone</label>
                                <p id="detailsMemberPhone" class="text-sm text-gray-900">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription Details -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Subscription Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Plan</label>
                                <p id="detailsSubName" class="text-sm text-gray-900">-</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Duration</label>
                                <p id="detailsSubDuration" class="text-sm text-gray-900">-</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
                                <p id="detailsSubStartDate" class="text-sm text-gray-900">-</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">End Date</label>
                                <p id="detailsSubEndDate" class="text-sm text-gray-900">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Payment Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Method</label>
                                <p id="detailsPaymentMethod" class="text-sm text-gray-900">-</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Amount</label>
                                <p id="detailsAmount" class="text-sm text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-6 justify-end">
                    <button type="button" class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors" onclick="closeModal(document.getElementById('transactionDetailsModal'))">
                        Close
                    </button>
                    <button type="button" class="px-4 py-2.5 bg-primary-dark text-white rounded-md hover:bg-opacity-90 transition-colors" id="printReceiptBtn">
                        Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History Modal -->
    <div id="transactionHistoryModal" class="fixed inset-0 bg-black bg-opacity-30 z-[60] flex items-center justify-center hidden backdrop-blur-sm">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4 transform scale-95 overflow-hidden transition-all duration-200">
            <div class="flex items-center justify-between p-5 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Transaction History</h3>
                <button onclick="closeModal(document.getElementById('transactionHistoryModal'))" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-5">
                <div class="mb-4">
                    <h4 class="font-medium text-gray-700 mb-2">Member Information</h4>
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-primary-light flex items-center justify-center text-white text-xs" id="historyMemberInitials">-</div>
                        <div class="ml-3">
                            <p id="historyMemberName" class="text-sm font-medium text-gray-900">-</p>
                            <p id="historyMemberId" class="text-xs text-gray-500">-</p>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="transactionHistoryBody">
                            <!-- Dynamic data will be loaded here -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination controls -->
                <div class="flex justify-between items-center mt-4">
                    <p class="text-sm text-gray-600">Showing <span id="historyStart">1</span> to <span id="historyEnd">3</span> of <span id="historyTotal">3</span> transactions</p>
                    <div class="flex space-x-1">
                        <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>Previous</button>
                        <button class="px-3 py-1 bg-primary-dark text-white rounded hover:bg-opacity-90 transition-colors">1</button>
                        <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>Next</button>
                    </div>
                </div>
                
                <!-- Close button at the bottom -->
                <div class="flex justify-end mt-6">
                    <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors" onclick="closeModal(document.getElementById('transactionHistoryModal'))">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Confirmation Dialog -->
    <div id="logoutConfirmDialog" class="fixed inset-0 bg-black bg-opacity-30 z-[60] flex items-center justify-center hidden backdrop-blur-sm">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 transform scale-95 overflow-hidden transition-all duration-200">
            <div class="p-5">
                <div class="flex items-center mb-4">
                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-4">
                        <i class="fas fa-sign-out-alt text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Confirm Logout</h3>
                        <p class="text-sm text-gray-600">Are you sure you want to log out of your account?</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button id="cancelLogout" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button id="confirmLogout" class="px-4 py-2 bg-primary-dark text-white rounded-md hover:bg-opacity-90 transition-colors">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Dialog -->
    <div id="confirmationDialog" class="fixed inset-0 bg-black bg-opacity-30 z-[60] flex items-center justify-center hidden backdrop-blur-sm">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 transform scale-95 overflow-hidden transition-all duration-200">
            <div class="p-5">
                <div class="flex items-center mb-4">
                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-4">
                        <i class="fas fa-question-circle text-xl"></i>
                    </div>
                    <div>
                        <h3 id="confirmationTitle" class="text-lg font-semibold text-gray-800">Confirm Action</h3>
                        <p id="confirmationMessage" class="text-sm text-gray-600">Are you sure you want to proceed?</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button id="cancelConfirmation" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button id="confirmAction" class="px-4 py-2 bg-primary-dark text-white rounded-md hover:bg-opacity-90 transition-colors">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    <div id="toast" class="fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-md shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300 flex items-center" style="display: none;">
        <i id="toastIcon" class="fas fa-check-circle mr-2"></i>
        <span id="toastMessage">Operation successful!</span>
        <button class="ml-3 text-white focus:outline-none" onclick="hideToast()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch transactions and subscription data on page load
            fetchTransactionData();
            fetchSubscriptionData();
            fetchDropdownOptions();
            
            // Setup member search autocomplete in the add transaction modal
            setupMemberSearchInModal();

            // Function to fetch dropdown options (subscriptions and programs)
            function fetchDropdownOptions() {
                // Fetch subscription options
                fetch('../../api/subscription/get_subscription_options.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            populateSubscriptionDropdown(data.subscriptions);
                        } else {
                            console.error('Error fetching subscription options');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching subscription options:', error);
                    });

                // Fetch program options
                fetch('../../api/program/get_program_options.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            populateProgramDropdown(data.programs);
                        } else {
                            console.error('Error fetching program options');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching program options:', error);
                    });
                
                // Fetch payment method options
                fetch('../../api/payment/get_payment_methods.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            populatePaymentMethodDropdown(data.payment_methods);
                        } else {
                            console.error('Error fetching payment methods');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching payment methods:', error);
                    });
            }

            // Function to populate subscription dropdown
            function populateSubscriptionDropdown(subscriptions) {
                const subFilter = document.getElementById('subFilter');
                if (subFilter) {
                    // Clear all options except the first one
                    while (subFilter.options.length > 1) {
                        subFilter.remove(1);
                    }
                    
                    // Add new options from the data
                    subscriptions.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.id;
                        option.text = sub.name;
                        subFilter.appendChild(option);
                    });
                }
                
                // Also update the subscription select in the add transaction modal
                const subscriptionSelect = document.getElementById('subscriptionSelect');
                if (subscriptionSelect) {
                    // Clear all options except the first one
                    while (subscriptionSelect.options.length > 1) {
                        subscriptionSelect.remove(1);
                    }
                    
                    // Add new options from the data
                    subscriptions.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.id;
                        option.text = `${sub.name} ($${parseFloat(sub.price).toFixed(2)})`;
                        option.setAttribute('data-duration', `${sub.duration} Days`);
                        option.setAttribute('data-price', sub.price);
                        subscriptionSelect.appendChild(option);
                    });
                }
            }

            // Function to fetch subscription data from API
            function fetchSubscriptionData() {
                return new Promise((resolve, reject) => {
                    fetch('../../api/subscription/get_subscriptions.php')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update expiring subscriptions count
                                document.getElementById('expiringSubscriptions').textContent = data.expiring_count;
                                
                                // Update subscription status table
                                updateSubscriptionTable(data.subscriptions);
                                resolve(data);
                            } else {
                                console.error('Error fetching subscription data');
                                reject(new Error(data.message || 'Failed to fetch subscription data'));
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching subscription data:', error);
                            reject(error);
                        });
                });
            }
            
            // Function to update subscription status table
            function updateSubscriptionTable(subscriptions) {
                const tbody = document.getElementById('subscriptionStatusBody');
                tbody.innerHTML = ''; // Clear existing rows
                
                if (subscriptions.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No subscriptions found</td></tr>';
                    return;
                }
                
                // Add each subscription to the table
                subscriptions.forEach(sub => {
                    const row = document.createElement('tr');
                    
                    // Member column with avatar and name
                    let memberCell = document.createElement('td');
                    memberCell.className = 'px-6 py-4 whitespace-nowrap';
                    
                    // Get initials for the avatar
                    const nameParts = sub.member_name.split(' ');
                    const initials = nameParts.length >= 2 
                        ? (nameParts[0][0] + nameParts[1][0]).toUpperCase()
                        : nameParts[0][0].toUpperCase();
                    
                    memberCell.innerHTML = `
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-gray-500 flex items-center justify-center text-white text-xs font-medium mr-3">
                                ${initials}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">${sub.member_name}</p>
                                <p class="text-xs text-gray-500">${sub.member_email}</p>
                            </div>
                        </div>
                    `;
                    row.appendChild(memberCell);
                    
                    // Subscription column
                    let subCell = document.createElement('td');
                    subCell.className = 'px-6 py-4 whitespace-nowrap';
                    subCell.innerHTML = `
                        <div>
                            <p class="text-sm text-gray-900">${sub.sub_name}</p>
                            <p class="text-xs text-gray-500">${sub.duration} days</p>
                        </div>
                    `;
                    row.appendChild(subCell);
                    
                    // Start date column
                    let startDateCell = document.createElement('td');
                    startDateCell.className = 'px-6 py-4 whitespace-nowrap';
                    const startDate = new Date(sub.start_date);
                    startDateCell.innerHTML = `
                        <p class="text-sm text-gray-900">${startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</p>
                    `;
                    row.appendChild(startDateCell);
                    
                    // End date column
                    let endDateCell = document.createElement('td');
                    endDateCell.className = 'px-6 py-4 whitespace-nowrap';
                    const endDate = new Date(sub.end_date);
                    endDateCell.innerHTML = `
                        <p class="text-sm text-gray-900">${endDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</p>
                    `;
                    row.appendChild(endDateCell);
                    
                    // Paid date column (using start date as paid date)
                    let paidDateCell = document.createElement('td');
                    paidDateCell.className = 'px-6 py-4 whitespace-nowrap';
                    paidDateCell.innerHTML = `
                        <p class="text-sm text-gray-900">${startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</p>
                    `;
                    row.appendChild(paidDateCell);
                    
                    // Status column
                    let statusCell = document.createElement('td');
                    statusCell.className = 'px-4 py-4 whitespace-nowrap';
                    
                    // Determine status based on active flag and end date
                    const today = new Date();
                    let statusClass = '';
                    let statusText = '';
                    
                    if (!sub.is_active) {
                        statusClass = 'bg-red-100 text-red-800';
                        statusText = 'Inactive';
                    } else if (endDate < today) {
                        statusClass = 'bg-red-100 text-red-800';
                        statusText = 'Expired';
                    } else {
                        statusClass = 'bg-green-100 text-green-800';
                        statusText = 'Active';
                    }
                    
                    statusCell.innerHTML = `
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                            ${statusText}
                        </span>
                    `;
                    row.appendChild(statusCell);
                    
                    // Action column
                    let actionCell = document.createElement('td');
                    actionCell.className = 'px-4 py-4 whitespace-nowrap text-center';
                    
                    if (sub.is_active) {
                        // Deactivate button for active subscriptions
                        actionCell.innerHTML = `
                            <button class="text-red-600 hover:text-red-800 transition-colors" 
                                title="Deactivate subscription" 
                                data-action="deactivate" 
                                data-sub-id="${sub.sub_id}" 
                                data-member-id="${sub.member_id}">
                                <i class="fas fa-toggle-off"></i>
                            </button>
                        `;
                    } else {
                        // Renew button for inactive subscriptions
                        actionCell.innerHTML = `
                            <button class="text-green-600 hover:text-green-800 transition-colors" 
                                title="Renew subscription" 
                                data-action="renew" 
                                data-sub-id="${sub.sub_id}" 
                                data-member-id="${sub.member_id}">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        `;
                    }
                    row.appendChild(actionCell);
                    
                    tbody.appendChild(row);
                });
                
                // Initialize action buttons after updating table
                initActionButtons();
            }

            // Initialize dropdown toggle functionality
            const dropdownButtons = document.querySelectorAll('[data-collapse-toggle]');
            
            dropdownButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-collapse-toggle');
                    const targetElement = document.getElementById(targetId);
                    const chevronIcon = document.getElementById(targetId.replace('dropdown-', '') + '-chevron');
                    
                    if (targetElement) {
                        if (targetElement.classList.contains('hidden')) {
                            // Show dropdown
                            targetElement.classList.remove('hidden');
                            targetElement.style.maxHeight = targetElement.scrollHeight + 'px';
                            if (chevronIcon) {
                                chevronIcon.style.transform = 'rotate(180deg)';
                            }
                        } else {
                            // Hide dropdown
                            targetElement.style.maxHeight = '0px';
                            if (chevronIcon) {
                                chevronIcon.style.transform = 'rotate(0deg)';
                            }
                            setTimeout(() => {
                                targetElement.classList.add('hidden');
                            }, 300);
                        }
                    }
                });
            });

            // Date range handling - just handle direct inputs since there's no dateRange dropdown
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');
            
            // Set default date range to current month
            if (startDateInput && endDateInput) {
                const today = new Date();
                const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                startDateInput.value = formatDate(firstDay);
                endDateInput.value = formatDate(today);
            }

            // Add logout confirmation functionality
            const logoutButton = document.getElementById('logoutBtn');
            const logoutConfirmDialog = document.getElementById('logoutConfirmDialog');
            const cancelLogout = document.getElementById('cancelLogout');
            const confirmLogout = document.getElementById('confirmLogout');

            // Change logout link behavior to show confirmation
            if (logoutButton) {
                logoutButton.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent immediate navigation
                    showLogoutConfirmDialog();
                });
            }

            // Cancel logout button
            if (cancelLogout) {
                cancelLogout.addEventListener('click', hideLogoutConfirmDialog);
            }

            // Confirm logout button
            if (confirmLogout) {
                confirmLogout.addEventListener('click', function() {
                    // Navigate to login page
                    window.location.href = "../../login.php";
                });
            }

            // Function to show logout confirmation dialog
            function showLogoutConfirmDialog() {
                logoutConfirmDialog.classList.remove('hidden');
                setTimeout(() => {
                    const dialogContent = logoutConfirmDialog.querySelector('.transform');
                    if (dialogContent) {
                        dialogContent.classList.remove('scale-95');
                        dialogContent.classList.add('scale-100');
                    }
                }, 10);
            }

            // Function to hide logout confirmation dialog
            function hideLogoutConfirmDialog() {
                const dialogContent = logoutConfirmDialog.querySelector('.transform');
                if (dialogContent) {
                    dialogContent.classList.remove('scale-100');
                    dialogContent.classList.add('scale-95');
                }
                setTimeout(() => {
                    logoutConfirmDialog.classList.add('hidden');
                }, 200);
            }

            // Modal functions
            window.openModal = function(modal) {
                if (!modal) return;
                modal.classList.remove('hidden');
                setTimeout(() => {
                    const modalContent = modal.querySelector('.transform');
                    if (modalContent) {
                        modalContent.classList.remove('scale-95');
                        modalContent.classList.add('scale-100');
                    }
                }, 10);
            }

            window.closeModal = function(modal) {
                if (!modal) return;
                const modalContent = modal.querySelector('.transform');
                if (modalContent) {
                    modalContent.classList.remove('scale-100');
                    modalContent.classList.add('scale-95');
                }
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 200);
            }

            // Notification function
            function showNotification(message, type = 'info') {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white flex items-center space-x-3 transition-all duration-300 transform translate-y-10 opacity-0 z-50`;

                // Set background color based on type
                if (type === 'success') {
                    notification.classList.add('bg-green-600');
                } else if (type === 'error') {
                    notification.classList.add('bg-red-600');
                } else {
                    notification.classList.add('bg-blue-600');
                }

                // Set icon based on type
                let icon;
                if (type === 'success') {
                    icon = 'fa-check-circle';
                } else if (type === 'error') {
                    icon = 'fa-exclamation-circle';
                } else {
                    icon = 'fa-info-circle';
                }

                // Set content
                notification.innerHTML = `
                    <i class="fas ${icon}"></i>
                    <span>${message}</span>
                `;

                // Add notification to body
                document.body.appendChild(notification);

                // Show notification with animation
                setTimeout(() => {
                    notification.classList.remove('translate-y-10', 'opacity-0');
                }, 10);

                // Hide notification after 3 seconds
                setTimeout(() => {
                    notification.classList.add('translate-y-10', 'opacity-0');
                    // Remove notification from DOM after animation completes
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 3000);
            }

            // Add Transaction Button
            const addTransactionBtn = document.getElementById('addTransactionBtn');
            const addTransactionModal = document.getElementById('addTransactionModal');
            if (addTransactionBtn && addTransactionModal) {
                addTransactionBtn.addEventListener('click', function() {
                    openModal(addTransactionModal);
                    
                    // Set the date fields with today's date
                    const today = new Date();
                    
                    // Set start date in MM/DD/YYYY format
                    const startDateInput = document.getElementById('startDateInput');
                    if (startDateInput) {
                        startDateInput.value = formatDisplayInputDate(today);
                    }
                    
                    // Store the date value in ISO format for hidden field
                    const startDateInputHidden = document.getElementById('startDateInputHidden');
                    if (startDateInputHidden) {
                        startDateInputHidden.value = formatDate(today);
                    }
                    
                    // Make sure subscription select triggers a change event to calculate end date
                    const subscriptionSelect = document.getElementById('subscriptionSelect');
                    if (subscriptionSelect && subscriptionSelect.value) {
                        // If a subscription is already selected, update end date
                        updateEndDateFromStartDate(today);
                    }
                });
            }
            
            // Function to update end date based on start date and subscription duration
            function updateEndDateFromStartDate(startDate) {
                const subscriptionSelect = document.getElementById('subscriptionSelect');
                if (!subscriptionSelect || !subscriptionSelect.value) return;
                
                const selectedOption = subscriptionSelect.options[subscriptionSelect.selectedIndex];
                const endDateInput = document.getElementById('endDateInput');
                const endDateInputHidden = document.getElementById('endDateInputHidden');
                
                if (selectedOption.value) {
                    // Get duration from selected subscription
                    const duration = selectedOption.getAttribute('data-duration');
                    
                    // Calculate end date based on start date
                    const endDate = calculateEndDate(startDate, duration);
                    
                    // Update end date inputs
                    if (endDateInput) {
                        // Format for display (MM/DD/YYYY) in visible field
                        endDateInput.value = formatDisplayInputDate(endDate);
                    }
                    
                    if (endDateInputHidden) {
                        // Format the end date for input value (YYYY-MM-DD) in hidden field
                        endDateInputHidden.value = formatDate(endDate);
                    }
                    
                    // Also update the display in the summary section
                    document.getElementById('subStartDate').textContent = formatDisplayDate(startDate);
                    document.getElementById('subEndDate').textContent = formatDisplayDate(endDate);
                }
            }

            // Initialize date calculation based on selected subscription
            function initDateCalculation() {
                const startDateInput = document.getElementById('startDateInput');
                const endDateInput = document.getElementById('endDateInput');
                const subscriptionSelect = document.getElementById('subscriptionSelect');
                
                if (!startDateInput || !endDateInput || !subscriptionSelect) return;
                
                // If no subscription selected or no start date set, we can't calculate
                if (!subscriptionSelect.value) return;
                
                // If no start date is set, default to today
                if (!startDateInput.value) {
                    const today = new Date();
                    startDateInput.value = formatDate(today);
                }
                
                const selectedOption = subscriptionSelect.options[subscriptionSelect.selectedIndex];
                const duration = selectedOption.getAttribute('data-duration');
                
                if (!duration) return;
                
                const startDate = new Date(startDateInput.value);
                
                // Skip if date is invalid
                if (isNaN(startDate.getTime())) return;
                
                // Calculate end date based on duration
                const endDate = calculateEndDate(startDate, duration);
                
                // Update end date input
                endDateInput.value = formatDate(endDate);
                
                // Update the summary display
                document.getElementById('subStartDate').textContent = formatDisplayDate(startDate);
                document.getElementById('subEndDate').textContent = formatDisplayDate(endDate);
            }

            // Initialize subscription details when subscription is selected
            const subscriptionSelect = document.getElementById('subscriptionSelect');
            if (subscriptionSelect) {
                subscriptionSelect.addEventListener('change', function() {
                    updateSubscriptionDetails();
                });
            }

            function updateSubscriptionDetails() {
                const subscriptionSelect = document.getElementById('subscriptionSelect');
                const selectedOption = subscriptionSelect.options[subscriptionSelect.selectedIndex];
                const startDateInput = document.getElementById('startDateInput');
                const endDateInput = document.getElementById('endDateInput');
                
                if (selectedOption.value) {
                    // Get data attributes
                    const duration = selectedOption.getAttribute('data-duration');
                    const price = selectedOption.getAttribute('data-price');
                    const name = selectedOption.text.split('(')[0].trim();

                    // Calculate dates - use existing start date if available
                    let startDate;
                    if (startDateInput.value) {
                        // Use the already selected start date
                        startDate = new Date(startDateInput.value);
                    } else {
                        // Default to today
                        startDate = new Date();
                        startDateInput.value = formatDate(startDate);
                    }
                    
                    // Calculate end date
                    const endDate = calculateEndDate(startDate, duration);

                    // Update UI summary
                    document.getElementById('subName').textContent = name;
                    document.getElementById('subDuration').textContent = duration;
                    document.getElementById('subStartDate').textContent = formatDisplayDate(startDate);
                    document.getElementById('subEndDate').textContent = formatDisplayDate(endDate);
                    document.getElementById('subPrice').textContent = `$${price}`;

                    // Set the end date input field
                    if (endDateInput) {
                        endDateInput.value = formatDate(endDate);
                    }
                } else {
                    // Reset values
                    document.getElementById('subName').textContent = '-';
                    document.getElementById('subDuration').textContent = '-';
                    document.getElementById('subStartDate').textContent = '-';
                    document.getElementById('subEndDate').textContent = '-';
                    document.getElementById('subPrice').textContent = '-';
                    
                    // Clear end date input
                    if (endDateInput) {
                        endDateInput.value = '';
                    }
                }
            }

            function calculateEndDate(startDate, duration) {
                const date = new Date(startDate);
                
                // Extract number from duration string
                const durationValue = parseInt(duration);
                
                // Handle different duration formats including numbers with unit texts
                if (duration.includes('Month') || duration.includes('month')) {
                    date.setMonth(date.getMonth() + durationValue);
                } else if (duration.includes('Year') || duration.includes('year')) {
                    date.setFullYear(date.getFullYear() + durationValue);
                } else if (duration.includes('Day') || duration.includes('day')) {
                    date.setDate(date.getDate() + durationValue);
                } else {
                    // Default handling for numeric values assuming they are days
                    date.setDate(date.getDate() + durationValue);
                }
                
                return date;
            }

            // Initialize action buttons (Deactivate, Renew, View)
            initActionButtons();

            function initActionButtons() {
                // Deactivate subscription - Needs confirmation
                document.querySelectorAll('[data-action="deactivate"]').forEach(button => {
                    button.addEventListener('click', function() {
                        const subId = this.getAttribute('data-sub-id');
                        const memberId = this.getAttribute('data-member-id');
                        // Show confirmation dialog for deactivation
                        showConfirmationDialog(
                            'Confirm Deactivation',
                            'Are you sure you want to deactivate this subscription?',
                            () => {
                                // Show loading state
                                const originalHTML = this.innerHTML;
                                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                                this.disabled = true;

                                // Call the API to deactivate the subscription
                                fetch('../../api/subscription/deactivate_subscription.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        member_id: memberId,
                                        sub_id: subId
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Update UI
                                        const row = this.closest('tr');
                                        const statusCell = row.querySelector('td:nth-child(6) span');
                                        statusCell.className = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
                                        statusCell.textContent = 'Inactive';

                                        // Create a new renew button with proper event handling
                                        const newRenewButton = document.createElement('button');
                                        newRenewButton.className = 'text-green-600 hover:text-green-800 transition-colors';
                                        newRenewButton.title = 'Renew subscription';
                                        newRenewButton.innerHTML = '<i class="fas fa-sync-alt"></i>';
                                        newRenewButton.setAttribute('data-sub-id', subId);
                                        newRenewButton.setAttribute('data-member-id', memberId);
                                        newRenewButton.setAttribute('data-action', 'renew');
                                        
                                        // Replace the old button with the new one
                                        this.parentNode.replaceChild(newRenewButton, this);
                                        
                                        // Explicitly add click event to the new button
                                        newRenewButton.addEventListener('click', function() {
                                            const row = this.closest('tr');
                                            const memberName = row.querySelector('td:nth-child(1) .text-sm.font-medium').textContent;
                                            const subscriptionName = row.querySelector('td:nth-child(2) .text-sm').textContent;
                                            openRenewModal(memberName, subscriptionName);
                                        });

                                        // Show notification
                                        showToast('Subscription deactivated successfully!', true);
                                    } else {
                                        // Restore the button if there was an error
                                        this.innerHTML = originalHTML;
                                        this.disabled = false;
                                        
                                        // Show error notification
                                        showToast('Error: ' + data.message, false);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error deactivating subscription:', error);
                                    
                                    // Restore the button
                                    this.innerHTML = originalHTML;
                                    this.disabled = false;
                                    
                                    // Show error notification
                                    showToast('Error deactivating subscription. Please try again.', false);
                                });
                            }
                        );
                    });
                });

                // Set up direct renewal modal opening for existing renew buttons
                document.querySelectorAll('[data-action="renew"]').forEach(button => {
                    button.addEventListener('click', function() {
                        const row = this.closest('tr');
                        const memberName = row.querySelector('td:nth-child(1) .text-sm.font-medium').textContent;
                        const subscriptionName = row.querySelector('td:nth-child(2) .text-sm').textContent;
                        
                        // Open renewal form modal
                        openRenewModal(memberName, subscriptionName);
                    });
                });
            }

            // Function to open renew modal with pre-filled data
            function openRenewModal(memberName, subscriptionName) {
                const modal = document.getElementById('addTransactionModal');
                if (!modal) return;
                
                openModal(modal);
                
                // Set member details
                const memberInitials = document.getElementById('memberInitials');
                const memberNameElement = document.getElementById('memberName');
                const memberEmail = document.getElementById('memberEmail');
                const selectedMemberId = document.getElementById('selectedMemberId');
                const changeMemberBtn = document.getElementById('changeMemberBtn');
                
                // Set member information
                const initials = memberName.split(' ').map(n => n[0]).join('');
                if (memberInitials) memberInitials.textContent = initials;
                if (memberNameElement) memberNameElement.textContent = memberName;
                if (memberEmail) memberEmail.textContent = memberName.toLowerCase().replace(' ', '.') + '@example.com';
                if (selectedMemberId) selectedMemberId.value = '1001'; // This would be replaced with actual ID
                
                // Hide change button for renewals
                if (changeMemberBtn) {
                    changeMemberBtn.classList.add('hidden');
                }
                
                // Pre-fill subscription select
                const subscriptionSelect = document.getElementById('subscriptionSelect');
                if (subscriptionSelect) {
                    for (let i = 0; i < subscriptionSelect.options.length; i++) {
                        if (subscriptionSelect.options[i].text.includes(subscriptionName)) {
                            subscriptionSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
                
                // Set start date to today
                const today = new Date();
                
                // Update start date fields
                const startDateInput = document.getElementById('startDateInput');
                if (startDateInput) {
                    startDateInput.value = formatDisplayInputDate(today);
                }
                
                const startDateInputHidden = document.getElementById('startDateInputHidden');
                if (startDateInputHidden) {
                    startDateInputHidden.value = formatDate(today);
                }
                
                // Update end date
                updateEndDateFromStartDate(today);
            }
        
            const submitTransactionBtn = document.getElementById('submitTransactionBtn');
            
            if (submitTransactionBtn) {
                // Replace the existing click event with enhanced validation
                submitTransactionBtn.addEventListener('click', function() {
                    // Get form fields
                    const memberId = document.getElementById('selectedMemberId').value;
                    const subscriptionSelect = document.getElementById('subscriptionSelect');
                    const paymentSelect = document.getElementById('paymentSelect');
                    const startDateInput = document.getElementById('startDateInput');
                    const endDateInput = document.getElementById('endDateInput');
                    
                    // Check if elements exist
                    if (!subscriptionSelect || !paymentSelect || !startDateInput || !endDateInput) {
                        showToast('An error occurred: Form elements not found', false);
                        return;
                    }
                    
                    const subscriptionId = subscriptionSelect.value;
                    const paymentMethod = paymentSelect.value;
                    const startDate = startDateInput.value;
                    const endDate = endDateInput.value;
                    
                    // Reset previous error states
                    document.querySelectorAll('#addTransactionForm .error-border').forEach(el => {
                        el.classList.remove('error-border');
                    });
                    document.querySelectorAll('#addTransactionForm .error-message').forEach(el => {
                        el.remove();
                    });
                    
                    // Validate form and show inline errors
                    let hasErrors = false;
                    
                    // Member validation - check just once, avoid duplicate errors
                    if (!memberId) {
                        hasErrors = true;
                        // Find the member search field and add error only once
                        const memberSearchContainer = document.querySelector('#addTransactionForm #memberSearch').closest('div');
                        if (memberSearchContainer && !memberSearchContainer.querySelector('.error-message')) {
                            highlightError(memberSearchContainer, 'Please select a member');
                        }
                    }
                    
                    // Subscription validation
                    if (!subscriptionId) {
                        hasErrors = true;
                        highlightError(subscriptionSelect.parentElement, 'Please select a subscription plan');
                    }
                    
                    // Payment method validation
                    if (!paymentMethod) {
                        hasErrors = true;
                        highlightError(paymentSelect.parentElement, 'Please select a payment method');
                    }
                    
                    // Date validation
                    if (!startDate) {
                        hasErrors = true;
                        highlightError(startDateInput.parentElement, 'Please set a start date');
                    }
                    
                    if (!endDate) {
                        hasErrors = true;
                        highlightError(endDateInput.parentElement, 'Please set an end date');
                    }
                    
                    // If validation fails, exit
                    if (hasErrors) {
                        return;
                    }
                    
                    // If all validations pass, proceed with form submission
                    // Show loading state on the button
                    const originalBtnText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                    this.disabled = true;
                    
                    // Get subscription details for the notification
                    const selectedOption = subscriptionSelect.options[subscriptionSelect.selectedIndex];
                    const subscriptionName = selectedOption.text.split('(')[0].trim();
                    const memberName = document.getElementById('memberName').textContent;
                    
                    // Simulate API call with timeout
                    setTimeout(() => {
                        // Close modal
                        closeModal(document.getElementById('addTransactionModal'));
                        
                        // Reset form
                        document.getElementById('addTransactionForm').reset();
                        
                        // Reset the UI for future new transactions
                        resetTransactionModalUI();
                        
                        // Update summary cards (simulating data refresh)
                        updateSummaryCards();
                        
                        // Determine if this was a renewal
                        const isRenewal = this.innerHTML.includes('Renew');
                        const message = isRenewal 
                            ? `${subscriptionName} successfully renewed for ${memberName}!`
                            : `${subscriptionName} successfully added for ${memberName}!`;
                        
                        // Show success notification using the toast
                        showToast(message, true);
                        
                        // Reset button
                        this.innerHTML = originalBtnText;
                        this.disabled = false;
                    }, 1000);
                });
            }
            
            // Function to highlight error fields with a red border and message
            function highlightError(fieldContainer, message) {
                // Add red border to the input
                const input = fieldContainer.querySelector('input, select');
                if (input) {
                    input.classList.add('border-red-500', 'error-border');
                    input.classList.remove('border-gray-300');
                }
                
                // Add error message below the field
                const errorMessage = document.createElement('p');
                errorMessage.className = 'text-xs text-red-600 mt-1 error-message';
                errorMessage.textContent = message;
                fieldContainer.appendChild(errorMessage);
            }
            
            // Add event listeners to clear error state when input changes
            const modalFormInputs = document.querySelectorAll('#addTransactionForm input, #addTransactionForm select');
            modalFormInputs.forEach(input => {
                if (input) {
                    input.addEventListener('change', function() {
                        // Remove red border
                        this.classList.remove('border-red-500', 'error-border');
                        this.classList.add('border-gray-300');
                        
                        // Remove error message if it exists
                        const errorMessage = this.parentElement.querySelector('.error-message');
                        if (errorMessage) errorMessage.remove();
                    });
                }
            });
        });

        // Function to show filter badge at the top of the page
        function showFilterBadge(resultCount) {
            // Check if a filter badge already exists
            let filterBadge = document.getElementById('filterBadge');
            
            if (!filterBadge) {
                // Create the filter badge
                filterBadge = document.createElement('div');
                filterBadge.id = 'filterBadge';
                filterBadge.className = 'fixed top-4 right-4 z-40 flex items-center bg-blue-900 text-white px-4 py-3 rounded-md shadow-lg';
                
                // Add to the page
                document.body.appendChild(filterBadge);
            }
            
            // Get filter values for display
            const subFilter = document.getElementById('subFilter');
            const programFilter = document.getElementById('programFilter');
            
            // Get the selected text values
            let subFilterText = '';
            let programFilterText = '';
            
            if (subFilter && subFilter.value !== 'all') {
                subFilterText = subFilter.options[subFilter.selectedIndex].text;
            }
            
            if (programFilter && programFilter.value !== 'all') {
                programFilterText = programFilter.options[programFilter.selectedIndex].text;
            }
            
            // Prepare the badge content
            let badgeContent = `
                <div class="flex flex-col">
                    <div class="flex items-center">
                        <i class="fas fa-filter mr-2"></i>
                        <span class="font-semibold">${resultCount} result${resultCount !== 1 ? 's' : ''} found</span>
                    </div>
            `;
            
            // Add active filters to the badge
            const activeFilters = [];
            if (subFilterText) activeFilters.push(`Subscription: ${subFilterText}`);
            if (programFilterText) activeFilters.push(`Program: ${programFilterText}`);
            
            if (activeFilters.length > 0) {
                badgeContent += `<div class="text-xs mt-1 text-blue-100">${activeFilters.join(' • ')}</div>`;
            }
            
            badgeContent += `</div>
                <button class="ml-3 text-white hover:text-blue-200 focus:outline-none" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Update badge content
            filterBadge.innerHTML = badgeContent;
            
            // Make sure it's visible
            filterBadge.style.display = 'flex';
            
            // Add animation
            filterBadge.classList.add('animate-bounce');
            setTimeout(() => {
                filterBadge.classList.remove('animate-bounce');
            }, 1000);
        }

        // Function to populate program dropdown
        function populateProgramDropdown(programs) {
            const programFilter = document.getElementById('programFilter');
            if (programFilter) {
                // Clear all options except the first one
                while (programFilter.options.length > 1) {
                    programFilter.remove(1);
                }
                
                // Add new options from the data
                programs.forEach(program => {
                    const option = document.createElement('option');
                    option.value = program.id;
                    option.text = program.name;
                    programFilter.appendChild(option);
                });
            }
        }
        
        // Function to populate payment method dropdown
        function populatePaymentMethodDropdown(paymentMethods) {
            const paymentSelect = document.getElementById('paymentSelect');
            if (paymentSelect) {
                // Clear all options except the first one
                while (paymentSelect.options.length > 1) {
                    paymentSelect.remove(1);
                }
                
                // Add new options from the data
                paymentMethods.forEach(method => {
                    const option = document.createElement('option');
                    option.value = method.id;
                    option.text = method.name;
                    paymentSelect.appendChild(option);
                });
            }
        }

        // Function to fetch transaction data from API
        function fetchTransactionData() {
            fetch('../../api/transaction/get_transactions.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update summary cards
                        document.getElementById('totalTransactions').textContent = data.total_transactions;
                        document.getElementById('totalRevenue').textContent = '$' + data.total_revenue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        document.getElementById('recentTransactions').textContent = data.recent_transactions;
                    } else {
                        console.error('Error fetching transaction data');
                    }
                })
                .catch(error => {
                    console.error('Error fetching transaction data:', error);
                });
        }
        
        // Add document ready listener to set up date inputs after the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Set up date input handling
            setupDateInputs();
            
            // Initialize date pickers for the filter section
            initDatePickers();
            
            // Fetch and populate dropdown options
            fetchSubscriptionOptions();
            fetchProgramOptions();
            
            // Fetch initial subscription data
            fetchSubscriptionData();
        });
        
        // Function to fetch subscription options
        function fetchSubscriptionOptions() {
            fetch('../../api/subscription/get_subscription_options.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const subFilterSelect = document.getElementById('subFilter');
                        if (subFilterSelect) {
                            // Keep the "All Subscriptions" option
                            const allOption = subFilterSelect.options[0];
                            subFilterSelect.innerHTML = '';
                            subFilterSelect.appendChild(allOption);
                            
                            // Add options from API
                            data.subscriptions.forEach(subscription => {
                                const option = document.createElement('option');
                                option.value = subscription.id;
                                option.textContent = subscription.name;
                                subFilterSelect.appendChild(option);
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching subscription options:', error);
                });
        }
        
        // Function to fetch program options
        function fetchProgramOptions() {
            fetch('../../api/program/get_program_options.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const programFilterSelect = document.getElementById('programFilter');
                        if (programFilterSelect) {
                            // Keep the "All Programs" option
                            const allOption = programFilterSelect.options[0];
                            programFilterSelect.innerHTML = '';
                            programFilterSelect.appendChild(allOption);
                            
                            // Add options from API
                            data.programs.forEach(program => {
                                const option = document.createElement('option');
                                option.value = program.id;
                                option.textContent = program.name;
                                programFilterSelect.appendChild(option);
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching program options:', error);
                });
        }
        
        // Function to set up date inputs with MM/DD/YYYY format
        function setupDateInputs() {
            const startDateInput = document.getElementById('startDateInput');
            const endDateInput = document.getElementById('endDateInput');
            
            if (startDateInput) {
                // Add event listener for start date changes to calculate end date
                startDateInput.addEventListener('input', function() {
                    // Try to parse the date in MM/DD/YYYY format
                    const parts = this.value.split('/');
                    if (parts.length === 3) {
                        const month = parseInt(parts[0]) - 1; // Month is 0-based
                        const day = parseInt(parts[1]);
                        const year = parseInt(parts[2]);
                        
                        // Only process if we have a valid date
                        if (!isNaN(month) && !isNaN(day) && !isNaN(year)) {
                            const date = new Date(year, month, day);
                            
                            // Calculate end date based on subscription
                            const subscriptionSelect = document.getElementById('subscriptionSelect');
                            if (subscriptionSelect && subscriptionSelect.value) {
                                const selectedOption = subscriptionSelect.options[subscriptionSelect.selectedIndex];
                                const duration = selectedOption.getAttribute('data-duration');
                                
                                if (duration) {
                                    // Calculate end date
                                    const endDate = calculateEndDate(date, duration);
                                    
                                    // Update end date input
                                    if (endDateInput) {
                                        endDateInput.value = formatDisplayInputDate(endDate);
                                    }
                                    
                                    // Update summary display
                                    document.getElementById('subStartDate').textContent = formatDisplayDate(date);
                                    document.getElementById('subEndDate').textContent = formatDisplayDate(endDate);
                                }
                            }
                        }
                    }
                });
                
                // Initialize with today's date
                const today = new Date();
                startDateInput.value = formatDisplayInputDate(today);
            }
        }

        // Format date for input fields (YYYY-MM-DD)
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
        
        // Format date for display in the input field (MM/DD/YYYY)
        function formatDisplayInputDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${month}/${day}/${year}`;
        }

        // Function to show toast notification
        function showToast(message, isSuccess = true) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');
            
            if (!toast || !toastMessage || !toastIcon) return;
            
            // Set message
            toastMessage.textContent = message;
            
            // Set icon and color based on status
            if (isSuccess) {
                toast.classList.remove('bg-red-600');
                toast.classList.add('bg-green-600');
                toastIcon.classList.remove('fa-exclamation-circle');
                toastIcon.classList.add('fa-check-circle');
            } else {
                toast.classList.remove('bg-green-600');
                toast.classList.add('bg-red-600');
                toastIcon.classList.remove('fa-check-circle');
                toastIcon.classList.add('fa-exclamation-circle');
            }
            
            // Show the toast
            toast.style.display = 'flex';
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);
            
            // Hide after 3 seconds
            setTimeout(hideToast, 3000);
        }
        
        // Function to hide toast notification
        function hideToast() {
            const toast = document.getElementById('toast');
            if (!toast) return;
            
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                toast.style.display = 'none';
            }, 300);
        }
        
        // Function to show confirmation dialog
        function showConfirmationDialog(title, message, confirmCallback) {
            const dialog = document.getElementById('confirmationDialog');
            const titleElement = document.getElementById('confirmationTitle');
            const messageElement = document.getElementById('confirmationMessage');
            const confirmButton = document.getElementById('confirmAction');
            const cancelButton = document.getElementById('cancelConfirmation');
            
            if (!dialog || !titleElement || !messageElement || !confirmButton || !cancelButton) return;
            
            // Set dialog content
            titleElement.textContent = title;
            messageElement.textContent = message;
            
            // Remove any previous event listeners with a clone
            const newConfirmButton = confirmButton.cloneNode(true);
            confirmButton.parentNode.replaceChild(newConfirmButton, confirmButton);
            
            // Add new confirm action
            newConfirmButton.addEventListener('click', function() {
                // Hide the dialog
                dialog.classList.add('hidden');
                // Execute the callback
                if (typeof confirmCallback === 'function') {
                    confirmCallback();
                }
            });
            
            // Setup cancel button
            cancelButton.addEventListener('click', function() {
                dialog.classList.add('hidden');
            });
            
            // Show the dialog
            dialog.classList.remove('hidden');
        }
        
        // Function to reset transaction modal UI for new transactions
        function resetTransactionModalUI() {
            // Reset member search section
            const memberSearchContainer = document.querySelector('#addTransactionForm #memberSearch').closest('div').parentElement;
            if (memberSearchContainer) {
                memberSearchContainer.classList.remove('hidden');
            }
            
            // Hide selected member info
            const selectedMemberInfo = document.getElementById('selectedMemberInfo');
            if (selectedMemberInfo) {
                selectedMemberInfo.classList.add('hidden');
            }
            
            // Reset member info section title
            const memberInfoSection = document.querySelector('.mb-1');
            if (memberInfoSection) {
                const memberHeading = memberInfoSection.querySelector('span');
                if (memberHeading) {
                    memberHeading.textContent = "Member Information";
                }
            }
            
            // Reset the change member button
            const changeMemberBtn = document.getElementById('changeMemberBtn');
            if (changeMemberBtn) {
                changeMemberBtn.classList.remove('hidden');
            }
            
            // Reset form fields
            document.getElementById('addTransactionForm').reset();
            
            // Reset any error states
            document.querySelectorAll('#addTransactionForm .error-border').forEach(el => {
                el.classList.remove('error-border');
            });
            document.querySelectorAll('#addTransactionForm .error-message').forEach(el => {
                el.remove();
            });
            
            // Reset subscription summary
            document.getElementById('subName').textContent = '-';
            document.getElementById('subDuration').textContent = '-';
            document.getElementById('subStartDate').textContent = '-';
            document.getElementById('subEndDate').textContent = '-';
            document.getElementById('subPrice').textContent = '-';
        }
        
        // Function to update summary cards with new data
        function updateSummaryCards() {
            // Fetch latest data or use the data we already have
            fetch('../../api/transaction/get_transactions.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update summary cards with new values
                        document.getElementById('totalTransactions').textContent = data.total_transactions;
                        document.getElementById('totalRevenue').textContent = '$' + data.total_revenue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        document.getElementById('recentTransactions').textContent = data.recent_transactions;
                        
                        // Refresh subscription data if needed
                        fetchSubscriptionData();
                    }
                })
                .catch(error => {
                    console.error('Error updating summary cards:', error);
                });
        }
        
        // Function to setup member search in the add transaction modal
        function setupMemberSearchInModal() {
            const memberSearch = document.getElementById('memberSearch');
            const memberSearchResults = document.getElementById('memberSearchResults');
            const selectedMemberInfo = document.getElementById('selectedMemberInfo');
            const memberInitials = document.getElementById('memberInitials');
            const memberName = document.getElementById('memberName');
            const memberEmail = document.getElementById('memberEmail');
            const selectedMemberId = document.getElementById('selectedMemberId');
            const changeMemberBtn = document.getElementById('changeMemberBtn');
            
            if (!memberSearch || !memberSearchResults || !selectedMemberInfo) return;
            
            // Setup search input event
            memberSearch.addEventListener('input', function() {
                if (this.value.length < 2) {
                    memberSearchResults.classList.add('hidden');
                    return;
                }
                
                // Simulate a search - in real implementation, this would call an API
                const searchTerm = this.value.toLowerCase();
                fetch(`../../api/member/search_members.php?term=${searchTerm}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Clear previous results
                            memberSearchResults.innerHTML = '';
                            
                            if (data.members.length === 0) {
                                memberSearchResults.innerHTML = '<div class="p-3 text-gray-500">No members found</div>';
                            } else {
                                data.members.forEach(member => {
                                    const item = document.createElement('div');
                                    item.className = 'autocomplete-item';
                                    
                                    // Get initials for avatar
                                    let initials = '';
                                    const nameParts = member.name.split(' ');
                                    if (nameParts.length >= 2) {
                                        initials = nameParts[0][0] + nameParts[1][0];
                                    } else {
                                        initials = nameParts[0][0];
                                    }
                                    
                                    item.innerHTML = `
                                        <div class="autocomplete-avatar">${initials}</div>
                                        <div class="autocomplete-info">
                                            <div class="autocomplete-name">${member.name}</div>
                                            <div class="autocomplete-email">${member.email}</div>
                                        </div>
                                    `;
                                    
                                    // Add click event to select this member
                                    item.addEventListener('click', function() {
                                        // Hide search results
                                        memberSearchResults.classList.add('hidden');
                                        
                                        // Clear search input
                                        memberSearch.value = '';
                                        
                                        // Update selected member info
                                        memberInitials.textContent = initials;
                                        memberName.textContent = member.name;
                                        memberEmail.textContent = member.email;
                                        selectedMemberId.value = member.id;
                                        
                                        // Show selected member info
                                        selectedMemberInfo.classList.remove('hidden');
                                    });
                                    
                                    memberSearchResults.appendChild(item);
                                });
                            }
                            
                            // Show results
                            memberSearchResults.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error searching members:', error);
                    });
            });
            
            // Close search results when clicking outside
            document.addEventListener('click', function(e) {
                if (!memberSearch.contains(e.target) && !memberSearchResults.contains(e.target)) {
                    memberSearchResults.classList.add('hidden');
                }
            });
            
            // Setup change member button
            if (changeMemberBtn) {
                changeMemberBtn.addEventListener('click', function() {
                    // Hide selected member info
                    selectedMemberInfo.classList.add('hidden');
                    
                    // Clear selected member data
                    selectedMemberId.value = '';
                    
                    // Focus search input
                    memberSearch.focus();
                });
            }
        }

        // Initialize date pickers for filters
        function initDatePickers() {
            // Initialize flatpickr for start date
            flatpickr("#startDate", {
                dateFormat: "m/d/Y",
                defaultDate: "today",
                maxDate: "today",
                disableMobile: "true",
                onChange: function(selectedDates, dateStr, instance) {
                    // Update the end date picker with a minimum date
                    endDatePicker.set('minDate', dateStr);
                }
            });
            
            // Initialize flatpickr for end date
            const endDatePicker = flatpickr("#endDate", {
                dateFormat: "m/d/Y",
                defaultDate: new Date().fp_incr(7), // Default to 7 days from today
                disableMobile: "true"
            });
        }

        // Add refresh button functionality
        const refreshSubsBtn = document.getElementById('refreshSubsBtn');
        if (refreshSubsBtn) {
            refreshSubsBtn.addEventListener('click', function() {
                // Show loading state
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Refreshing...';
                this.disabled = true;
                
                // Fetch latest subscription data
                fetchSubscriptionData()
                    .then(() => {
                        // Restore button state
                        this.innerHTML = originalHTML;
                        this.disabled = false;
                        
                        // Show success notification
                        showToast('Subscription data refreshed successfully!', true);
                    })
                    .catch(error => {
                        console.error('Error refreshing subscription data:', error);
                        
                        // Restore button state
                        this.innerHTML = originalHTML;
                        this.disabled = false;
                        
                        // Show error notification
                        showToast('Error refreshing data. Please try again.', false);
                    });
            });
        }

        // Add event listener for Apply Filters button
        const applyFiltersBtn = document.getElementById('applyFiltersBtn');
        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', function() {
                try {
                    // Hide any existing errors
                    hideErrorAlert();
                    
                    // Show loading state
                    const originalHTML = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Applying...';
                    this.disabled = true;
                    
                    // Get filter values
                    const startDateInput = document.getElementById('startDate');
                    const endDateInput = document.getElementById('endDate');
                    const subFilterSelect = document.getElementById('subFilter');
                    const programFilterSelect = document.getElementById('programFilter');
                    const memberSearchInput = document.getElementById('memberSearch');
                    
                    // Format dates properly for API (YYYY-MM-DD)
                    let startDate = '';
                    let endDate = '';
                    
                    if (startDateInput && startDateInput._flatpickr) {
                        const startDateObj = startDateInput._flatpickr.selectedDates[0];
                        if (startDateObj) {
                            startDate = formatDate(startDateObj); // YYYY-MM-DD format
                        }
                    } else if (startDateInput && startDateInput.value) {
                        // Try to parse the date from the input value
                        const parts = startDateInput.value.split('/');
                        if (parts.length === 3) {
                            // Handle MM/DD/YYYY format
                            const month = parseInt(parts[0]) - 1;
                            const day = parseInt(parts[1]);
                            const year = parseInt(parts[2]);
                            startDate = `${year}-${(month+1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                        }
                    }
                    
                    if (endDateInput && endDateInput._flatpickr) {
                        const endDateObj = endDateInput._flatpickr.selectedDates[0];
                        if (endDateObj) {
                            endDate = formatDate(endDateObj); // YYYY-MM-DD format
                        }
                    } else if (endDateInput && endDateInput.value) {
                        // Try to parse the date from the input value
                        const parts = endDateInput.value.split('/');
                        if (parts.length === 3) {
                            // Handle MM/DD/YYYY format
                            const month = parseInt(parts[0]) - 1;
                            const day = parseInt(parts[1]);
                            const year = parseInt(parts[2]);
                            endDate = `${year}-${(month+1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                        }
                    }
                    
                    // Validate dates
                    if (startDate && endDate) {
                        const startDateObj = new Date(startDate);
                        const endDateObj = new Date(endDate);
                        
                        if (isNaN(startDateObj.getTime()) || isNaN(endDateObj.getTime())) {
                            throw new Error("Invalid date format. Please use MM/DD/YYYY format.");
                        }
                        
                        if (startDateObj > endDateObj) {
                            throw new Error("Start date cannot be after end date.");
                        }
                    }
                    
                    const subFilter = subFilterSelect ? subFilterSelect.value : 'all';
                    const programFilter = programFilterSelect ? programFilterSelect.value : 'all';
                    const memberSearch = memberSearchInput ? memberSearchInput.value : '';
                    
                    // Highlight active filters visually
                    highlightActiveFilters(subFilter, programFilter);
                    
                    // Build query string
                    let queryParams = new URLSearchParams();
                    if (startDate) queryParams.append('start_date', startDate);
                    if (endDate) queryParams.append('end_date', endDate);
                    if (subFilter && subFilter !== 'all') queryParams.append('subscription_id', subFilter);
                    if (programFilter && programFilter !== 'all') queryParams.append('program_id', programFilter);
                    if (memberSearch) queryParams.append('member_search', memberSearch);
                    
                    console.log("Filter API request:", `../../api/subscription/filter_subscriptions.php?${queryParams.toString()}`);
                    
                    // Fetch filtered data
                    fetch(`../../api/subscription/filter_subscriptions.php?${queryParams.toString()}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Update subscription table with filtered data
                                updateSubscriptionTable(data.subscriptions);
                                
                                // Show filter badge with count
                                showFilterBadge(data.total);
                                
                                // Update expiring count
                                const expiringElement = document.getElementById('expiringSubscriptions');
                                if (expiringElement) {
                                    expiringElement.textContent = data.expiring_count;
                                }
                                
                                // Update table header to show filtered status
                                updateTableHeaderStatus(data.total, subFilter, programFilter);
                                
                                // Show success notification
                                showToast(`Found ${data.total} subscription(s) matching your filters`, true);
                            } else {
                                // Show error notification
                                showErrorAlert('Error applying filters: ' + (data.message || 'Unknown error'));
                                showToast('Error applying filters: ' + (data.message || 'Unknown error'), false);
                            }
                            
                            // Restore button state
                            this.innerHTML = originalHTML;
                            this.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error applying filters:', error);
                            
                            // Restore button state
                            this.innerHTML = originalHTML;
                            this.disabled = false;
                            
                            // Show error alert
                            showErrorAlert('Error applying filters. Please try again.');
                            
                            // Show error notification
                            showToast('Error applying filters. Please try again.', false);
                        });
                } catch (error) {
                    console.error('Error in filter application:', error);
                    
                    // Restore button state
                    this.innerHTML = originalHTML;
                    this.disabled = false;
                    
                    // Show error alert
                    showErrorAlert(error.message || 'Error applying filters. Please try again.');
                    
                    // Show error notification
                    showToast(error.message || 'Error applying filters. Please try again.', false);
                }
            });
        }
        
        // Function to show error alert
        function showErrorAlert(message) {
            const errorAlert = document.getElementById('errorAlert');
            const errorAlertMessage = document.getElementById('errorAlertMessage');
            
            if (!errorAlert || !errorAlertMessage) return;
            
            errorAlertMessage.textContent = message;
            errorAlert.classList.remove('hidden');
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                hideErrorAlert();
            }, 5000);
        }
        
        // Function to hide error alert
        function hideErrorAlert() {
            const errorAlert = document.getElementById('errorAlert');
            if (errorAlert) {
                errorAlert.classList.add('hidden');
            }
        }
        
        // Function to highlight active filters visually
        function highlightActiveFilters(subFilter, programFilter) {
            // Get the select elements
            const subFilterSelect = document.getElementById('subFilter');
            const programFilterSelect = document.getElementById('programFilter');
            
            // Reset all filter styles
            [subFilterSelect, programFilterSelect].forEach(select => {
                if (select) {
                    select.parentElement.classList.remove('ring-2', 'ring-blue-500');
                }
            });
            
            // Highlight active subscription filter
            if (subFilter && subFilter !== 'all' && subFilterSelect) {
                subFilterSelect.parentElement.classList.add('ring-2', 'ring-blue-500');
            }
            
            // Highlight active program filter
            if (programFilter && programFilter !== 'all' && programFilterSelect) {
                programFilterSelect.parentElement.classList.add('ring-2', 'ring-blue-500');
            }
        }
        
        // Function to update the table header status
        function updateTableHeaderStatus(totalCount, subFilter, programFilter) {
            const tableHeader = document.querySelector('#transactionResults h2 + p');
            if (!tableHeader) return;
            
            let statusText = 'Showing ';
            
            // Add count
            if (totalCount === 0) {
                statusText += 'no subscriptions';
            } else if (totalCount === 1) {
                statusText += '1 subscription';
            } else {
                statusText += `${totalCount} subscriptions`;
            }
            
            // Add filter information
            const activeFilters = [];
            
            if (subFilter && subFilter !== 'all') {
                const subText = document.querySelector(`#subFilter option[value="${subFilter}"]`)?.textContent || 'selected subscription';
                activeFilters.push(subText);
            }
            
            if (programFilter && programFilter !== 'all') {
                const programText = document.querySelector(`#programFilter option[value="${programFilter}"]`)?.textContent || 'selected program';
                activeFilters.push(programText);
            }
            
            if (activeFilters.length > 0) {
                statusText += ` filtered by ${activeFilters.join(' and ')}`;
            }
            
            tableHeader.textContent = statusText;
        }
        
        // Add event listener for Reset Filters button
        const resetFiltersBtn = document.getElementById('resetFiltersBtn');
        if (resetFiltersBtn) {
            resetFiltersBtn.addEventListener('click', function() {
                // Reset date pickers to default values
                const startDatePicker = document.getElementById('startDate')._flatpickr;
                const endDatePicker = document.getElementById('endDate')._flatpickr;
                
                if (startDatePicker) {
                    startDatePicker.setDate(new Date(), true);
                }
                
                if (endDatePicker) {
                    endDatePicker.setDate(new Date().fp_incr(7), true);
                }
                
                // Reset dropdowns
                document.getElementById('subFilter').value = 'all';
                document.getElementById('programFilter').value = 'all';
                document.getElementById('memberSearch').value = '';
                
                // Reset visual filter indicators
                highlightActiveFilters('all', 'all');
                
                // Fetch all subscription data (unfiltered)
                fetchSubscriptionData()
                    .then((data) => {
                        // Remove any filter badge if present
                        const filterBadge = document.getElementById('filterBadge');
                        if (filterBadge) filterBadge.remove();
                        
                        // Reset table header status
                        updateTableHeaderStatus(data.subscriptions.length, 'all', 'all');
                        
                        // Show notification
                        showToast('Filters reset successfully', true);
                    })
                    .catch(error => {
                        console.error('Error resetting filters:', error);
                        showToast('Error resetting filters. Please try again.', false);
                    });
            });
        }
    </script>
</body>
</html>