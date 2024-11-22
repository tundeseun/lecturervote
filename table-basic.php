<?php
include 'conn.php';

$supervisor_id = 973;
$status = 0;

$query = "
    SELECT 
        vr.id,
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
        vr.timestamp IS NOT NULL AND 
        vr.status = ?;

      ";

try {
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ii", $supervisor_id, $status);
  $stmt->execute();
  $result = $stmt->get_result();
} catch (mysqli_sql_exception $e) {
  die('Error: ' . $e->getMessage());
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
        <h6 class="font-semibold mb-0 dark:text-white">Basic Table</h6>
        <ul class="flex items-center gap-[6px]">
          <li class="font-medium">
            <a href="index.php" class="flex items-center gap-2 hover:text-primary-600 dark:text-white">
              <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
              Dashboard
            </a>
          </li>
          <li class="dark:text-white">-</li>
          <li class="font-medium dark:text-white">Basic Table</li>
        </ul>
      </div>

      <div class="col-span-12 lg:col-span-6">
        <div class="card border-0 overflow-hidden">
          <div class="card-header">
            <h5 class="card-title text-lg mb-0">Bordered Tables</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table basic-border-table mb-0">
                <thead>
                  <tr>
                    <th class="border-r border-neutral-200 dark:border-neutral-600 last:border-r-0">S/N </th>
                    <th class="border-r border-neutral-200 dark:border-neutral-600 last:border-r-0">Matric</th>
                    <th class="border-r border-neutral-200 dark:border-neutral-600 last:border-r-0">Surname</th>
                    <th class="border-r border-neutral-200 dark:border-neutral-600 last:border-r-0">Other Names</th>
                    <th class="border-r border-neutral-200 dark:border-neutral-600 last:border-r-0">Email</th>
                    <th class="border-r border-neutral-200 dark:border-neutral-600 last:border-r-0">Phone No.</th>
                    <th class="border-r border-neutral-200 dark:border-neutral-600 last:border-r-0">Paper Title</th>
                    <th class="border-r border-neutral-200 dark:border-neutral-600 last:border-r-0">Paper URl</th>
                    <th class="border-r border-neutral-200 dark:border-neutral-600 last:border-r-0">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <?php
                    $i = 0;
                    if ($result && $result->num_rows > 0) {
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
    

    <!-- Approve Button -->
    <a href="javascript:void(0)" 
       class="approve-btn w-8 h-8 bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 rounded-full inline-flex items-center justify-center"
       data-request-id="' . htmlspecialchars($row['id']) . '">
      <iconify-icon icon="lucide:check-circle"></iconify-icon>
      <span class="sr-only">Approve</span>
    </a>

    <!-- Reject Button -->
    <a href="javascript:void(0)" 
       class="reject-btn w-8 h-8 bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 rounded-full inline-flex items-center justify-center"
       data-request-id="' . htmlspecialchars($row['id']) . '">
      <iconify-icon icon="mingcute:close-circle-line"></iconify-icon>
      <span class="sr-only">Reject</span>
    </a>
  </td>';

                        echo '</tr>';
                      }
                    } else {
                      echo '<tr><td colspan="8">No data found</td></tr>';
                    }
                    ?>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div><!-- card end -->
      </div>


    </div><!-- card end -->
    </div>

    </div>
    </div>

    <footer class="d-footer">
      <div class="flex items-center justify-between gap-3">
        <p class="mb-0">© 2024 The Postgraduate College. All Rights Reserved.</p>
        <p class="mb-0">Made by <span class="text-primary-600">Pgcollege ICT Team</span></p>
      </div>
    </footer>
  </main>



  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const approveButtons = document.querySelectorAll('.approve-btn');
      const rejectButtons = document.querySelectorAll('.reject-btn');

      approveButtons.forEach((button) => {
        button.addEventListener('click', (e) => {
          const requestId = button.getAttribute('data-request-id');
          console.log('Request ID:', requestId); 

          if (confirm('Are you sure you want to approve this request?')) {
            fetch('approverequest.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                  id: requestId 
                }),
              })
              .then((response) => response.json())
              .then((data) => {
                if (data.success) {
                  button.closest('tr').remove();  
                  alert('Request approved successfully!');
                } else {
                  alert('Failed to approve the request. Please try again.');
                }
              })
              .catch((error) => {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
              });
          }
        });
      });

      rejectButtons.forEach((button) => {
        button.addEventListener('click', (e) => {
          const requestId = button.getAttribute('data-request-id');

          if (confirm('Are you sure you want to reject this request?')) {
            fetch('rejectrequest.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                  id: requestId
                })
              })
              .then((response) => response.json())
              .then((data) => {
                if (data.success) {
                  button.closest('tr').remove();  
                  alert('Request rejected successfully!');
                } else {
                  alert('Failed to reject the request. Please try again.');
                }
              })
              .catch((error) => {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
              });
          }
        });
      });
    });
</script>

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
</body>

</html>