<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Vote Request</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-header {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            text-align: center;
            font-size: 1.5rem;
        }
        .form-footer {
            text-align: center;
            margin-top: 20px;
        }
        .form-footer a {
            color: #007bff;
        }
        button.btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>
<div class="form-container">
    <div class="form-header">Create Vote Request</div>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="user_id" class="form-label">User ID</label>
            <input type="number" class="form-control" id="user_id" name="user_id" placeholder="Enter your user ID" required>
        </div>
        <div class="mb-3">
            <label for="supervisor_id" class="form-label">Supervisor</label>
            <select class="form-select" id="supervisor_id" name="supervisor_id" required>
                <option value="">Select a Supervisor</option>
                <?php foreach ($supervisors as $supervisor): ?>
                    <option value="<?= $supervisor['id'] ?>"><?= htmlspecialchars($supervisor['full_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="paper_title" class="form-label">Paper Title</label>
            <textarea class="form-control" id="paper_title" name="paper_title" rows="2" placeholder="Enter the paper title" required></textarea>
        </div>
        <div class="mb-3">
            <label for="journal_name" class="form-label">Journal Name</label>
            <textarea class="form-control" id="journal_name" name="journal_name" rows="2" placeholder="Enter the journal name" required></textarea>
        </div>
        <div class="mb-3">
            <label for="paper_url" class="form-label">Paper URL</label>
            <input type="url" class="form-control" id="paper_url" name="paper_url" placeholder="Enter the URL of the paper" required>
        </div>
        <div class="mb-3">
            <label for="publish_date" class="form-label">Publish Date</label>
            <input type="date" class="form-control" id="publish_date" name="publish_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <div class="form-footer">
        <p>Need help? <a href="#">Contact Support</a></p>
    </div>
</div>
</body>
</html>
