<?php
// Include the connection file
require_once 'connect.php';

// Use prepared statements for secure queries
$sql = "
    SELECT 
        vr.id AS sn,
        pa.matric,
        CONCAT(n.surname, ' ', n.other_names) AS full_name,
        n.email AS email,
        s.name AS supervisor_name,
        vr.journal_name,
        vr.paper_title,
        vr.paper_url,
        vr.publish_date
    FROM vote_request vr
    LEFT JOIN new n ON vr.user_id = n.id
    LEFT JOIN supervisors s ON vr.supervisor_id = s.id
    LEFT JOIN prev_app pa ON vr.user_id = pa.user_id
    WHERE vr.status = ?
    ORDER BY vr.publish_date DESC 
";

$stmt = $conn->prepare($sql);
$status = 1;
$stmt->bind_param('i', $status);
$stmt->execute();
$result = $stmt->get_result();

$results = [];
while ($row = $result->fetch_assoc()) {
  $results[] = $row;
}




$stmt->close();
$conn->close();

?>



<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pgcollege - Vote Management System</title>
  <link rel="icon" type="image/png" href="assets/images/logo.png" sizes="16x16" />
  <!-- google fonts -->
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
    rel="stylesheet" />
  <!-- remix icon font css  -->
  <link rel="stylesheet" href="assets/css/remixicon.css" />
  <!-- Apex Chart css -->
  <link rel="stylesheet" href="assets/css/lib/apexcharts.css" />
  <!-- Data Table css -->
  <link rel="stylesheet" href="assets/css/lib/dataTables.min.css" />
  <!-- Text Editor css -->
  <link rel="stylesheet" href="assets/css/lib/editor-katex.min.css" />
  <link rel="stylesheet" href="assets/css/lib/editor.atom-one-dark.min.css" />
  <link rel="stylesheet" href="assets/css/lib/editor.quill.snow.css" />
  <!-- Date picker css -->
  <link rel="stylesheet" href="assets/css/lib/flatpickr.min.css" />
  <!-- Calendar css -->
  <link rel="stylesheet" href="assets/css/lib/full-calendar.css" />
  <!-- Vector Map css -->
  <link rel="stylesheet" href="assets/css/lib/jquery-jvectormap-2.0.5.css" />
  <!-- Popup css -->
  <link rel="stylesheet" href="assets/css/lib/magnific-popup.css" />
  <!-- Slick Slider css -->
  <link rel="stylesheet" href="assets/css/lib/slick.css" />
  <!-- prism css -->
  <link rel="stylesheet" href="assets/css/lib/prism.css" />
  <!-- file upload css -->
  <link rel="stylesheet" href="assets/css/lib/file-upload.css" />

  <link rel="stylesheet" href="assets/css/lib/audioplayer.css" />
  <!-- main css -->
  <link rel="stylesheet" href="assets/css/style.css" />

  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css">

  <style>
    .btn-primary-home {
      background: #0a2b4f !important;
      color: #fff !important;
    }

    /* Modal styles */
    .modal {
      display: none;
      /* Hidden by default */
      position: fixed;
      z-index: 1050;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
      /* Black with opacity */

    }

    .modal-dialog {
      position: relative;
      margin: auto;
      top: 50%;
      transform: translateY(-50%);
      width: 90%;
      max-width: 500px;
    }

    .modal-content {
      background-color: #fff;
      border-radius: 10px;
      padding: 2rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      position: relative;

    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #ddd;
    }

    .modal-title {
      font-size: 1.25rem;
      font-weight: bold;
      margin: 0;
    }

    .btn-close {
      background: none;
      border: none;
      font-size: 1.5rem;
      line-height: 1;
      cursor: pointer;
      color: #000;
      position: absolute;
      top: 10px;
      right: 10px;
    }

    .btn-close:hover {
      color: #ff0000;
      /* Red color on hover */
    }


    .modal-body p {
      margin: 0.5rem 0;
    }

    .modal-body a {
      color: #007bff;
      text-decoration: none;
    }

    .modal-body a:hover {
      text-decoration: underline;
    }

    .flex {
      display: flex;
    }

    .items-center {
      align-items: center;
    }

    .justify-center {
      justify-content: center;
    }

    .gap-3 {
      gap: 1rem;
    }

    .btn {
      padding: 0.5rem 1rem;
      font-size: 1rem;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-primary-home {
      background-color: #007bff;
      color: #fff;
    }

    .btn-primary-home:hover {
      background-color: #0056b3;
    }

    .btn-danger {
      background-color: #dc3545;
      color: #fff;
    }

    .btn-danger:hover {
      background-color: #c82333;
    }

    .datatable-active .datatable-pagination-list-item-link {
      background: #0a2b4f !important;
      color: #fff !important;
    }

    .badge {
      background-color: #007bff;
      color: white;
      padding: 1px 3px;
      border-radius: 5px;
      font-size: 12px;
      font-weight: bold;
      display: inline-block;
    }

    .badge-warning {
      background-color: #ffc107;
      color: #212529;
      /* For readable text on warning background */
    }

    .badge-success {
      background-color: #198754;
      color: #ffffff;
      /* For readable text on success background */
    }
  </style>

</head>

<body class="dark:bg-neutral-800 bg-neutral-100 dark:text-white">
  <aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
      <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
      <a href="/" class="sidebar-logo">
        <img src="assets/images/logo.png" alt="site logo" class="light-logo" />
        <img src="assets/images/logo-light.png" alt="site logo" class="dark-logo" />
        <img src="assets/images/logo.png" alt="site logo" class="logo-icon" />
      </a>
    </div>
    <div class="sidebar-menu-area">
      <ul class="sidebar-menu" id="sidebar-menu">
        <li class="">
          <a href="javascript:void(0)" class="active-page">
            <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
            <span>Dashboard <span id="pendingApprovalCountBadge" class="badge badge-warning"></span></span>
          </a>
        </li>

        <li class="sidebar-menu-group-title">UI Elements</li>

        <li class="">
          <a href="approved-page.php">
            <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
            <span>Approved Paper <span id="approved_count_badge" class="badge badge-success"></span></span>
          </a>
        </li>

      </ul>
    </div>
  </aside>

  <main class="dashboard-main">
    <div class="navbar-header border-b border-neutral-200 dark:border-neutral-600">
      <div class="flex items-center justify-between">
        <div class="col-auto">
          <div class="flex flex-wrap items-center gap-[16px]">
            <button type="button" class="sidebar-toggle">
              <iconify-icon icon="heroicons:bars-3-solid" class="icon non-active"></iconify-icon>
              <iconify-icon icon="iconoir:arrow-right" class="icon active"></iconify-icon>
            </button>
            <button type="button" class="sidebar-mobile-toggle">
              <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
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
            <button type="button" id="theme-toggle"
              class="w-10 h-10 bg-neutral-200 dark:bg-neutral-700 dark:text-white rounded-full flex justify-center items-center">
              <span id="theme-toggle-dark-icon" class="hidden">
                <i class="ri-sun-line"></i>
              </span>
              <span id="theme-toggle-light-icon" class="hidden">
                <i class="ri-moon-line"></i>
              </span>
            </button>

            <button data-dropdown-toggle="dropdownProfile" class="flex justify-center items-center rounded-full"
              type="button">
              <a class="text-black px-0 py-2 hover:text-danger-600 flex items-center gap-4" href="javascript:void(0)">
                <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon>
                Log Out</a>
            </button>

          </div>
        </div>
      </div>
    </div>

    <div class="dashboard-main-body">
      <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
        <h6 class="font-semibold mb-0 dark:text-white">Dashboard</h6>

      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"> <!-- Dashboard Widget Start -->
        <div
          class="card px-xl-6 px-4 py-4 shadow-none rounded-lg border-neutral-200 dark:border-neutral-600 h-full bg-gradient-to-r from-primary-600/10 to-bg-white">
          <div class="card-body p-0">
            <div class="flex flex-wrap items-center justify-between gap-1">
              <div class="flex items-center">
                <div
                  class="w-[64px] h-[64px] rounded-2xl bg-white flex-shrink-0 dark:bg-neutral-800/75 flex justify-center items-center me-2xl-5 me-xl-4 me-3">
                  <span
                    class="w-10 h-10 bg-primary-600 shrink-0 text-white flex justify-center items-center rounded-lg h6 mb-0">
                    <iconify-icon icon="flowbite:users-group-solid" class="icon"></iconify-icon>
                  </span>
                </div>

                <div>
                  <span class="mb-2 font-medium text-secondary-light text-base">Pending Approval</span>
                  <h6 class="font-semibold my-1" id="pendingApprovalCount"></h6>
                  <p class="text-sm mb-0">
                    Increase by
                    <span id="pending_approved_records_this_week"
                      class="bg-success-100 dark:bg-success-600/50 px-1 py-0.5 rounded-sm font-medium text-success-600 dark:text-success-400 text-sm">+</span>
                    this week
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Dashboard Widget End -->

        <!-- Dashboard Widget Start -->
        <div
          class="card px-xl-6 px-4 py-4 shadow-none rounded-lg border-neutral-200 dark:border-neutral-600 h-full bg-gradient-to-r from-primary-600/10 to-bg-white">
          <div class="card-body p-0">
            <div class="flex flex-wrap items-center justify-between gap-1">
              <div class="flex items-center">
                <div
                  class="w-[64px] h-[64px] rounded-2xl bg-white flex-shrink-0 dark:bg-neutral-800/75 flex justify-center items-center me-2xl-5 me-xl-4 me-3">
                  <span
                    class="w-10 h-10 bg-primary-600 shrink-0 text-white flex justify-center items-center rounded-lg h6 mb-0">
                    <iconify-icon icon="flowbite:users-group-solid" class="icon"></iconify-icon>
                  </span>
                </div>

                <div>
                  <span class="mb-2 font-medium text-secondary-light text-base">Approved</span>
                  <h6 class="font-semibold my-1" id="approved_count"></h6>
                  <p class="text-sm mb-0">
                    Increase by
                    <span id="approved_records_this_week"
                      class="bg-success-100 dark:bg-success-600/50 px-1 py-0.5 rounded-sm font-medium text-success-600 dark:text-success-400 text-sm">+</span>
                    this week
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Dashboard Widget End -->

        <!-- Dashboard Widget Start -->
        <div
          class="card px-xl-6 px-4 py-4 shadow-none rounded-lg border-neutral-200 dark:border-neutral-600 h-full bg-gradient-to-r from-red-600/10 to-bg-white">
          <div class="card-body p-0">
            <div class="flex flex-wrap items-center justify-between gap-1">
              <div class="flex items-center">
                <div
                  class="w-[64px] h-[64px] rounded-2xl bg-white flex-shrink-0 dark:bg-neutral-800/75 flex justify-center items-center me-2xl-5 me-xl-4 me-3">
                  <span
                    class="w-10 h-10 bg-red-600 shrink-0 text-white flex justify-center items-center rounded-lg h6 mb-0">
                    <iconify-icon icon="fa6-solid:file-invoice-dollar" class="text-white text-2xl mb-0"></iconify-icon>
                  </span>
                </div>

                <div>
                  <span class="mb-2 font-medium text-secondary-light text-base">Total Expense</span>
                  <h6 class="font-semibold my-1">15,000</h6>
                  <p class="text-sm mb-0">
                    Increase by
                    <span
                      class="bg-success-100 dark:bg-success-600/50 px-1 py-0.5 rounded-sm font-medium text-success-600 dark:text-success-400 text-sm">+200</span>
                    this week
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Dashboard Widget End -->

        <!-- Dashboard Widget Start -->
        <div
          class="card px-xl-6 px-4 py-4 shadow-none rounded-lg border-neutral-200 dark:border-neutral-600 h-full bg-gradient-to-r from-success-600/10 to-bg-white">
          <div class="card-body p-0">
            <div class="flex flex-wrap items-center justify-between gap-1">
              <div class="flex items-center">
                <div
                  class="w-[64px] h-[64px] rounded-2xl bg-white flex-shrink-0 dark:bg-neutral-800/75 flex justify-center items-center me-2xl-5 me-xl-4 me-3">
                  <span
                    class="w-10 h-10 bg-success-600 shrink-0 text-white flex justify-center items-center rounded-lg h6 mb-0">
                    <iconify-icon icon="streamline:bag-dollar-solid" class="icon"></iconify-icon>
                  </span>
                </div>

                <div>
                  <span class="mb-2 font-medium text-secondary-light text-base">Total Earning</span>
                  <h6 class="font-semibold my-1">15,000</h6>
                  <p class="text-sm mb-0">
                    Increase by
                    <span
                      class="bg-success-100 dark:bg-success-600/50 px-1 py-0.5 rounded-sm font-medium text-success-600 dark:text-success-400 text-sm">+200</span>
                    this week
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Dashboard Widget End -->
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">
        <!-- Revenue Statistics Start -->
        <div class="col-span-12 2xl:col-span-8">
          <div class="card h-full rounded-lg border-0">
            <div class="card-header">
              <h6 class="card-title mb-0 text-lg">Paper(s) Awaiting Approval</h6>
            </div>
            <div class="card-body table-responsive">
              <table id="selection-table" class="table sm-table bordered-table mb-0">
                <thead>
                  <tr>
                    <th scope="col" class="text-neutral-800 dark:text-white">
                      <div class="form-check style-check flex items-center">
                        <label class="ms-2 form-check-label" for="serial">S/N</label>
                      </div>
                    </th>
                    <th scope="col" class="text-neutral-800 dark:text-white">
                      <div class="flex items-center gap-2">
                        Matric
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                          height="24" fill="none" viewBox="0 0 24 24">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                      </div>
                    </th>
                    <th scope="col" class="text-neutral-800 dark:text-white">
                      <div class="flex items-center gap-2">Name
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                          height="24" fill="none" viewBox="0 0 24 24">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                      </div>
                    </th>
                    <th scope="col" class="text-neutral-800 dark:text-white">
                      <div class="flex items-center gap-2">Supervisor
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                          height="24" fill="none" viewBox="0 0 24 24">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                      </div>
                        </th>
                      <th scope="col" class="text-neutral-800 dark:text-white">View to Approve/Reject</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($results)): ?>
                    <?php $i = 0;
                    foreach ($results as $row):
                      $i++ ?>
                      <tr>
                        <td>
                          <div class="form-check style-check flex items-center">
                                <label class=" ms-2 form-check-label">
                            <?= $i ?></label>
                              </div>
                            </td>
                            <td><h6 class="text-base mb-0 font-medium grow"><?= htmlspecialchars($row['matric']) ?>
                            </h6>
                        </td>
                        <td>
                          <h6 class="text-base mb-0 font-medium grow">
                            <?= htmlspecialchars($row['full_name']) ?>
                          </h6>
                        </td>
                        <td>
                          <h6 class="text-base mb-0 font-medium grow">
                            <?= htmlspecialchars($row['supervisor_name']) ?>
                          </h6>
                        </td>
                        <td>
                          <button class="btn btn-primary-home gap-1 " data-bs-toggle="modal" data-bs-target="#infoModal"
                            onclick="loadModalContent(<?= htmlspecialchars(json_encode($row)) ?>)">
                                    <iconify-icon icon="iconamoon:eye-light" class="mt-1"></iconify-icon>
                                      View
                          </button>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" class="text-center">No records found</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>

              <!-- Modal -->
              <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header mb-10">
                      <h5 class="modal-title" id="infoModalLabel">Additional Information</h5>
                      <button type="button" class="btn-close" aria-label="Close"
                        data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" data-id="">
                      <p><strong>Matric:</strong> <span id="modal-matric"></span></p>
                      <p><strong>Name:</strong> <span id="modal-name"></span></p>
                      <p><strong>Email:</strong> <span id="modal-email"></span></p>
                      <p><strong>Supervisor Name:</strong> <span id="modal-supervisor-name"></span></p>
                      <p><strong>Journal Name:</strong> <span id="modal-journal-name"></span></p>
                      <p><strong>Paper Title:</strong> <span id="modal-paper-title"></span></p>
                      <p><strong>Publish Date:</strong> <span id="modal-publish-date"></span></p>
                      <p><strong>Paper URL:</strong> <a id="modal-paper-url" href="#" target="_blank">Visit</a></p>
                      <div class="flex items-center justify-center gap-3 mt-10">
                        <button id="approveButton" class="btn btn-primary-home text-center">
                          <iconify-icon icon="iconamoon:mark"></iconify-icon>
                          Approve
                        </button>
                        <button id="rejectButton" class="btn btn-danger text-center">
                          <iconify-icon icon="iconamoon:close"></iconify-icon>
                          Reject
                        </button>
                      </div>

                    </div>
                  </div>
                </div>
              </div>

              <!-- Reject Modal -->
              <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header mb-10">
                      <h5 class="modal-title" id="rejectModalLabel">Rejection Reason</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close">&times;</button>
                    </div>
                    <div class="modal-body">
                      <form id="rejectForm">
                        <div class="mb-3">
                          <textarea class="form-control" id="rejectionReason" rows="4"
                            placeholder="Enter the reason for rejection"></textarea>
                        </div>
                        <button type="button" id="submitRejection" class="btn btn-danger">Submit Rejection</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>



            </div>
          </div>
        </div>
        <!-- Revenue Statistics End -->

      </div>
    </div>

    <footer class="d-footer">
      <div class="flex items-center justify-between gap-3">
        <p class="mb-0">© 2024 The Postgraduate College. All Rights Reserved.</p>
        <p class="mb-0">
          Developed by <span class="text-primary-600">Pgcollege ICT Team</span>
        </p>
      </div>
    </footer>
  </main>

  </div>

  <!-- JavaScript for Modal -->
  <script>
    function loadModalContent(row) {
      const modalBody = document.querySelector("#infoModal .modal-body");

      modalBody.setAttribute("data-id", row.sn);
      document.getElementById('modal-matric').textContent = row.matric;
      document.getElementById('modal-email').textContent = row.email;
      document.getElementById('modal-name').textContent = row.full_name;
      document.getElementById('modal-supervisor-name').textContent = row.supervisor_name;
      document.getElementById('modal-journal-name').textContent = row.journal_name;
      document.getElementById('modal-paper-title').textContent = row.paper_title;
      document.getElementById('modal-publish-date').textContent = row.publish_date;
      document.getElementById('modal-paper-url').textContent = row.paper_url;
      document.getElementById('modal-paper-url').href = row.paper_url;
    }

    // Modal functionality
    document.addEventListener("DOMContentLoaded", function () {
      const modal = document.getElementById("infoModal");
      const closeButton = modal.querySelector(".btn-close");
      const approveButton = document.getElementById("approveButton");


      // Open the modal
      function openModal() {
        modal.style.display = "block";
      }

      // Close the modal
      function closeModal() {
        modal.style.display = "none";
      }

      // Close the modal when clicking outside of the content
      window.onclick = function (event) {
        if (event.target === modal) {
          closeModal();
        }
      };

      // Close the modal when the close button is clicked
      closeButton.addEventListener("click", closeModal);


      approveButton.addEventListener("click", function () {
        const recordId = modal.querySelector(".modal-body").getAttribute("data-id");
        console.log("Record ID:", recordId);

        if (recordId) {
          // Send the request to the backend

          fetch("approve.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({ id: recordId, status: 2 }),

          })
            .then((response) => {
              if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
              }
              return response.json();
            })
            .then((data) => {
              if (data.success) {
                Swal.fire({
                  title: "Success!",
                  text: "Record approved successfully!",
                  icon: "success",
                  confirmButtonText: "OK"
                }).then(() => {
                  modal.style.display = "none"; // Close the modal
                  location.reload();

                });
              } else {
                Swal.fire({
                  title: "Error!",
                  text: "Failed to approve the record.",
                  icon: "error",
                  confirmButtonText: "Try Again"
                });
              }
            })
            .catch((error) => {
              console.error("Error:", error);
              Swal.fire({
                title: "Error!",
                text: "An unexpected error occurred. Please try again.",
                icon: "error",
                confirmButtonText: "OK"
              });
            });

        }
      });
    });

    document.addEventListener("DOMContentLoaded", function () {
      const modal = document.getElementById("infoModal");
      const rejectButton = document.getElementById("rejectButton");
      const rejectModal = document.getElementById("rejectModal");
      const submitRejectionButton = document.getElementById("submitRejection");

      // Open Reject Modal
      rejectButton.addEventListener("click", function () {
        const recordId = modal.querySelector(".modal-body").getAttribute("data-id");
        const email = modal.querySelector("#modal-email").textContent.trim(); // Fetch email dynamically

        if (recordId) {
          // Pass parent modal's ID and email to reject modal
          rejectModal.setAttribute("data-parent-id", recordId);
          rejectModal.setAttribute("data-email", email);

          // Open the reject modal
          const rejectModalInstance = new bootstrap.Modal(rejectModal);
          rejectModalInstance.show();
        }
      });

      // Handle Rejection Submission
      submitRejectionButton.addEventListener("click", function () {
        const recordId = rejectModal.getAttribute("data-parent-id");
        // const email = rejectModal.getAttribute("data-email");
        const email = "iljimae2019@gmail.com";
        const rejectionReason = document.getElementById("rejectionReason").value.trim();

        if (!rejectionReason) {
          Swal.fire({
            title: "Error!",
            text: "Please provide a reason for rejection.",
            icon: "error",
            confirmButtonText: "OK",
          });
          return;
        }

        // Show loading indicator
        Swal.fire({
          title: "Submitting...",
          text: "Please wait while we process the rejection.",
          allowOutsideClick: false,
          showConfirmButton: false,
          didOpen: () => {
            Swal.showLoading();
          },
        });

        // Send rejection data to the server
        fetch("reject.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            id: recordId,
            status: -1,
            reason: rejectionReason,
            email: email,
          }),
        })
          .then((data) => {
            Swal.close(); // Close loading indicator

            if (data.success) {
              Swal.fire({
                title: "Success!",
                text: "Paper rejected successfully, and email sent to the student!",
                icon: "success",
                confirmButtonText: "OK",
              }).then(() => {
                // Close reject modal and optionally reload data
                const rejectModalInstance = bootstrap.Modal.getInstance(rejectModal);
                rejectModalInstance.hide();
                modal.style.display = "none";
                location.reload(); // Reload page to update UI
              });
            } else {
              Swal.fire({
                title: "Warning!",
                text: data.message || "Paper rejected successfully, but email notification failed.",
                icon: "warning",
                confirmButtonText: "OK",
              }).then(() => {
                const rejectModalInstance = bootstrap.Modal.getInstance(rejectModal);
                rejectModalInstance.hide();
                modal.style.display = "none";
                location.reload();
              });
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            Swal.fire({
              title: "Error!",
              text: error.message || "An unexpected error occurred. Please try again.",
              icon: "error",
              confirmButtonText: "OK",
            }).then(() => {
              const rejectModalInstance = bootstrap.Modal.getInstance(rejectModal);
              rejectModalInstance.hide();
              modal.style.display = "none";
              location.reload();
            });
          });
      });
    });

  </script>



  <!-- Include Bootstrap CSS and JS (if not already included in your project) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.js"></script>

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

  <script src="assets/js/homeFiveChart.js"></script>


  <script>
    if (document.getElementById("selection-table") && typeof simpleDatatables.DataTable !== 'undefined') {

      let multiSelect = true;
      let rowNavigation = false;
      let table = null;

      const resetTable = function () {
        if (table) {
          table.destroy();
        }

        const options = {
          columns: [
            { select: [0, 4], sortable: false } // Disable sorting on the first column (index 0 and 6)
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

  <script>
    $(document).ready(function () {
      // Fetch dashboard data
      $.ajax({
        url: 'fetch_counts.php',
        method: 'GET',
        success: function (response) {
          // Update the HTML with fetched data
          $('#pending_approved_records_this_week').text("+" + response.pending_approved_records_this_week);
          $('#approved_records_this_week').text("+" + response.approved_records_this_week);
          $('#pendingApprovalCount').text(response.pendingApprovalCount);
          $('#approved_count').text(+ response.approved_count);
          $('#pendingApprovalCountBadge').text(response.pendingApprovalCount);
          $('#approved_count_badge').text(+ response.approved_count);
        },
        error: function (xhr, status, error) {
          console.error("Error fetching data:", error);
        }
      });

    });
  </script>

</body>

</html>