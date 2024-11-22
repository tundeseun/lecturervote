<?php
include 'conn.php';

$supervisor_id = 973;

$query = "
    SELECT 
        vr.user_id, 
        vr.paper_title, 
        vr.paper_url, 
        n.Surname, 
        n.Other_names, 
        n.email, 
        n.Telephone, 
        pa.matric AS matric_number
    FROM 
        vote_request vr
    LEFT JOIN 
        new n ON vr.user_id = n.id
    LEFT JOIN 
        prev_app pa ON vr.user_id = pa.user_id
    WHERE 
        vr.supervisor_id = ? AND 
        vr.timestamp IS NOT NULL;
";

try {
  $stmt = $conn->prepare($query);

  $stmt->bind_param("i", $supervisor_id);

  $stmt->execute();

  $result = $stmt->get_result();
} catch (mysqli_sql_exception $e) {
  echo 'Error: ' . $e->getMessage();
} finally {
  $conn->close();
}
?>

<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pgcollege - Vote Management System</title>
  <link rel="icon" type="image/png" href="assets/images/favicon.png" sizes="16x16">
  <!-- google fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <!-- remix icon font css  -->
  <link rel="stylesheet" href="assets/css/remixicon.css">
  <!-- Apex Chart css -->
  <link rel="stylesheet" href="assets/css/lib/apexcharts.css">
  <!-- Data Table css -->
  <link rel="stylesheet" href="assets/css/lib/dataTables.min.css">
  <!-- Text Editor css -->
  <link rel="stylesheet" href="assets/css/lib/editor-katex.min.css">
  <link rel="stylesheet" href="assets/css/lib/editor.atom-one-dark.min.css">
  <link rel="stylesheet" href="assets/css/lib/editor.quill.snow.css">
  <!-- Date picker css -->
  <link rel="stylesheet" href="assets/css/lib/flatpickr.min.css">
  <!-- Calendar css -->
  <link rel="stylesheet" href="assets/css/lib/full-calendar.css">
  <!-- Vector Map css -->
  <link rel="stylesheet" href="assets/css/lib/jquery-jvectormap-2.0.5.css">
  <!-- Popup css -->
  <link rel="stylesheet" href="assets/css/lib/magnific-popup.css">
  <!-- Slick Slider css -->
  <link rel="stylesheet" href="assets/css/lib/slick.css">
  <!-- prism css -->
  <link rel="stylesheet" href="assets/css/lib/prism.css">
  <!-- file upload css -->
  <link rel="stylesheet" href="assets/css/lib/file-upload.css">

  <link rel="stylesheet" href="assets/css/lib/audioplayer.css">
  <!-- main css -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="dark:bg-neutral-800 bg-neutral-100 dark:text-white">
  <aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
      <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
      <a href="index.php" class="sidebar-logo">
        <img src="assets/images/logo.png" alt="site logo" class="light-logo">
        <img src="assets/images/logo-light.png" alt="site logo" class="dark-logo">
        <img src="assets/images/logo-icon.png" alt="site logo" class="logo-icon">
      </a>
    </div>
    <div class="sidebar-menu-area">
      <ul class="sidebar-menu" id="sidebar-menu">
        <li class="">
          <a href="/">
            <iconify-icon
              icon="solar:home-smile-angle-outline"
              class="menu-icon"></iconify-icon>
            <span>Dashboard</span>
          </a>
        </li>


        <li class="sidebar-menu-group-title">UI Elements</li>


        <li class="dropdown">
          <a href="javascript:void(0)">
            <iconify-icon icon="heroicons:document" class="menu-icon"></iconify-icon>
            <span>Forms</span>
          </a>
          <ul class="sidebar-submenu">
            <li>
              <a href="form.html"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Input Forms</a>
            </li>
            <li>
              <a href="form-layout.html"><i class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> Input Layout</a>
            </li>
            <li>
              <a href="form-validation.html"><i class="ri-circle-fill circle-icon text-success-600 w-auto"></i> Form Validation</a>
            </li>

          </ul>
        </li>
        <li class="dropdown">
          <a href="javascript:void(0)">
            <iconify-icon icon="mingcute:storage-line" class="menu-icon"></iconify-icon>
            <span>Requests</span>
          </a>
          <ul class="sidebar-submenu">
            <li>
              <a href="table-basic.php"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> New Request Pending Approval</a>
            </li>
            <li>
              <a href="table-data.php"><i class="ri-circle-fill circle-icon text-warning-600 w-auto"></i> All Request</a>
            </li>
          </ul>
        </li>


      </ul>
    </div>
  </aside>

  <main class="dashboard-main">
    <div
      class="navbar-header border-b border-neutral-200 dark:border-neutral-600">
      <div class="flex items-center justify-between">
        <div class="col-auto">
          <div class="flex flex-wrap items-center gap-[16px]">
            <button type="button" class="sidebar-toggle">
              <iconify-icon
                icon="heroicons:bars-3-solid"
                class="icon non-active"></iconify-icon>
              <iconify-icon
                icon="iconoir:arrow-right"
                class="icon active"></iconify-icon>
            </button>
            <button type="button" class="sidebar-mobile-toggle">
              <iconify-icon
                icon="heroicons:bars-3-solid"
                class="icon"></iconify-icon>
            </button>

            <!-- <span class="max-w-[244px] w-full p-6 h-3 bg-red-600 text-white flex items-center justify-center rounded-[50px]">tesdgxt</span> -->
          </div>
        </div>
        <div class="col-auto">
          <div class="flex flex-wrap items-center gap-3">
            <h6>Vote Management System</h6>
          </div>
        </div>
        <div class="col-auto">
          <div class="flex flex-wrap items-center gap-3">
            <button
              type="button"
              id="theme-toggle"
              class="w-10 h-10 bg-neutral-200 dark:bg-neutral-700 dark:text-white rounded-full flex justify-center items-center">
              <span id="theme-toggle-dark-icon" class="hidden">
                <i class="ri-sun-line"></i>
              </span>
              <span id="theme-toggle-light-icon" class="hidden">
                <i class="ri-moon-line"></i>
              </span>
            </button>

            <button
              data-dropdown-toggle="dropdownProfile"
              class="flex justify-center items-center rounded-full"
              type="button">
              <a
                class="text-black px-0 py-2 hover:text-danger-600 flex items-center gap-4"
                href="javascript:void(0)">
                <iconify-icon
                  icon="lucide:power"
                  class="icon text-xl"></iconify-icon>
                Log Out</a>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="dashboard-main-body">

      <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h6 class="font-semibold mb-0 dark:text-white">New Request Pending Approval</h6>
        <ul class="flex items-center gap-[6px]">
          <li class="font-medium">
            <a href="index.php" class="flex items-center gap-2 hover:text-primary-600 dark:text-white">
              <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
              Dashboard
            </a>
          </li>
          <li class="dark:text-white">-</li>
          <li class="font-medium dark:text-white">New Request Pending Approval</li>
        </ul>
      </div>

      <div class="grid grid-cols-12">
        <div class="col-span-12">
          <div class="card border-0 overflow-hidden">
            <div class="card-header">
              <h6 class="card-title mb-0 text-lg">STUDENT DATA</h6>
            </div>
            <div class="card-body">
              <table id="selection-table" class="border border-neutral-200 dark:border-neutral-600 rounded-lg border-separate	">
                <thead>
                  <tr>
                    <th scope="col" class="text-neutral-800 dark:text-white">
                      <div class="form-check style-check flex items-center">
                        <input class="form-check-input" id="serial" type="checkbox">
                        <label class="ms-2 form-check-label" for="serial">
                          S.N
                        </label>
                      </div>
                    </th>
                    <th scope="col" class="text-neutral-800 dark:text-white">
                      <div class="flex items-center gap-2">
                        MATRIC
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                      </div>
                    </th>
                    <th scope="col" class="text-neutral-800 dark:text-white">
                      <div class="flex items-center gap-2">
                        SURNAME
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                      </div>
                    </th>
                    <th scope="col" class="text-neutral-800 dark:text-white">
                      <div class="flex items-center gap-2">
                        OTHER NAMES
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                      </div>
                    </th>

                    <th scope="col" class="text-neutral-800 dark:text-white">
                      <div class="flex items-center gap-2">
                        EMAIL
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                      </div>
                    </th>
                    <th scope="col" class="text-neutral-800 dark:text-white">
                      <div class="flex items-center gap-2">
                        TELEPHONE
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                      </div>
                    </th>
                    <th scope="col" class="text-neutral-800 dark:text-white">
                      <div class="flex items-center gap-2">
                        PAPER TITLE
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                      </div>
                    </th>
                    <th scope="col" class="text-neutral-800 dark:text-white">
                      <div class="flex items-center gap-2">
                        URL
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                      </div>
                    </th>
                    <th scope="col" class="text-neutral-800 dark:text-white">
                      <div class="flex items-center gap-2">
                        Action
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody>


                  <?php

                  $i = 0;
                  // $counter = 1;
                  while ($row = $result->fetch_assoc()) {
                    $i++;
                    echo '<tr>';
                    echo '<td>' . $i . '</td>';
                    echo '<td>' . (isset($row['matric_number']) ? htmlspecialchars($row['matric_number']) : 'N/A') . '</td>';
                    echo '<td><h6 class="text-base mb-0 font-medium grow">' . (isset($row['Surname']) ? htmlspecialchars($row['Surname']) : 'N/A') . '</h6></td>';
                    echo '<td><h6 class="text-base mb-0 font-medium grow">' . (isset($row['Other_names']) ? htmlspecialchars($row['Other_names']) : 'N/A') . '</h6></td>';
                    echo '<td>' . (isset($row['email']) ? htmlspecialchars($row['email']) : 'N/A') . '</td>';
                    echo '<td>' . (isset($row['Telephone']) ? htmlspecialchars($row['Telephone']) : 'N/A') . '</td>';
                    echo '<td>' . (isset($row['paper_title']) ? htmlspecialchars($row['paper_title']) : 'No Title') . '</td>';
                    echo '<td><a href="' . (isset($row['paper_url']) ? htmlspecialchars($row['paper_url']) : '#') . '" target="_blank">View Paper</a></td>';
                    echo '<td>
                    <a href="javascript:void(0)" class="w-8 h-8 bg-primary-50 dark:bg-primary-600/10 text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                      <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-8 h-8 bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 rounded-full inline-flex items-center justify-center">
                      <iconify-icon icon="lucide:edit"></iconify-icon>
                    </a>
                    <a href="javascript:void(0)" class="w-8 h-8 bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 rounded-full inline-flex items-center justify-center">
                      <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                    </a>
                  </td>';
                    echo '</tr>';
                  }
                  ?>
                  <tr>

                  
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="col-span-12 lg:col-span-6">
      <div class="card border-0 overflow-hidden">
        <div class="card-header">
          <h5 class="card-title text-lg mb-0">Tables Border Colors</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table border-primary-600 mb-0">
              <thead>
                <tr>
                  <th scope="col" class="border-r border-b border-primary-600 last:border-r-0">
                    <div class="flex items-center">
                      <input id="sl" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-neutral-400 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                      <label for="sl" class="ms-1.5 text-md font-medium text-gray-900 dark:text-gray-300">S.L</label>
                    </div>
                  </th>
                  <th scope="col" class="border-r border-b border-primary-600 last:border-r-0">Transaction ID</th>
                  <th scope="col" class="border-r border-b border-primary-600 last:border-r-0">Date</th>
                  <th scope="col" class="border-r border-b border-primary-600 last:border-r-0">Status</th>
                  <th scope="col" class="border-r border-b border-primary-600 last:border-r-0">Amount</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">
                    <div class="flex items-center">
                      <input id="sl1" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-neutral-400 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                      <label for="sl1" class="ms-1.5 text-md font-normal text-gray-600 dark:text-gray-300">S.L</label>
                    </div>
                  </td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">5986124445445</td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">27 Mar 2024</td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">
                    <span class="bg-warning-100 dark:bg-warning-600/25 text-warning-600 dark:text-warning-400 px-8 py-1.5 rounded-full font-medium text-sm">Pending</span>
                  </td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">$20,000.00</td>
                </tr>
                <tr>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">
                    <div class="flex items-center">
                      <input id="sl2" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-neutral-400 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                      <label for="sl2" class="ms-1.5 text-md font-normal text-gray-600 dark:text-gray-300">S.L</label>
                    </div>
                  </td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">5986124445445</td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">27 Mar 2024</td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">
                    <span class="bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 px-8 py-1.5 rounded-full font-medium text-sm">Rejected</span>
                  </td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">$20,000.00</td>
                </tr>
                <tr>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">
                    <div class="flex items-center">
                      <input id="sl3" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-neutral-400 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                      <label for="sl3" class="ms-1.5 text-md font-normal text-gray-600 dark:text-gray-300">S.L</label>
                    </div>
                  </td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">5986124445445</td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">27 Mar 2024</td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">
                    <span class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-8 py-1.5 rounded-full font-medium text-sm">Completed</span>
                  </td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">$20,000.00</td>
                </tr>
                <tr>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">
                    <div class="flex items-center">
                      <input id="sl4" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-neutral-400 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                      <label for="sl4" class="ms-1.5 text-md font-normal text-gray-600 dark:text-gray-300">S.L</label>
                    </div>
                  </td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">5986124445445</td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">27 Mar 2024</td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">
                    <span class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-8 py-1.5 rounded-full font-medium text-sm">Completed</span>
                  </td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">$20,000.00</td>
                </tr>
                <tr>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">
                    <div class="flex items-center">
                      <input id="sl5" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-neutral-400 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                      <label for="sl5" class="ms-1.5 text-md font-normal text-gray-600 dark:text-gray-300">S.L</label>
                    </div>
                  </td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">5986124445445</td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">27 Mar 2024</td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">
                    <span class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-8 py-1.5 rounded-full font-medium text-sm">Completed</span>
                  </td>
                  <td class="border-r border-b !border-primary-600 last:border-r-0">$20,000.00</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- card end -->
    </div>

    <footer class="d-footer">
      <div class="flex items-center justify-between gap-3">
        <p class="mb-0">© 2024 The Postgraduate College. All Rights Reserved.</p>
        <p class="mb-0">Made by <span class="text-primary-600">Pgcollege ICT Team</span></p>
      </div>
    </footer>
  </main>

  <!-- jQuery library js -->
  <script src="assets/js/lib/jquery-3.7.1.min.js"></script>
  <!-- Apex Chart js -->
  <script src="assets/js/lib/apexcharts.min.js"></script>
  <!-- Data Table js -->
  <script src="assets/js/lib/simple-datatables.min.js"></script>
  <!-- Iconify Font js -->
  <script src="assets/js/lib/iconify-icon.min.js"></script>
  <!-- jQuery UI js -->
  <script src="assets/js/lib/jquery-ui.min.js"></script>
  <!-- Vector Map js -->
  <script src="assets/js/lib/jquery-jvectormap-2.0.5.min.js"></script>
  <script src="assets/js/lib/jquery-jvectormap-world-mill-en.js"></script>
  <!-- Popup js -->
  <script src="assets/js/lib/magnifc-popup.min.js"></script>
  <!-- Slick Slider js -->
  <script src="assets/js/lib/slick.min.js"></script>
  <!-- prism js -->
  <script src="assets/js/lib/prism.js"></script>
  <!-- file upload js -->
  <script src="assets/js/lib/file-upload.js"></script>
  <!-- audioplayer -->
  <script src="assets/js/lib/audioplayer.js"></script>

  <script src="assets/js/flowbite.min.js"></script>
  <!-- main js -->
  <script src="assets/js/app.js"></script>


  <script>
    if (document.getElementById("selection-table") && typeof simpleDatatables.DataTable !== 'undefined') {

      let multiSelect = true;
      let rowNavigation = false;
      let table = null;

      const resetTable = function() {
        if (table) {
          table.destroy();
        }

        const options = {
          columns: [{
              select: [0, 6],
              sortable: false
            } // Disable sorting on the first column (index 0 and 6)
          ],
          rowRender: (row, tr, _index) => {
            if (!tr.attributes) {
              tr.attributes = {};
            }
            if (!tr.attributes.class) {
              tr.attributes.class = "";
            }
            if (row.selected) {
              tr.attributes.class += " selected";
            } else {
              tr.attributes.class = tr.attributes.class.replace(" selected", "");
            }
            return tr;
          }
        };
        if (rowNavigation) {
          options.rowNavigation = true;
          options.tabIndex = 1;
        }

        table = new simpleDatatables.DataTable("#selection-table", options);

        // Mark all rows as unselected
        table.data.data.forEach(data => {
          data.selected = false;
        });

        table.on("datatable.selectrow", (rowIndex, event) => {
          event.preventDefault();
          const row = table.data.data[rowIndex];
          if (row.selected) {
            row.selected = false;
          } else {
            if (!multiSelect) {
              table.data.data.forEach(data => {
                data.selected = false;
              });
            }
            row.selected = true;
          }
          table.update();
        });
      };

      // Row navigation makes no sense on mobile, so we deactivate it and hide the checkbox.
      const isMobile = window.matchMedia("(any-pointer:coarse)").matches;
      if (isMobile) {
        rowNavigation = false;
      }

      resetTable();
    }
  </script>
</body>

</html>